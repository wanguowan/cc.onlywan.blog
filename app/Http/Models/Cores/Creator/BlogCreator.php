<?php
/**
 * Created by PhpStorm.
 * User: wan
 * Date: 7/27/17
 * Time: 6:44 PM
 */
namespace App\Http\Models\Cores\Creator;

use App\Http\Models\DB\Blogs;
use App\Http\Models\DB\Tags;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Naux\AutoCorrect;

class BlogCreator
{
    private $_error = 'error';

    public function create( Request $request )
    {
        $oBlog = new Blogs();

        $oBlog = $this->_transform( $oBlog, $request );
        if ( ! $oBlog )
        {
            // TODO 添加错误
            return null;
        }
        Tags::createBlogsTags( $oBlog->id, $request->tags );

        return $oBlog->id;
    }

    private function _transform( Blogs $oBlog, Request $request )
    {
        $oBlog->b_title = ( new AutoCorrect() )->convert( $request->title );
        $oBlog->b_flag = strtolower( $request->flag );
        $oBlog->b_thumb = $request->thumb;
        $oBlog->b_cat_id = $request->category_id;
        $oBlog->user_id = 1;
        $oBlog->b_content = ( new \Parsedown() )->text( $request->markdown );
        $oBlog->b_md = $request->markdown;
        $oBlog->b_ip = !empty($request->ipaddress) ? $request->ipaddress : $request->ip();

        try
        {
            $oBlog->save();
            return $oBlog;
        }
        catch ( QueryException $exception )
        {
            if ( $exception->errorInfo[ 1 ] == 1062 )
            {
                $this->_error = '文章插入失败，flag重复了。';
            }

            return null;
        }
    }
}