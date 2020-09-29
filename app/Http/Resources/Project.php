<?php

namespace PMIS\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Project extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'project_code' => $this->project_code,
            'name_eng' => $this->name_eng,
            'monitoring_office_id' => $this->monitoring_office_id,
            'monitoring_office' => $this->monitoring_office_id,
            'time_extension_count' => count($this->timeExtension),
            'notifications_count' => $this->notifications_count,
            'contractor_id' => $this->contractor,
            'is_overrun' => $this->time_overrun['is_overrun'],
            'time_overrun' => $this->time_overrun['time_overrun'],
            'physical_progress' => $this->progresses()->get()->last()->progressTrack->physical_percentage,
            'authorized_persons' => implode(', ', $this->authorizedPersons->pluck('name')->toArray()),
            'implementing_office' => $this->implementing_office_id,
            'budget_topic_id' => $this->budget_topic_id,
            'district_id' => $this->district_id,
            'project_group' => $this->project_group_id,
            'fiscal_year' => $this->start_fy_id,
        ];
    }
}
