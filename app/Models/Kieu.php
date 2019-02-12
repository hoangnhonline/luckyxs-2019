<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Kieu extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'kieu';

	 /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['name'];
}