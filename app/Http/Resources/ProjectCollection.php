<?php

namespace PMIS\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public $collects = 'PMIS\Http\Resources\Project';
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'count' => count($this->collection),
        ];
    }
}
