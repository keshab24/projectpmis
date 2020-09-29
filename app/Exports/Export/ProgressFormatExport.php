<?php

namespace PMIS\Exports\Export;


use PMIS\Exports\ExportTrait;
use PMIS\Exports\shouldExportExcel;
use Illuminate\Support\Collection;


class ProgressFormatExport implements shouldExportExcel
{


    use ExportTrait;
    protected $fileName = 'progress';



    public function __construct(Collection $projectCollection)
    {
        /* $this->projectCollection
         * Supported by ExportTrait
         * */
        $this->projectCollection = $projectCollection;
    }


    public function bodyInit()
    {
        return [
            'Implementing Office'=>[
                'implementing_office',
            ],
            'Project Code'=>[
                'project_code'
            ],
            'project cost'=>[
                'projectCost()',
            ],
        ];
    }

}
