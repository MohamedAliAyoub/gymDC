<?php

namespace App\Http\Resources\Dashboard\CheckIn;

use Illuminate\Http\Resources\Json\JsonResource;

class BodyImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image' => $this->image_url,
        ];
    }
}
