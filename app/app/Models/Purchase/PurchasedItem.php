<?php

namespace App\Models\Purchase;

use App\Http\Controllers\logController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchasedItem extends Model {
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_purchased_items';
    protected $primaryKey = 'piid';

    protected $fillable = [
        'piid',
        'purchase_id',
        'name',
        'description',
        'amount',
        'supplier_id',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];


    protected static function boot(): void {
        parent::boot();

        // create Logs
        // static::creating(function (PurchasedItem $purchasedItem): void {
        //     // Getting the Module nam
        //     $moduleName = ucfirst(explode('tbl_', self::$table)[1]); // the Module is returned by the table name, nad expected to be proper as Document Number
        //     $userId = Auth::user()->UserID; // user id
        //     // creating log data
        //     $logData = array(
        //         "Description" => "Transaction made to " . $moduleName,
        //         "ModuleName" => $moduleName,
        //         "Action" => "create",
        //         "ReferID" => $purchasedItem[self::$primaryKey],
        //         "OldData" => "",
        //         "NewData" => serialize($purchasedItem),
        //         "UserID" => $userId,
        //         "IP" => $purchasedItem->ip(),
        //     ); // start database transactions
        //     DB::transaction(function () use ($logData) {
        //         (new logController())->Store($logData); // storing the log data
        //         DB::commit();
        //     });
        // });
    }
}