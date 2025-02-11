<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentResource extends JsonResource
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
            'text' => $this->text,
            'filename' => $this->filename,
            'filename_hash' => $this->filename_hash,
            'file_download_url' => $this->when(isset($this->filename_hash), Storage::url($this->filename_hash)),
            // 'ticket' => new TicketResource($this->whenLoaded('ticket')),
            'ticket' => new TicketResource($this->ticket),
            // 'user' => new UserResource($this->whenLoaded('user')),
            'user' => new UserResource($this->user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
