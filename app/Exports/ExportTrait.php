<?php

namespace PMIS\Exports;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;

trait ExportTrait
{
    protected $exportTable;
    protected $projectCollection;
    protected $attributeAndValue;

    public static function getValueFromRelation($item,$relation,$index,$itemCount)
    {
        if(is_array($index)){
            $relation_name = array_keys($index)[0];
            $index_name = array_values($index)[0];
            return self::getValueFromRelation($item->$relation,$relation_name,$index_name,$itemCount);
        }elseif(strcmp($relation,"static")==0){
            return $index;
        }elseif(strcmp($relation,"__counter__")==0){
            return $itemCount;
        }elseif(strcmp($relation,"__formula__")==0){
            if($itemCount>1){
                    return null;
            }
            return $index;
        }else{
            return $item->$relation?$item->$relation->$index:"N/A";
        }
    }

    public function setBody()
    {
        /*
         * Initialization of header of the excel array
         * */
        $this->attributeAndValue = $this->bodyInit();
        $this->exportTable[] = array_keys($this->attributeAndValue);

        /*
         * Initialization of Body of the excel array
         * */
        $exportField = array();
        $itemCount = 0;
        foreach ($this->projectCollection as $item){
            $itemCount++;
            $count = 0;
            foreach ($this->attributeAndValue as $index=>$attribute){
                $index = array_keys($attribute)[0];
                $attribute = array_values($attribute)[0];
                if(is_string ($index)){
                    // true when relation is passed as an array, can be nested relation too.. $project->creator->district->zone->name
                    $exportField[$count++] = self::getValueFromRelation($item,$index,$attribute,$itemCount);
                }else if(strpos( $attribute, "()") !== false) {
                    #passed if it is a function i.e. $project->projectCost();
                    $method = str_replace("()", "", $attribute);
                    if(!method_exists ( $item , $method)){
                        throw new Exception("Method $method() not found ".get_class($item). ". Check your array declaration in bodyInit() method");
                    }
                    $exportField[$count++] = $item->$method();
                }
               else{
                    #passed, direct relation
                    $exportField[$count++] = $item->$attribute;
                }
            }
            $this->exportTable[] = $exportField;
        }
        $this->checkFileName();
    }

    public function export()
    {
        return Excel::create($this->fileName, function ($excel) {
            $excel->sheet('sheet', function ($sheet){
                $sheet->cell(1, function($row) {
                    $row->setAlignment('center');
                    $row->setFontWeight('bold');
                });
                if(isset($this->cellColorForColumn)){
                    foreach ($this->cellColorForColumn as $cell){
                        $columnColor = array_values($cell)[0];
                        $columnName = array_keys($cell)[0];
                        $columnName  = $columnName."1:".$columnName.($this->projectCollection->count()+1);
                        $sheet->cells($columnName, function ($cells)use ($columnColor) {
                            $cells->setBackground($columnColor);
                        });
                    }
                }

                $sheet->fromArray($this->exportTable, null, 'A1', false, false);
            });
        })->download(self::excelFormat);
    }

    public function download(){
        $this->setBody();
        return $this->export();
    }

    private function checkFileName(){
        !isset($this->fileName)?$this->fileName="PMIS":true;
    }

    private function paintColumn($sheet){

    }


}