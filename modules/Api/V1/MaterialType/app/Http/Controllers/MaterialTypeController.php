<?php

namespace Modules\Api\V1\MaterialType\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FoodType;
use App\Models\MaterialType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Api\V1\MaterialType\app\Http\Resources\MaterialTypeResource;

class MaterialTypeController extends Controller
{
    public function index(): JsonResponse
    {
        $materialTypes = MaterialType::latest()->paginate(10);
        return successResponse([
            'data' => MaterialTypeResource::collection($materialTypes),
            'links' => MaterialTypeResource::collection($materialTypes)->response()->getData()->links,
            'meta' => MaterialTypeResource::collection($materialTypes)->response()->getData()->meta
        ], 200, 'انواع متریال با موفقیت دریافت شد.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'food_id' => 'required|integer',
            'step' => 'required|integer',
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return errorResponse(422, $validator->messages());
        }

        try {
            DB::beginTransaction();

            $materialType = MaterialType::create([
                'name' => $request->name,
                'food_id' => $request->food_id,
                'step' => $request->step,
                'status' => $request->status
            ]);

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            return errorResponse(422, $ex->getMessage());
        }

        return successResponse(new MaterialTypeResource($materialType), 200, 'متریال جدید با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MaterialType $materialType): JsonResponse
    {
        return successResponse(new MaterialTypeResource($materialType), 200, 'متریال با موفقیت دریافت شد.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaterialType $materialType): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'food_id' => 'required|integer',
            'step' => 'required|integer',
            'status' => 'required|boolean'
        ]);

        if($validator->fails()){
            return errorResponse(422, $validator->messages());
        }

        try {
            DB::beginTransaction();

            $materialType->update([
                'name' => $request->name,
                'food_id' => $request->food_id,
                'step' => $request->step,
                'status' => $request->status
            ]);

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            return errorResponse(422, $ex->getMessage());
        }

        return successResponse(new MaterialTypeResource($materialType), 200, 'متریال مورد نظر مورد نظر با موفقیت ویرایش شد!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaterialType $materialType): JsonResponse
    {
        $materialType->delete();

        return successResponse(new MaterialTypeResource($materialType), 200, 'متریال مورد نظر با موفقیت حذف شد!');
    }

}
