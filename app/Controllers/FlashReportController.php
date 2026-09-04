<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\RequestContext;
use App\Repository\AbuseRepository;
use App\Repository\FlashReportRepository;
use App\Repository\FlashRepository;
use App\Security\RateLimiter;
use App\Services\FlashIntelligenceService;
use App\Support\Request;
use App\Support\Response;

final class FlashReportController
{
    private const REASONS=['spam','misleading','duplicate','offensive','privacy','other'];
    public function __construct(
        private readonly FlashRepository $flashes=new FlashRepository(),
        private readonly FlashReportRepository $reports=new FlashReportRepository(),
        private readonly RateLimiter $limits=new RateLimiter(),
        private readonly AbuseRepository $abuse=new AbuseRepository(),
        private readonly FlashIntelligenceService $intelligence=new FlashIntelligenceService(),
    ) {}

    public function create(string $id): never
    {
        $userId=RequestContext::userId(); $flashId=$this->id($id); $data=Request::json();
        $reason=trim((string)($data['reason']??'')); $description=trim((string)($data['description']??''));
        if(!in_array($reason,self::REASONS,true)) Response::error('VALIDATION_ERROR','Report reason is invalid',422,['reason'=>'Choose a valid report reason']);
        if(mb_strlen($description)>500) Response::error('VALIDATION_ERROR','Report description is too long',422,['description'=>'Maximum length is 500 characters']);
        $flash=$this->flashes->find($flashId,null); if(!$flash) Response::error('NOT_FOUND','Flash not found',404);
        if($flash['reporter']['id']===$userId){$this->abuse->record($userId,'report_own_flash',1,'flash',$flashId);Response::error('FORBIDDEN','You cannot report your own flash',403);}
        if($this->reports->exists($flashId,$userId)){ $this->abuse->record($userId,'duplicate_report_attempt',1,'flash',$flashId); Response::error('CONFLICT','You have already reported this flash',409); }
        if(!$this->limits->allow('flash:report',(string)$userId,10,3600)){ $this->abuse->record($userId,'report_rate_limit',2,'flash',$flashId); Response::error('RATE_LIMITED','Too many reports',429); }
        try{$report=$this->reports->create($flashId,$userId,$reason,$description!==''?$description:null);$this->intelligence->evaluate($flashId);Response::json($report,201);}
        catch(\DomainException){Response::error('CONFLICT','You have already reported this flash',409);}
    }
    private function id(string $value): int {if(!ctype_digit($value)||(int)$value<1)Response::error('VALIDATION_ERROR','Resource id is invalid',422);return(int)$value;}
}