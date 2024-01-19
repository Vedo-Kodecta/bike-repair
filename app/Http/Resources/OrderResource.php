<?php

namespace App\Http\Resources;

use App\Models\RepairStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'customer' => UserResource::make($this->whenLoaded('customer')),
                'mechanic' => UserResource::make($this->whenLoaded('mechanic')),
                'repairStatus' => RepairStatusResource::make($this->whenLoaded('repairStatus')),
            ];
    }
}
