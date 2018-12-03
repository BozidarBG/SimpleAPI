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
            'tags'=>$this->tags->pluck('title'),
            'date'=>$this->created_at->format('d.m.Y @ H:i:s'),
            'comments'=>CommentCollection::collection($this->comments),
            'image'=>isset($this->image) ? asset($this->folder.$this->image) : null,
        ];
    }
}
