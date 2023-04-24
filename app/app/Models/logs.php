<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class logs extends Model {
	use HasFactory;
	protected $table = 'tbl_log';
	protected $primaryKey = 'LogID';
	public $incrementing = false;
	public $timestamps = false;
	protected $attributes = [];
	protected $fillable = [
		'LogID',
		'Description',
		'ModuleName',
		'Action',
		'OldData',
		'NewData',
		'IPAddress',
		'UserID',
		'LogTime'
	];
}