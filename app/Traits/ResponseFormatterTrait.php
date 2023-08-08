<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;

trait ResponseFormatterTrait
{
    /**
     * 格式化返回的資料
     *
     * @param \Illuminate\Support\MessageBag|\Illuminate\Support\Collection|array|string|null $error 錯誤訊息
     * @param mixed $data 資料
     * @param int $status [200] 狀態碼
     * @param array<string,string> $headers [] 標頭
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($error = null, $data = null, int $status = 200, array $headers = []): JsonResponse
    {
        if ($error instanceof MessageBag) {
            $error = $this->processMessageBag($error);
        }

        if ($error instanceof Collection) {
            $error = $error->toArray();
        }

        if (is_array($error)) {
            $error = implode("、", $error);
        }

        $response = [
            'status' => $status,
            'message' => $error,
            'data' => $data,
        ];

        return response()->json($response, $status, $headers);
    }

    /**
     * 預處理驗證的錯誤訊息
     *
     * @param \Illuminate\Support\MessageBag $error
     * @return string
     */
    protected function processMessageBag(MessageBag $error): string
    {
        $error = $error->toArray();

        $error = collect($error)
            ->map(function ($error) {
                return $error[0];
            })
            ->values()
            ->join('、');

        return $error;
    }
}
