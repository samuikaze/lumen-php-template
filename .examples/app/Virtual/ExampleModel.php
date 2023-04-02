<?php

namespace App\Virtual\Resources;

/**
 * @OA\Schema(
 *   title="範例模型",
 *   description="範例模型描述",
 *   type="object",
 *   required={"version"}
 * )
 */
class ExampleModel
{
    /**
     * @OA\Property(
     *   title="版本",
     *   description="系統版本",
     *   example="1.0.0"
     * )
     *
     * @var string
     */
    public $version;
}
