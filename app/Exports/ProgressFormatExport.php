<?php

namespace PMIS\Exports;


use PMIS\Project;
use Maatwebsite\Excel\Facades\Excel;

class ProgressFormatExport
{

    protected $exportTable;
    protected $excelFormat = 'xls';

    public function headings()
    {
        $heading[0] = "Id";
        $heading[1] = "Project Code";
        $heading[2] = "Name";
        $heading[3] = "Name English";
        $this->exportTable[] = $heading;
    }
    public function export()
    {
        $this->headings();
        $projects = Project::where('show_on_running','1')->orderBy('implementing_office_id','asc');
        foreach ($projects->get() as $index => $project) {
            $exportField[0] = $project->id;
            $exportField[1] = $project->project_code;
            $exportField[2] = $project->name;
            $exportField[3] = $project->name_eng;
            $this->exportTable[] = $exportField;
        }


        return Excel::create('Progress', function ($excel) {
            $excel->sheet('sheet1', function ($sheet){
                $sheet->fromArray($this->exportTable, null, 'A1', false, false);
            });
        })->download($this->excelFormat);

    }


}