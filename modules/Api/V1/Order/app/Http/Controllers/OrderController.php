<?php

namespace Modules\Api\V1\Order\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FoodType;
use App\Models\Material;
use App\Models\MaterialType;
use Illuminate\Http\JsonResponse;
use Modules\Api\V1\Material\app\Http\Resources\MaterialResource;

class OrderController extends Controller
{
    public function provideMaterials(FoodType $foodType, $step): JsonResponse
    {
        $materialType = MaterialType::active()->where('food_id', $foodType->id)->where('step', $step)->first();
        $materials = Material::where('type_id', $materialType->id)->get();
        return successResponse(MaterialResource::collection($materials), 200, "متریال مرحله ارسال شد.");
    }

}
