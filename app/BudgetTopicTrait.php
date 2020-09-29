<?php
namespace PMIS;

trait BudgetTopicTrait {

    public function getBudgetTopicNumAttribute()
    {
        $field = $this->seeGivenFyAndReturnIfItRequiresOldFyorNew();
        return $this->getOriginal('budget_topic_num'.$field);
    }

    public function getBudgetHeadAttribute()
    {
        $field = $this->seeGivenFyAndReturnIfItRequiresOldFyorNew();
        return $this->getOriginal('budget_head'.$field);
    }

    public function getBudgetHeadEngAttribute()
    {
        $field = $this->seeGivenFyAndReturnIfItRequiresOldFyorNew();
        return $this->getOriginal('budget_head'.$field);
    }
    public function getPriorityAttribute()
    {
        $field = $this->seeGivenFyAndReturnIfItRequiresOldFyorNew();
        return $this->getOriginal('priority'.$field);
    }


    public function seeGivenFyAndReturnIfItRequiresOldFyorNew()
    {
        return seeGivenFyAndReturnIfItRequiresOldFyorNew();
    }


}
