<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{

	public function user() {
		return $this->belongsTo('App\User');
	}

	protected $dates = [
		'created_at',
		'updated_at',
		'start',
		'end',
	];
}
