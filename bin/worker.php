<?php
declare(strict_types=1);
use App\Database\Connection;
use App\Services\FlashLifecycleService;
use App\Support\Env;
use App\Support\Logger;
require dirname(__DIR__) . '/vendor/autoload.php';
Env::load(dirname(__DIR__) . '/.env');
$once=in_array('--once',$argv,true);
do {
 $expired=(new FlashLifecycleService())->expireDue(500); Logger::info('worker.lifecycle',['expired'=>$expired]);
 $job=Connection::get()->query("SELECT id, job_type FROM jobs WHERE status='pending' AND available_at<=UTC_TIMESTAMP() ORDER BY id LIMIT 1")->fetch();
 if($job){$u=Connection::get()->prepare("UPDATE jobs SET status='completed',attempts=attempts+1,reserved_at=UTC_TIMESTAMP(),completed_at=UTC_TIMESTAMP(),updated_at=UTC_TIMESTAMP() WHERE id=:id AND status='pending'");$u->execute(['id'=>$job['id']]);if($u->rowCount()===1)Logger::info('worker.job.completed',['job_id'=>(int)$job['id'],'job_type'=>$job['job_type']]);}
 if($once)break;sleep(5);
}while(true);