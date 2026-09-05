<?php
declare(strict_types=1);
namespace App\Repository;

use App\Database\Connection;
use App\Support\Date;

final class FlashRepository
{
    private FlashMediaRepository $media;
    private FlashIntelligenceRepository $intelligence;
    public function __construct() { $this->media=new FlashMediaRepository(); $this->intelligence=new FlashIntelligenceRepository(); }

    public function create(int $userId,int $categoryId,int $sourceTypeId,string $description,float $lat,float $lng,?string $areaName,int $expiresAfterMinutes): array
    {
        $pdo=Connection::get(); $now=Date::now(); $expires=$now->modify(sprintf('+%d minutes',$expiresAfterMinutes));
        $s=$pdo->prepare("INSERT INTO flashes (user_id,category_id,source_type_id,description,location,area_name,expires_at,created_at,updated_at) VALUES (:user_id,:category_id,:source_type_id,:description,ST_GeomFromText(CONCAT('POINT(',:lng,' ',:lat,')'),4326),:area_name,:expires_at,:created_at,:updated_at)");
        $s->execute(['user_id'=>$userId,'category_id'=>$categoryId,'source_type_id'=>$sourceTypeId,'description'=>$description!==''?$description:null,'lat'=>$lat,'lng'=>$lng,'area_name'=>$areaName,'expires_at'=>$expires->format('Y-m-d H:i:s'),'created_at'=>$now->format('Y-m-d H:i:s'),'updated_at'=>$now->format('Y-m-d H:i:s')]);
        return $this->find((int)$pdo->lastInsertId(),null) ?? throw new \RuntimeException('Flash was not created');
    }


    public function findRecentDuplicate(int $userId,int $categoryId,string $description,float $lat,float $lng,int $windowSeconds=600,float $radiusMeters=250): ?array
    {
        $description=trim($description);
        if($description==='') return null;

        $pdo=Connection::get();
        $sql="SELECT id FROM flashes
              WHERE user_id=:user_id
                AND category_id=:category_id
                AND description=:description
                AND created_at>=DATE_SUB(UTC_TIMESTAMP(), INTERVAL :window_seconds SECOND)
                AND ST_Distance_Sphere(location,POINT(:lng,:lat))<=:radius_meters
              ORDER BY id DESC
              LIMIT 1";
        $s=$pdo->prepare($sql);
        $s->execute([
            'user_id'=>$userId,
            'category_id'=>$categoryId,
            'description'=>$description,
            'lat'=>$lat,
            'lng'=>$lng,
            'window_seconds'=>$windowSeconds,
            'radius_meters'=>$radiusMeters,
        ]);
        $id=$s->fetchColumn();
        return $id===false?null:$this->find((int)$id,null,$userId);
    }

    public function find(int $id,?array $origin,?int $viewerId=null): ?array
    {
        $sql=$this->selectSql($origin)." WHERE f.id=:id AND f.moderation_status='visible' GROUP BY f.id LIMIT 1";
        $s=Connection::get()->prepare($sql); $params=['id'=>$id]; if($origin) $params+=$origin; $s->execute($params);
        return ($row=$s->fetch())?$this->map($row,$viewerId):null;
    }

    public function canObserve(int $flashId): bool
    {
        $s=Connection::get()->prepare("SELECT 1 FROM flashes WHERE id=:id AND moderation_status='visible' AND lifecycle_status='active' AND expires_at>UTC_TIMESTAMP() LIMIT 1");
        $s->execute(['id'=>$flashId]); return (bool)$s->fetchColumn();
    }

    public function feed(float $lat,float $lng,float $radiusKm,array $categoryKeys,?string $since,int $limit,?int $viewerId=null): array { return $this->feedPage($lat,$lng,$radiusKm,$categoryKeys,$since,$limit,null,$viewerId)['flashes']; }

    public function feedPage(float $lat,float $lng,float $radiusKm,array $categoryKeys,?string $since,int $limit,?string $cursor,?int $viewerId=null): array
    {
        $origin=['origin_lat'=>$lat,'origin_lng'=>$lng];
        $params=[...$origin,'filter_lat'=>$lat,'filter_lng'=>$lng,'radius_m'=>$radiusKm*1000];
        $sql=$this->selectSql($origin)." WHERE f.moderation_status='visible' AND f.lifecycle_status='active' AND f.expires_at>UTC_TIMESTAMP() AND ST_Distance_Sphere(f.location,POINT(:filter_lng,:filter_lat))<=:radius_m";
        if($categoryKeys!==[]){$p=[];foreach($categoryKeys as $i=>$key){$n='category_'.$i;$p[]=':'.$n;$params[$n]=$key;} $sql.=' AND c.category_key IN ('.implode(',',$p).')';}
        if($since){$ts=strtotime($since);if($ts===false) throw new \InvalidArgumentException('Invalid since value');$params['since']=gmdate('Y-m-d H:i:s',$ts);$sql.=' AND f.created_at>=:since';}
        $decoded=$this->decodeCursor($cursor);
        $sql.=' GROUP BY f.id';
        if($decoded){$sql.=' HAVING distance_m>:cursor_distance_after OR (distance_m=:cursor_distance_equal AND (f.created_at<:cursor_created_after OR (f.created_at=:cursor_created_equal AND f.id<:cursor_id)))';$params['cursor_distance_after']=$decoded['d'];$params['cursor_distance_equal']=$decoded['d'];$params['cursor_created_after']=$decoded['c'];$params['cursor_created_equal']=$decoded['c'];$params['cursor_id']=$decoded['i'];}
        $sql.=' ORDER BY distance_m ASC,f.created_at DESC,f.id DESC LIMIT '.($limit+1);
        $s=Connection::get()->prepare($sql);$s->execute($params);$rows=$s->fetchAll();$hasMore=count($rows)>$limit;if($hasMore) array_pop($rows);
        $flashes=array_map(fn(array $row): array => $this->map($row,$viewerId),$rows);$next=null;
        if($hasMore&&$rows!==[]){$last=$rows[array_key_last($rows)];$next=base64_encode(json_encode(['d'=>(float)$last['distance_m'],'c'=>$last['created_at'],'i'=>(int)$last['id']],JSON_THROW_ON_ERROR));}
        return ['flashes'=>$flashes,'next_cursor'=>$next];
    }

    public function observe(int $flashId,int $userId,int $observationTypeId,?string $note): ?array
    {
        $now=Date::now()->format('Y-m-d H:i:s');
        Connection::get()->prepare("INSERT INTO flash_observations (flash_id,user_id,observation_type_id,note,created_at,updated_at) VALUES (:flash_id,:user_id,:observation_type_id,:note,:created_at,:updated_at) ON DUPLICATE KEY UPDATE observation_type_id=VALUES(observation_type_id),note=VALUES(note),updated_at=VALUES(updated_at)")
        ->execute(['flash_id'=>$flashId,'user_id'=>$userId,'observation_type_id'=>$observationTypeId,'note'=>$note,'created_at'=>$now,'updated_at'=>$now]);
        return $this->find($flashId,null,$userId);
    }

    private function decodeCursor(?string $cursor): ?array
    {
        if(!$cursor) return null; $json=base64_decode($cursor,true); if($json===false) throw new \InvalidArgumentException('Invalid cursor');
        $data=json_decode($json,true); if(!is_array($data)||!isset($data['d'],$data['c'],$data['i'])||!is_numeric($data['d'])||!is_string($data['c'])||!is_numeric($data['i'])) throw new \InvalidArgumentException('Invalid cursor');
        return ['d'=>(float)$data['d'],'c'=>$data['c'],'i'=>(int)$data['i']];
    }

    private function selectSql(?array $origin): string
    {
        $distance=$origin?", ST_Distance_Sphere(f.location,POINT(:origin_lng,:origin_lat)) AS distance_m":', NULL AS distance_m';
        return "SELECT f.id,f.user_id,f.description,f.area_name,f.lifecycle_status,f.verification_state,f.moderation_status,f.expires_at,f.resolved_at,f.created_at,f.updated_at,ST_Y(f.location) AS lat,ST_X(f.location) AS lng".$distance.",c.category_key,c.name AS category_name,c.icon AS category_icon,u.display_name,COALESCE(SUM(ot.observation_key='still_happening'),0) AS confirm_count,COALESCE(SUM(ot.observation_key='cleared'),0) AS dispute_count,(SELECT COUNT(*) FROM flash_views fv WHERE fv.flash_id=f.id) AS engagement_views,(SELECT COUNT(*) FROM flash_share_events fse WHERE fse.flash_id=f.id) AS engagement_shares,(SELECT COUNT(*) FROM flash_helpful_reactions fhr WHERE fhr.flash_id=f.id) AS engagement_helpful FROM flashes f JOIN categories c ON c.id=f.category_id JOIN users u ON u.id=f.user_id LEFT JOIN flash_observations fo ON fo.flash_id=f.id LEFT JOIN observation_types ot ON ot.id=fo.observation_type_id";
    }

    private function map(array $row,?int $viewerId=null): array
    {
        $status=$row['lifecycle_status'];if($status==='active'&&strtotime($row['expires_at'].' UTC')<=time())$status='expired';
        $intel=$this->intelligence->forFlash((int)$row['id']);
        $markedHelpful=false;
        if($viewerId!==null){
            $stmt=Connection::get()->prepare('SELECT 1 FROM flash_helpful_reactions WHERE flash_id=:flash_id AND user_id=:user_id LIMIT 1');
            $stmt->execute(['flash_id'=>(int)$row['id'],'user_id'=>$viewerId]);
            $markedHelpful=(bool)$stmt->fetchColumn();
        }
        return ['id'=>(int)$row['id'],'category'=>['key'=>$row['category_key'],'name'=>$row['category_name'],'icon'=>$row['category_icon']],'status'=>$status,'verification_state'=>$row['verification_state'],'description'=>$row['description'],'location'=>['lat'=>(float)$row['lat'],'lng'=>(float)$row['lng'],'area_name'=>$row['area_name']],'distance_km'=>$row['distance_m']===null?null:round(((float)$row['distance_m'])/1000,2),'reporter'=>['id'=>(int)$row['user_id'],'display_name'=>$row['display_name']],'confirm_count'=>(int)$row['confirm_count'],'dispute_count'=>(int)$row['dispute_count'],'intelligence'=>['confidence_score'=>(float)$intel['confidence_score'],'state'=>$intel['state'],'last_evaluated_at'=>$intel['last_evaluated_at']],'engagement'=>['views'=>(int)$row['engagement_views'],'shares'=>(int)$row['engagement_shares'],'helpful'=>(int)$row['engagement_helpful'],'marked_helpful'=>$markedHelpful],'created_at'=>$row['created_at'],'expires_at'=>$row['expires_at'],'media'=>$this->media->forFlash((int)$row['id'])];
    }
}