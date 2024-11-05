<?php

namespace App\Http\Resources;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    private bool $withLikePermission = false;

    public function withLikePermission(): self
    {
        $this->withLikePermission = true;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->whenLoaded('user', fn() => $this->user->name),
            ],
            'review' => $this->review,
            'rating' => $this->rating,
            'likes_count' => $this->likes_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'can' => [
                'update' => $request->user()?->can('update', $this->resource),
                'delete' => $request->user()?->can('delete', $this->resource),
                'like' => $this->when($this->withLikePermission, fn () => $request->user()?->can('create', [Like::class, $this->resource])),
            ],
            'canBeEditedOrDeleted' => $this->canBeEditedOrDeleted(),
        ];
    }
}
