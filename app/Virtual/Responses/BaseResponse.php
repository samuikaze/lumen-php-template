<?php

namespace App\Virtual\Responses;

/**
 * 共通回應格式
 *
 * @OA\Schema(
 *   title="共通回應",
 *   description="請求回傳的共通酬載",
 *   type="object"
 * )
 */
class BaseResponse
{
    /**
     * HTTP 狀態碼
     *
     * @var int
     *
     * @OA\Property(
     *   description="HTTP 狀態碼",
     *   example="200"
     * )
     */
    public $status;

    /**
     * 錯誤訊息
     *
     * @var string|null
     *
     * @OA\Property(
     *   description="錯誤訊息",
     *   example="錯誤訊息"
     * )
     */
    public $message;

    /**
     * 回應資料
     *
     * @var object|array|null
     *
     * @OA\Proerty(
     *   description="回應資料",
     * )
     */
    public $data;
}
