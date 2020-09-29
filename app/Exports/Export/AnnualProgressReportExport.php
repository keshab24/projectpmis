<?php
/**
 * Created by PhpStorm.
 * User: Lankuri
 * Date: 18/07/2018
 * Time: 16:30
 */

namespace PMIS\Exports\Export;


use Illuminate\Support\Collection;
use PMIS\Exports\ExportTrait;
use PMIS\Exports\shouldExportExcel;

class AnnualProgressReportExport implements shouldExportExcel
{
    use ExportTrait;
    protected $fileName = 'annual progress';
    protected $yearlyAimedBudgetGrantTotal;

    protected $cellColorForColumn = [
                                        ['G'=>'#FFFFF00'],
                                        ['H'=>'#FFFFF00'],
                                        ['I'=>'#FFFFF00'],
                                        ['J'=>'#FFFFF00'],
                                        ['N'=>'#FFFFF00'],
                            ];
    public function __construct(Collection $projectCollection)
    {
        $this->projectCollection = $projectCollection;
        $this->yearlyAimedBudgetGrantTotal = $projectCollection->sum('yearly_aimed_budgets');
    }

    public function bodyInit()
    {
        return [
            "Lmbis Code"=>[ //A
                "lmbis_code"
            ],
            "डिभिजन कार्यालय"=>[ //B
                "implementing_office_setting"=>"name"
            ],
            "आइ.डी."=>[ //C
                "code"
            ],
            "क्रियाकलाप"=>[ //D
                "name"
            ],
            "इकाई"=>[ //E
                "unit"
            ],

            "बार्षिक लक्ष्य बजेट"=>[ //F
                "yearly_aimed_budget"
            ],
            "बार्षिक लक्ष्य परिमाण "=> [//G
                //barsik lakxya budget/project cost *100
                "__formula__"=>"=SUM(F2,L2)*100"
            ],
            "बार्षिक लक्ष्य भार"=>[ //H
                //barsik lakxya budget/barsik lakxya budget ko grand total *100
                "__formula__"=>"=SUM(F2+".$this->yearlyAimedBudgetGrantTotal.")"
            ],

            "बार्षिक प्रगति परिमाण"=>[ //I
                "__formula__"=>"=G2*0"
            ],
            "बार्षिक प्रगति भारित"=>[ //J
                "__formula__"=>"=(I2/G2)*H2"
            ],
            "बार्षिक प्रगति बजेट"=>[ //K
                "static"=>0
            ],

            "कार्यक्रम लागत"=>[ //L
                "projectCost()"
            ],
            "कन्टिनजेनसी खर्च"=>[ //M
                "static"=>0
            ],
            "जम्मा खर्च"=>[ //N
                "__formula__"=>"=SUM(L2:M2)"
            ],
            "प्रतिसत"=>[ //O
                "static"=>100
            ],
            "कैफेयत"=>[ //P
                "lastProgress"=>"project_remarks"
            ],
        ];
    }
}