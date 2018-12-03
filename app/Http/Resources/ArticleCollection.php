<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;


class ArticleCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
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
            'image'=>isset($this->image) ? asset($this->folder.$this->image) : null,
            'tags'=>$this->tags->pluck('title'),
            'comments_count'=>$this->comments->count(),
            'href'=>[route('articles.show', $this->id)]
        ];

    }
}
