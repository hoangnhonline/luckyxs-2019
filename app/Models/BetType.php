<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class BetType extends Model  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'bet_type';

	 /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['keyword', 'content', 'example','display_order'];
}