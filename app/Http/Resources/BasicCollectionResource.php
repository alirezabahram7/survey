<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BasicCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $sign
     * @return array
     */
    public function toArray($request,$sign = 'data')
    {
        return [
            $sign => $this->collection,
        ];
    }
}
