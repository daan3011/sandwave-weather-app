<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Container\Container;

abstract class BaseResource extends JsonResource
{
    protected function resolveService($abstract)
    {
        return Container::getInstance()->make($abstract);
    }
}
