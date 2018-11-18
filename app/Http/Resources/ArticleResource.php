<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'author'=>$this->user->name,
            'title'=>$this->title,
            'content'=>$this->body,
            'tags'=>TagCollection::collection($this->tags),
            'date'=>$this->created_at,
            'comments'=>CommentCollection::collection($this->comments),
        ];
    }
}
