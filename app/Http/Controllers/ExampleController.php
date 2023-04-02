<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * 範例 Controller
 *
 * @OA\Tag(
 *   name="Example v1",
 *   description="範例相關"
 * )
 */
class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 測試
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   path="/test",
     *   summary="測試",
     *   tags={"Example v1"},
     *   @OA\Response(
     *     response="200",
     *     description="測試回應",
     *     @OA\JsonContent(
     *       allOf={
     *         @OA\Schema(ref="#/components/schemas/BaseResponse"),
     *         @OA\Schema(
     *           @OA\Property(
     *             property="data",
     *             type="string",
     *             example="Ok."
     *           )
     *         )
     *       }
     *     )
     *   )
     * )
     */
    public function test(): JsonResponse
    {
        return $this->response(data: 'Ok.');
    }
}
