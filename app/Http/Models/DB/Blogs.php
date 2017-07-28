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

    public function tags()
    {
        return $this->belongsToMany( Tags::class, 'blogs_tags', 'tag_id', 'tag_id' );
    }

    public function categories()
    {
        return $this->belongsTo( Categorys::class, 'b_cat_id' );
    }
}