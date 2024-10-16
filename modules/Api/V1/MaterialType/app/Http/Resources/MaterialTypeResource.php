<?php

namespace Modules\Api\V1\MaterialType\app\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @method getRawOriginal(string $string)
 */
class MaterialTypeResource extends JsonResource
{
    public static $wrap = 'materialType';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'food_id' => $this->food_id,
            'status' => $this->getRawOriginal('status'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
