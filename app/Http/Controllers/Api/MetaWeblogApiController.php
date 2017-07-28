<?php
/**
 * Created by PhpStorm.
 * User: wan
 * Date: 7/27/17
 * Time: 12:29 PM
 */
namespace App\Http\Controllers\Api;

use App\Http\CLibs\CXmlRpcLib;
use App\Http\Controllers\Controller;
use App\Http\Models\Cores\Creator\BlogCreator;
use App\Http\Models\DB\Blogs;
use App\Http\Models\DB\Categorys;
use Illuminate\Http\Request;
use Log;

class MetaWeblogApiController extends Controller
{
    public function index( Request $request )
    {
        $arrMethods = array(
            'blogger.getUsersBlogs' => '_getUserBlogList',
//            'blogger.deletePost' => 'deletePost',
            'metaWeblog.newPost' => '_newPost',
//            'metaWeblog.editPost' => 'editPost',
            'metaWeblog.getPost' => '_getPost',
            'metaWeblog.getCategories' => '_getCategories',
//            'metaWeblog.newMediaObject' => 'newMediaObject',
//            'metaWeblog.getRecentPosts' => 'getRecentPosts',
//            'wp.newCategory' => 'newCategory',
        );

        $sMethod = null;
        $sReqContent = $request->getContent();
        Log::info( $sReqContent );
        $arrResponse = xmlrpc_decode_request( $sReqContent, $sMethod );
        if ( array_key_exists( $sMethod, $arrMethods ) )
        {
            call_user_func_array( [ $this, $arrMethods[ $sMethod ] ], [ $sMethod, $arrResponse ] );
        }
        else
        {
            $this->_methodNotFound( $sMethod );
        }
    }

    private function _getUserBlogList( $sMethod, $sParams )
    {
        $arrResponse[ 0 ] = [
            'url' => url( '/' ),
            'blogid' => '1',
            'blogName' => 'wan'
        ];

        CXmlRpcLib::response( $arrResponse );
    }

    private function _newPost($method, $arrParams )
    {
        list( $nBlogID, $sUserName, $sPassword, $arrStructure, $sPublish ) = $arrParams;
        $request = $this->_transform( $arrStructure );

        $oBlogCreator = new BlogCreator();
        $nID = $oBlogCreator->create( $request );

        if ( $nID )
        {
            CXmlRpcLib::response( $nID );
        }
        else
        {
            $response = [
                'faultCode' => '2',
                'faultString' => '创建失败',
            ];
            CXmlRpcLib::response( $response, 'error' );
        }
    }

    /**
     * Get Post
     * @param $method
     * @param $params
     */
    private function _getPost( $sMethod, $arrParams )
    {
        list( $nPostID, $sUserName, $sPassword ) = $arrParams;
        $arrBlog = [];
        $oBlog = Blogs::where('id', $nPostID)->select('id', 'id as postid', 'b_title as title', 'b_cat_id as category_id',
            'b_cat_id', 'b_md as description', 'user_id as userid', 'b_flag as wp_slug', 'created_at as dateCreated')->first();
        $arrBlog = $oBlog->toArray();
        $arrTags = $oBlog->tags->toArray();
        $arrBlog[ 'categories' ] = $oBlog->categories->cat_name;
//        $arrBlog[ 'link' ] = route( 'posts', [ $oBlog->wp_slug ] );
        $arrTagNames = array_map( function ( $item ) {
            return $item[ 'tags_name' ];
        }, $arrTags );
        $arrBlog[ 'mt_keywords' ] = implode( ',', $arrTagNames );

        //  该字段返回后，mweb会崩溃，后面处理
        unset( $arrBlog[ 'categories' ] );

        CXmlRpcLib::response( $arrBlog );
    }

    private function _editPost( $sMethod, $arrParams )
    {
        list( $nPostId, $sUserName, $sPassword, $arrStructure, $publish ) = $arrParams;
        $request = $this->_transform( $arrStructure );
    }

    private function _getCategories( $sMethod, $sParams )
    {
        $arrCategory = Categorys::all([ 'id as categoryid', 'cat_name as title', 'cat_desc as description', 'cat_flag as slug' ] )
            ->toArray();

        CXmlRpcLib::response( $arrCategory );
    }

    private function _methodNotFound( $sMethodName )
    {
        $arrResponse = [
            'faultCode' => '2',
            'faultString' => 'The method you requested ' . $sMethodName . ' was not found.'
        ];

        CXmlRpcLib::response( $arrResponse, 'error' );
    }

    /**
     * transform data
     * @param $struct
     * @return Request
     */
    private function _transform( $arrStructure )
    {
        $tags = strpos( $arrStructure[ 'mt_keywords' ], ',') !== false ? explode(',', $arrStructure[ 'mt_keywords' ] ) : $arrStructure['mt_keywords'];
        $category = Categorys::where('cat_name', $arrStructure['categories'][0])->select('id')->first();
        $request = new Request();
        $request->title = $arrStructure['title'];
        $request->flag = $arrStructure['wp_slug'];
        $request->thumb = '';
        $request->tags = is_array( $arrStructure[ 'mt_keywords' ] ) ? $arrStructure[ 'mt_keywords' ] : $tags;
        $request->category_id = $category->id;
//        $request->user_id = Auth::id();
        $request->user_id = 1;
        $request->markdown = $arrStructure[ 'description' ];
        $request->ipaddress = !empty($this->client_id) ? $this->client_id : '127.0.0.1';

        return $request;
    }

}