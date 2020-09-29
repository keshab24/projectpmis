<?php
/**
 * Created by PhpStorm.
 * User: Lankuri
 * Date: 19/07/2018
 * Time: 10:23
 */

namespace PMIS\Exports\Export;


use Illuminate\Support\Collection;
use PMIS\Exports\ExportTrait;
use PMIS\Exports\shouldExportExcel;

class SeekProjectExport implements shouldExportExcel
{
    use ExportTrait;

    public function __construct(Collection $projectCollection)
    {
        $this->projectCollection = $projectCollection;
    }

    public function bodyInit()
    {
        return [
            "Creator"=>[
                "implementing_office"=>["creator"=>"name"]
            ],
            "क्र.सं."=>[
                "__counter__"=>true
            ],
            "Division Name"=>[
                "implementing_office"=>"name_eng"
            ],
            "Project Code"=>[
                "project_code"
            ],
            "Project Name"=>[
                "name_eng"
            ],
            "Base Year"=>[
                "fiscal_year"=>"fy"
            ],
            "Remarks"=>[
                "lastProgress"=>"project_remarks"
            ]
        ];
    }
}