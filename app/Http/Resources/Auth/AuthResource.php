<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function App\Http\Helpers\image_url;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->image_url,
            'mobile' => $this->mobile,
            'type' => $this->type,
            'main_mails_number' => $this->firstCheckInForm ? $this->firstCheckInForm->main_mails_number : null,
            'days_number_for_exercise' => $this->firstCheckInForm ? $this->firstCheckInForm->days_number_for_exercise : null,
            'created_at' => $this->created_at,

        ];
    }
}
