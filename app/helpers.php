<?php

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

function errorResponse($code, $message = 'Error'): JsonResponse
{

    return response()->json([

        'status' => 'Error',

        'message' => $message,

        'data' => ""

    ], $code);

};

function successResponse($data,$code,$message = null): JsonResponse
{

    return response()->json([

        'status' => 'Success',

        'message' => $message,

        'data' => $data

    ], $code);

}

function generateFileName($name): string
{
    $year = Carbon::now()->year;
    $month = Carbon::now()->month;
    $day = Carbon::now()->day;
    $hour = Carbon::now()->hour;
    $minute = Carbon::now()->minute;
    $second = Carbon::now()->second;
    $microsecond = Carbon::now()->microsecond;
    return $year .'_'. $month .'_'. $day .'_'. $hour .'_'. $minute .'_'. $second .'_'. $microsecond .'_'. strtolower($name);
}
