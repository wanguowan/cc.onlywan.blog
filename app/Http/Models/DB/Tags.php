<?php
/**
 * Created by PhpStorm.
 * User: wan
 * Date: 7/27/17
 * Time: 5:08 PM
 */
namespace App\Http\Models\DB;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    public static function createBlogsTags( $nBlogID, $arrTags )
    {
        $arrBlogTags = [];
        foreach ( $arrTags as $k => $tag )
        {
            $tagsInfo = self::firstOrCreate( [ 'tag_name' => $tag ] );
            $tagsInfo->tag_name = $tag;
            $tagsInfo->tag_flag = urlencode( $tag );
            $tagsInfo->save();
            $arrBlogTags[ $k ] = [
                'blog_id'       => $nBlogID,
                'tag_id'        => $tagsInfo->id,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ];
        }
        BlogsTags::where( 'blog_id', $nBlogID )->delete();
        DB::table( 'blogs_tags' )->insert( $arrBlogTags );
    }

    public function posts()
    {
        return $this->belongsToMany( Blogs::class );
    }
}