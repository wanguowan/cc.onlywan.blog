<?php
/**
 * Created by PhpStorm.
 * User: wan
 * Date: 7/27/17
 * Time: 6:40 PM
 */
namespace App\Http\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    protected $dates = [ 'deleted_at' ];
}