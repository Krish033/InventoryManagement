<?php

namespace App\Listeners;

use App\Http\Controllers\logController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogUpdatedListener implements ShouldQueue {

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
    public function handle($event): void {
        // Getting the Module nam
        $moduleName = ucfirst(explode('tbl_', $event->table)[1]); // the Module is returned by the table name, nad expected to be proper as Document Number
        $userId = Auth::user()->UserID; // user id

        // creating log data
        $logData = array(
            "Description" => "Transaction made to " . $moduleName,
            "ModuleName" => $moduleName,
            "Action" => "update",
            "ReferID" => $event->updatingItem[$event->primaryKey],
            "OldData" => serialize($event->updatingItem),
            "NewData" => serialize($event->updatedData),
            "UserID" => $userId,
            "IP" => '::1'
        );

        // start database transactions
        DB::transaction(function () use ($logData, $event) {

            (new logController())->Store($logData); // storing the log data

            DB::table($event->table)->where($event->primaryKey, '=', $event->id)->update($event->updatedData); // creating raw data
            DB::commit();
        });
    }

    public function failed($event, \Throwable $th) {
        abort(500, $th);
    }
}