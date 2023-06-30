<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use HttpResponses;

    public function getTagList()
    {
        $tags = Tag::select('name')->get();
        return $this->success(TagResource::collection($tags), 'Tag List');
    }
}
