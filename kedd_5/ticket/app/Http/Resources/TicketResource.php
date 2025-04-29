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
            'owner' => $this->whenLoaded('owner'),
            'users' => $this->whenLoaded('users'),
            'comments' => $this->whenLoaded('comments'),
            'comments_count' => $this->whenCounted('comments'),
            'comments_count2' => $this->comments->count(),
            'super_secret' => $this->when(Auth::user()->admin, 'supersupersecret'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
