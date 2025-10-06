<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    /**
     * 
     * Get a list of available tags.
     *
     * Returns all supported tags.  
     * Each tag contains its `id`, and `name`.
     * @authenticated
     * @response 200 [
     *   { "id": 1, "name": "English" },
     *   { "id": 2, "name": "French" },
     *   { "id": 3, "name": "Spanish" }
     * ]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke():JsonResponse{
        $tags = Tag::get(['id',"name"]);
        return response()->json($tags);
    }
}
