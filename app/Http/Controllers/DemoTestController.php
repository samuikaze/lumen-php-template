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
class DemoTestController extends Controller
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
     * 心跳
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   path="/test",
     *   summary="心跳",
     *   tags={"Example v1"},
     *   @OA\Response(
     *     response="200",
     *     description="心跳回應",
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
    public function heartbeat(): JsonResponse
    {
        return $this->response(data: 'Ok.');
    }
}
