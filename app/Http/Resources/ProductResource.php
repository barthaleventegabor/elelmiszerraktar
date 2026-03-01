<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'description' => $this->description,
            'category' => $this->category?->makeHidden('id', 'created_at', 'updated_at'),
            'supplier' => $this->supplier?->makeHidden('id', 'created_at', 'updated_at'),
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'expiration_date' => $this->expiration_date,
        ];
    }
}
