<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function allPosts(Request $request)
    {

        $columns = [
            0 => 'id',
            1 => 'title',
            2 => 'body',
            3 => 'created_at',
            4 => 'id',
        ];

        $totalData = Post::query()->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length'); //Number of records that the table can display in the current draw.
        $start = $request->input('start');//Paging first record indicator.  This is the start point in the current data set (0 index based - i.e. 0 is the first record)
        $order = $columns[$request->input('order.0.column')]; //Column to which ordering should be applied. This is an index reference to the columns array of information that is also submitted to the server.
        $dir = $request->input('order.0.dir'); // Ordering direction for this column. It will be asc or desc to indicate ascending ordering or descending ordering, respectively.

        if (empty($request->input('search.value'))) {
            $posts = Post::query()->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value'); //The Global search value.

            $posts = Post::where('id', 'LIKE', "%{$search}%")
                ->orWhere(function (Builder $query) use ($search) {
                    $query->where('title', 'LIKE', "%{$search}%")
                        ->orWhere(function (Builder $query) use ($search) {
                            $query->where('body', 'LIKE', "%{$search}%")
                                ->orWhere(DB::raw("(DATE_FORMAT(created_at,\"%d %M %Y %h:%i %a\"))"), "LIKE", "%{$search}%");
                            return $query;
                        });
                    return $query;
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = Post::where('id', 'LIKE', "%{$search}%")
                ->orWhere(function (Builder $query) use ($search) {
                    $query->where('title', 'LIKE', "%{$search}%")
                        ->orWhere(function (Builder $query) use ($search) {
                            $query->where('body', 'LIKE', "%{$search}%")
                                ->orWhere(DB::raw("(DATE_FORMAT(created_at,\"%d %M %Y %h:%i %a\"))"), "LIKE", "%{$search}%");
                            return $query;
                        });
                    return $query;
                })
                ->count();
        }

        $data = [];
        if (!empty($posts)) {
            foreach ($posts as $post) {
//                $show =  route('posts.show',$post->id);
//                $edit =  route('posts.edit',$post->id);
                $show = '/';
                $edit = '/';
                $nestedData['id'] = $post->id;
                $nestedData['title'] = $post->title;
                $nestedData['body'] = substr(strip_tags($post->body), 0, 50) . "...";
                $nestedData['created_at'] = date('j M Y h:i a', strtotime($post->created_at));
                $nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
                                          &emsp;<a href='{$edit}' title='EDIT' ><span class='glyphicon glyphicon-edit'></span></a>";
                $data[] = $nestedData;

            }
        }

        $json_data = [
            "draw" => intval($request->input('draw')), //: Draw counter. This is used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        ];

        return json_encode($json_data);

    }
}
