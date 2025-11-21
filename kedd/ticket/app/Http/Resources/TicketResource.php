<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TicketResource extends JsonResource
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
            'title' => $this->title,
            'done' => $this->done,
            'priority' => $this->priority,
            'comments_count' => $this->comments()->count(),
            'comments_count2' => $this->whenCounted('comments'),
            'comments' => $this->whenLoaded('comments'),
            'users' => $this->whenLoaded('users'),
            'super_secret' => $this->when(Auth::user()->admin, 'super_secret_message'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
