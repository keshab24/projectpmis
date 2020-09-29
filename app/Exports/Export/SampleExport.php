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

class SampleExport implements shouldExportExcel
{
    use ExportTrait;

    public function __construct(Collection $projectCollection)
    {
        $this->projectCollection = $projectCollection;
    }

    public function bodyInit()
    {
        return [
            "क्र.सं."=>[
                "__counter__"=>true
            ],
            "Division Name"=>[
                "implementing_office"=>"name_eng"
            ],
            "Nested"=>[
                "implementing_office"=>["creator"=>["father"=>"name"]] // Item ko implemtng office ko creator ko father ko name.. can be nested in multiple levels
            ],
            "Country"=>[
                "static"=>"Nepal" // some fileds require static values like ... Project ko country is always NEPAL
            ],
            "function"=>[
                "called function"=>"callLikeThis()"
            ],
            "Project Code"=>[
                "project_code" // from same table
            ]
        ];
    }
}