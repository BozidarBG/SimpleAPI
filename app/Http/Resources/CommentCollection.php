<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CommentCollection extends Resource
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
            'user'=>$this->user->name,
            'content'=>$this->body,
            'date'=>$this->created_at->format('d.m.Y @ H:i:s')
        ];
    }
}
