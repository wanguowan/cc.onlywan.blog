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
//            'metaWeblog.newPost' => 'newPost',
//            'metaWeblog.editPost' => 'editPost',
//            'metaWeblog.getPost' => 'getPost',
            'metaWeblog.getCategories' => '_getCategories',
//            'metaWeblog.newMediaObject' => 'newMediaObject',
//            'metaWeblog.getRecentPosts' => 'getRecentPosts',
//            'wp.newCategory' => 'newCategory',
        );

        $sMethod = null;
        $sReqContent = $request->getContent();
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

    public function newPost($method, $arrParams )
    {
        list( $nBlogID, $sUserName, $sPassword, $arrStructure, $sPublish ) = $arrParams;
        Log::info( $arrStructure );
        $request = $this->_transform( $arrStructure );
//        app(\Persimmon\Creator\PostsCreator::class)->create($this, $request);
    }

    private function _editPost( $sMethod, $sParams )
    {
        list( $nPostId, $sUserName, $sPassword, $sStructure, $publish ) = $sParams;
        $request = $this->_transform( $sStructure );

        Log::info( $request );
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
        $category = Categorys::where('cat_name', $arrStructure['$arrStructure'][0])->select('id')->first();
        $request = new Request();
        $request->title = $arrStructure['title'];
        $request->flag = $arrStructure['wp_slug'];
        $request->thumb = '';
        $request->tags = is_array( $arrStructure[ 'mt_keywords' ] ) ? $arrStructure[ 'mt_keywords' ] : $tags;
        $request->category_id = $category->id;
        $request->category_id = 1;
//        $request->user_id = Auth::id();
        $request->user_id = 1;
        $request->markdown = $arrStructure[ 'description' ];
        $request->ipaddress = !empty($this->client_id) ? $this->client_id : '127.0.0.1';

        return $request;
    }

}