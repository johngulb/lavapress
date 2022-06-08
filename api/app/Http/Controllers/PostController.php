<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function get()
    {
        $post = \Corcel\Model\Post::find(1);
        $resource = new \App\Http\Resources\PostResource($post);
        return $resource;
    }
}
