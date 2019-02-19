<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Puzzle extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->image_full_url,
            'order' => $this->order,
            'created_at' => optional($this->created_at)->toDateString()
        ];
    }
}
