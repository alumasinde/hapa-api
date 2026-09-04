<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Database\Connection;
use App\Support\Env;
use App\Support\Response;

final class SystemController
{
    public function health(): never
    {
        $checks=['database'=>'down'];
        try { Connection::get()->query('SELECT 1')->fetchColumn(); $checks['database']='up'; } catch (\Throwable) {}
        $status=$checks['database']==='up'?'ok':'degraded';
        Response::json(['status'=>$status,'environment'=>(string)Env::get('APP_ENV','local'),'checks'=>$checks,'timestamp'=>gmdate('c')],$status==='ok'?200:503);
    }
}