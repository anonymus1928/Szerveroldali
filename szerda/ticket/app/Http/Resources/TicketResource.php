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
            'priority' => $this->priority,
            'done' => $this->done,
            'super_secret' => $this->when(Auth::user()->is_admin, 'super_super_super_secret'),
            'comments_count' => $this->comments()->count(),
            'comments_count2' => $this->whenCounted('comments'),
            'comments' => $this->whenLoaded('comments'),
            'users' => $this->whenLoaded('users'),
            'owner' => $this->whenLoaded('owner'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
