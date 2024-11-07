<?php

namespace Modules\Api\V1\UserFood\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UserFood;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Api\V1\UserFood\app\Http\Resources\UserFoodResource;

class UserFoodController extends Controller
{
    public function index(): JsonResponse
    {
        $userFoods = UserFood::latest()->paginate(10);
        return successResponse([
            'data' => UserFoodResource::collection($userFoods),
            'links' => UserFoodResource::collection($userFoods)->response()->getData()->links,
            'meta' => UserFoodResource::collection($userFoods)->response()->getData()->meta
        ], 200, 'همه غذا ها با موفقیت دریافت شد.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'user_id' => 'required|integer',
            'food_id' => 'required|integer',
            'price' => 'required|integer',
            'status' => 'required|integer',
            'show_status' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return errorResponse(422, $validator->messages());
        }

        try {
            DB::beginTransaction();

            $userFood = UserFood::create([
                'name' => $request->name,
                'user_id' => $request->user_id,
                'food_id' => $request->food_id,
                'price' => $request->price,
                'show_status' => $request->show_status,
                'status' => $request->status
            ]);

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            return errorResponse(422, $ex->getMessage());
        }

        return successResponse(new UserFoodResource($userFood), 200, 'غذای جدید با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserFood $userFood): JsonResponse
    {
        return successResponse(new UserFoodResource($userFood), 200, 'غذا با موفقیت دریافت شد.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserFood $userFood): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'user_id' => 'required|integer',
            'food_id' => 'required|integer',
            'price' => 'required|integer',
            'status' => 'required|integer',
            'show_status' => 'required|boolean'
        ]);

        if($validator->fails()){
            return errorResponse(422, $validator->messages());
        }

        try {
            DB::beginTransaction();

            $userFood->update([
                'name' => $request->name,
                'user_id' => $request->user_id,
                'food_id' => $request->food_id,
                'price' => $request->price,
                'show_status' => $request->show_status,
                'status' => $request->status
            ]);

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            return errorResponse(422, $ex->getMessage());
        }

        return successResponse(new UserFoodResource($userFood), 200, 'غذای مورد نظر با موفقیت ویرایش شد!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserFood $userFood): JsonResponse
    {
        $userFood->delete();

        return successResponse(new UserFoodResource($userFood), 200, 'غذای مورد نظر با موفقیت حذف شد!');
    }

}
