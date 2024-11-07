<?php

namespace Modules\Api\V1\Material\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Api\V1\Material\app\Http\Resources\MaterialResource;

class MaterialController extends Controller
{
    public function index(): JsonResponse
    {
        $materials = Material::latest()->paginate(10);
        return successResponse([
            'data' => Material::collection($materials),
            'links' => Material::collection($materials)->response()->getData()->links,
            'meta' => Material::collection($materials)->response()->getData()->meta
        ], 200, 'متریال ها با موفقیت دریافت شد.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'type_id' => 'required|integer',
            'price' => 'required|integer',
            'calorie' => 'required|integer',
            'status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return errorResponse(422, $validator->messages());
        }

        try {
            DB::beginTransaction();

            $material = Material::create([
                'name' => $request->name,
                'type_id' => $request->type_id,
                'price' => $request->price,
                'calorie' => $request->calorie,
                'status' => $request->status
            ]);

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            return errorResponse(422, $ex->getMessage());
        }

        return successResponse(new MaterialResource($material), 200, 'متریال جدید با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material): JsonResponse
    {
        return successResponse(new MaterialResource($material), 200, 'متریال با موفقیت دریافت شد.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'type_id' => 'required|integer',
            'price' => 'required|integer',
            'calorie' => 'required|integer',
            'status' => 'required|boolean'
        ]);

        if($validator->fails()){
            return errorResponse(422, $validator->messages());
        }

        try {
            DB::beginTransaction();

            $material->update([
                'name' => $request->name,
                'type_id' => $request->type_id,
                'price' => $request->price,
                'calorie' => $request->calorie,
                'status' => $request->status
            ]);

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            return errorResponse(422, $ex->getMessage());
        }

        return successResponse(new MaterialResource($material), 200, 'متریال مورد نظر مورد نظر با موفقیت ویرایش شد!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material): JsonResponse
    {
        $material->delete();

        return successResponse(new MaterialResource($material), 200, 'متریال مورد نظر با موفقیت حذف شد!');
    }

}
