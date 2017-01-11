<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{

	/**
	 * @var array
	 */
	protected $dates = [
		'created_at', 'updated_at', 'date',
	];

	/**
	 * @var array
	 */
	protected $fillable = [
		'user_id', 'date', 'start', 'end',
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo('App\User');
	}

	/**
	 * @param string $value Time string in format H:i:s
	 *
	 * @return Carbon
	 */
	public function getStartAttribute($value)
	{
		return new Carbon($value);
	}

	/**
	 * @param string $value Time string in format H:i:s
	 *
	 * @return Carbon
	 */
	public function getEndAttribute($value)
	{
		return new Carbon($value);
	}
}
