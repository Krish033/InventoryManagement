<?php

namespace App\Listeners;

use App\Events\LogForStoredEvent;
use App\Http\Controllers\logController;
use App\Models\DocNum;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogCreateListener implements ShouldQueue {

    use InteractsWithQueue;

    public $afterCommit = true;
    /**
     * Create the event listener.
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LogForStoredEvent $event): void {

        // Getting the Module nam
        $moduleName = ucfirst(explode('tbl_', $event->table)[1]); // the Module is returned by the table name, nad expected to be proper as Document Number
        $userId = Auth::user()->UserID; // user id
        // creating log data
        $logData = array(
            "Description" => "Transaction made to " . $moduleName,
            "ModuleName" => $moduleName,
            "Action" => "create",
            "ReferID" => $event->logRecordCreated[$event->primaryKey],
            "OldData" => "",
            "NewData" => serialize($event->logRecordCreated),
            "UserID" => $userId,
            "IP" => $event->ipaddress
        ); // start database transactions
        DB::transaction(function () use ($logData, $event) {
            (new logController())->Store($logData); // storing the log data
            DB::table($event->table)->insert($event->logRecordCreated); // creating raw data
            DB::commit();
        });
    }
}