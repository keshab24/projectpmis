<?php

namespace PMIS\Exports;


use Illuminate\Support\Collection;


interface shouldExportExcel
{
    const excelFormat = 'xls';
    public function __construct(Collection $projectCollection);
    public function bodyInit();
    public function setBody();
    public function export();
    public function download();
}