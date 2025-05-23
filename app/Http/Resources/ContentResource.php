<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'content_id' => $this->content_id,
            'type'=>$this->type,
            'description' =>$this->description,
            'caption' =>$this->caption,
            'order'=>$this->order,
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'created_at' => (new Carbon($this->created_at))->format('Y-m-d'),
            'updated_at' => (new Carbon(time: $this->updated_at))->format('Y-m-d'),
        ];
    }
}
