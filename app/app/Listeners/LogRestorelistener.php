<?php

namespace App\Listeners;

use App\Http\Controllers\logController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogRestorelistener {
    /**
     * Create the event listener.
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event): void {
        //logRecordRestore
        $moduleName = ucfirst(explode('tbl_', $event->table)[1]); // the Module is returned by the table name, nad expected to be proper as Document Number
        $userId = Auth::user()->UserID; // user id

        $logData = array(
            "Description" => "Transaction made to " . $moduleName,
            "ModuleName" => $moduleName,
            "Action" => "restore",
            "ReferID" => $event->logRecordRestore[$event->primaryKey],
            "OldData" => serialize(DB::table($event->table)
                ->where($event->primaryKey, '=', $event->logRecordRestore[$event->primaryKey])
                ->where('dflag', '=', 1)
                ->first()),
            "NewData" => serialize($event->logRecordRestore),
            "UserID" => $userId,
            "IP" => '::1'
        ); // start database transactions

        DB::transaction(function () use ($logData, $event) {
            (new logController())->Store($logData); // storing the log data
            DB::table($event->table)
                ->where($event->primaryKey, '=', $event->logRecordRestore[$event->primaryKey])
                ->where('dflag', '=', 1)
                ->update(['dflag' => 0]); // creating raw data
            DB::commit();
        });
    }
}