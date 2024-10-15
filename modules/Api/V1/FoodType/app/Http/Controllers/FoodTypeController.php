<?php

namespace Modules\Api\V1\FoodType\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FoodType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Api\V1\FoodType\app\Http\Resources\FoodTypeResource;

class FoodTypeController extends Controller
{
    public function index(): JsonResponse
    {
        $foodTypes = FoodType::latest()->paginate(10);
        return successResponse([
            'data' => FoodTypeResource::collection($foodTypes),
            'links' => FoodTypeResource::collection($foodTypes)->response()->getData()->links,
            'meta' => FoodTypeResource::collection($foodTypes)->response()->getData()->meta
        ], 200, 'انواع با موفقیت دریافت شد.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'baking_time' => 'required|integer',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return errorResponse(422, $validator->messages());
        }

        try {
            DB::beginTransaction();

            $imageName = generateFileName($request->image->getClientOriginalName());
            $request->image->move(storage_path(env('FOODTYPES_IMAGE_UPLOAD_PATH')), $imageName);

            $foodType = FoodType::create([
                'name' => $request->name,
                'baking_time' => $request->baking_time,
                'status' => $request->status,
                'image' => $imageName
            ]);

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            return errorResponse(422, $ex->getMessage());
        }

        return successResponse(new FoodTypeResource($foodType), 200, 'نوع غذای جدید با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodType $foodType): JsonResponse
    {
        return successResponse(new FoodTypeResource($foodType), 200, 'نوع غذا با موفقیت دریافت شد.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FoodType $foodType): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'baking_time' => 'required|integer',
            'status' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg'
        ]);

        if($validator->fails()){
            return errorResponse(422, $validator->messages());
        }

        try {
            DB::beginTransaction();

            if ($request->image) {
                $imageName = generateFileName($request->image->getClientOriginalName());
                $request->image->move(storage_path(env('FOODTYPES_IMAGE_UPLOAD_PATH')), $imageName);
                $foodType->update([
                    'image' => $imageName
                ]);
            }

            $foodType->update([
                'name' => $request->name,
                'baking_time' => $request->baking_time,
                'status' => $request->status
            ]);

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            return errorResponse(422, $ex->getMessage());
        }

        return successResponse(new FoodTypeResource($foodType), 200, 'نوع غذای مورد نظر مورد نظر با موفقیت ویرایش شد!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodType $foodType): JsonResponse
    {
        $foodType->delete();

        return successResponse(new FoodTypeResource($foodType), 200, 'نوع غذای مورد نظر با موفقیت حذف شد!');
    }

}
