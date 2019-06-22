<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $sign
     * @return array
     */
    public function toArray($request, $sign = 'data')
    {
        return [
            $sign => $this->resource
        ];
    }
}