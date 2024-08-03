<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionLogsResource  extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'log' => $this->client->name .' '.$this->log,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
