<?php

namespace PMIS\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BudgetTopic extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $projectCount = $this->projectCount($request->get('fy_id'));
        if($projectCount > 0) {
            return [
                'id' => $this->id,
                'budget_topic_num' => $this->budget_head . ':' . $this->getOriginal('budget_topic_num'),
                'project_count' => $projectCount,
            ];
        }
    }
}
