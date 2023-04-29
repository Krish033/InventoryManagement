<?php

namespace App\Listeners;

use App\Events\LogForDeletedEvents;
use App\Http\Controllers\logController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LodDeletedListener {
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
        // Getting the Module nam
        $moduleName = ucfirst(explode('tbl_', $event->table)[1]); // the Module is returned by the table name, nad expected to be proper as Document Number

        $userId = Auth::user()->UserID; // user id
        $logData = array(
            "Description" => "Transaction made to " . $moduleName,
            "ModuleName" => $moduleName,
            "Action" => "delete",
            "ReferID" => $event->logRecordDeleted[$event->primaryKey],
            "OldData" => serialize(DB::table($event->table)
                ->where($event->primaryKey, '=', $event->logRecordDeleted[$event->primaryKey])
                ->first()),
            "NewData" => serialize($event->logRecordDeleted),
            "UserID" => $userId,
            "IP" => $event->ipaddress
        );

        // start database transactions
        DB::transaction(function () use ($logData, $event) {
            (new logController())->Store($logData); // storing the log data
            DB::table($event->table)
                ->where($event->primaryKey, '=', $event->logRecordDeleted[$event->primaryKey])
                ->update(['dflag' => 1]); // creating raw data
            DB::commit();
        });
    }
}