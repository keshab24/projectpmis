<?php

/*
 * Mass Action
 * */

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PMIS\Fiscalyear;
use PMIS\ImplementingOffice;
use PMIS\Project;
use PMIS\ProjectSetting;
use PMIS\ProLog;
use PMIS\OldProjectCode;
function massAction($class = null, $url, $model)
{
    $return = '<div class="input-group ' . $class . ' massActionWrapper" style="padding-left:0">';
    $return .= '<select name="massAction" id="massAction" class="form-control">';
    $return .= '<option value="0">Choose Mass Action</option>';
    if (!isset($_GET['trashes'])) {
        //$return .= '<option value="1">Edit</option>';
        //$return .= '<option value="2">Trash</option>';
        if ($model == "Project")
            $return .= '<option value="4">Not Allocated</option>';
    }
    if (Auth::user()->access === 'Root Level') {
        $return .= '<option disabled="disabled" value="3">Permanent Delete</option>';
    }
    $return .= '</select>';
    $return .= '<span class="input-group-btn"><a href="' . $url . '" class="btn btn-sm btn-primary" role="button" data-model-name="' . $model . '"><span class="fa fa-send"></span> <span class="hidden-xs hidden-sm"> Do it!!</span></a></span>';
    $return .= '</div>';
    return $return;
}

/*
 *Delete data form
 */
function delete_form($routeParams, $formId)
{
    $form = Form::open(['route' => $routeParams, 'method' => 'delete', 'class' => 'form_inline', 'id' => $formId]);
    return $form .= Form::close();
}

function completed_form($routeParams, $setting)
{
    $form = Form::open(['route' => $routeParams, 'method' => 'post', 'class' => 'form_inline', 'id' => 'pro_my_form_completed' . $setting->project->id, 'data-project-name' => $setting->project->name, 'data-project-code' => $setting->project_code, 'data-completed-date' => $setting->project->completed_date, 'data-ho-date' => $setting->project->ho_date]);
    $form .= Form::hidden('project_id', $setting->project->id);
    return $form .= Form::close();
}

/*
 * Hard Delete data form
 */
function hard_delete_form($routeParams, $formId)
{
    $form = Form::open(['route' => $routeParams, 'method' => 'delete', 'class' => 'form_inline', 'id' => $formId]);
    $form .= Form::hidden('hardDelete', 'vtx');
    return $form .= Form::close();
}

/*
 * Add Button round plus sign
 * */
function addButton($route, $title)
{
    return '<a href="' . $route . '" target="_blank" title="' . $title . '" class="btn btn-warning showToolTip btn-xs pull-right"><span class="glyphicon glyphicon-plus"></span></a>';
}

/* Minimize button using id */
function minimizeButton($id, $title = null)
{
    return '<div class="input-group pull-right"><button type="button" class="btn btn-default btn-xs showToolTip focusInput" title="Show/Hide ' . $title . '" data-target="#' . $id . '" data-toggle="collapse" ><span class="glyphicon glyphicon-menu-down"></span></button></div><div class="clearfix"></div>';
}

/* See more button */
function seeMore($route, $title = null)
{
    return '<a href="' . $route . '" title="' . $title . '" class="btn btn-xs btn-info pull-right pro_more showToolTip">More</a><div class="clearfix"></div>';
}

/* Give smooth text or value*/
function giveSmoothText($text, $val, $number = false, $separator = ' ')
{
    $text = strip_tags($text);
    if ($text == '') {
        $return = 0;
    } elseif (strlen($text) <= $val) {
        $return = $val;
    } else {
        $vt = 1;
        $loop_counter = 1;
        while ($vt != 0) {
            if (substr($text, $val, 1) != $separator) {
                $val--;
                $vt = 1;
            } else $vt = 0;
            if ($loop_counter++ == 30) {
                break;
            }
        }
        $return = $val;
    }
    if ($number == true) {
        return $return;
    } else {
        return str_split($text, $val)[0];
    }
}

/**
 * @param $fileName
 * @return mixed
 */
function makeFileName($fileName)
{
    return preg_replace('/[^0-9a-zA-Z\.\-]/', '', str_replace(' ', '-', $fileName));
}

/**
 * @param $fileName
 * @return mixed
 */
function getFileName($file)
{
    $extension = $file->getClientOriginalExtension();
    $fileName = time() . str_replace('.' . $extension, '', $file->getClientOriginalName());
    return preg_replace('/[^0-9a-zA-Z\.\-]/', '', str_replace(' ', '-', $fileName)) . '.' . $extension;
}

/**
 * @param $fileName
 * @param null $file_info
 * @return string
 */
function makeImageName($fileName, $file_info = null)
{
    if ($file_info == null) {
        $tmp = explode('.', $fileName);
        $extension = end($tmp);
        $fileName = str_replace('.' . $extension, '', $fileName);
    } else {
        $extension = pathinfo($file_info, PATHINFO_EXTENSION);
    }
    return makeFileName($fileName) . '_' . time() . "." . $extension;
}

function add_my_array($array, $text = 'Any', $value = '')
{
    $myArray[$value] = $text;
    foreach ($array as $key => $value) {
        $myArray[$key] = $value;
    }
    return $myArray;
}

function get_trim($trimester)
{
    if ($trimester == "1") {
        $trimesters = "first_trim";
    } elseif ($trimester == "2") {
        $trimesters = "second_trim";
    } elseif ($trimester == "3") {
        $trimesters = "third_trim";
    }
    return $trimesters;
}

function rank_nepali($index)
{
    if ($index == "1") {
        $return = "प्रथम";
    } elseif ($index == "2") {
        $return = "दोश्रो";
    } elseif ($index == "3") {
        $return = "तेश्रो";
    } elseif ($index == "4") {
        $return = "चौथो";
    } elseif ($index == "5") {
        $return = "पाँचौ";
    } elseif ($index == "6") {
        $return = "छैठौ";
    } elseif ($index == "7") {
        $return = "सातौं";
    } elseif ($index == "8") {
        $return = "आठौँ";
    } elseif ($index == "9") {
        $return = "नवौं";
    } else {
        $return = "बार्षिक";
    }
    return $return;
}

function quantity()
{
    return 0;
}

function toggleClass(&$class)
{
    if ($class == '') {
        $class = 'warning';
    } else {
        $class = '';
    }
    return true;
}

function dateAD($date)
{
    return \PMIS\DateCon::whereDatenepformatted($date)->pluck('dateeng')->first();
}

function dateBS($date)
{
    return \PMIS\DateCon::whereDateeng($date)->pluck('datenepformatted')->first();
}

function tenth($a)
{
    switch ($a) {
        case 1:
            return "Ten";
            break;
        case 2:
            return "Twenty";
            break;
        case 3:
            return "Thirty";
            break;
        case 4:
            return "Forty";
            break;
        case 5:
            return "Fifty";
            break;
        case 6:
            return "Sixty";
            break;
        case 7:
            return "Seventy";
            break;
        case 8:
            return "Eighty";
            break;
        case 9:
            return "Ninety";
            break;
    }
}

function ones($a)
{
    switch ($a) {
        case 1:
            return "One";
            break;
        case 2:
            return "Two";
            break;
        case 3:
            return "Three";
            break;
        case 4:
            return "Four";
            break;
        case 5:
            return "Five";
            break;
        case 6:
            return "Six";
            break;
        case 7:
            return "Seven";
            break;
        case 8:
            return "Eight";
            break;
        case 9:
            return "Nine";
            break;
    }
}

function tens($a)
{
    if ($a < 10) {
        return ones($a);
    } elseif ($a < 20) {
        switch ($a) {
            case 10:
                return "Ten";
                break;
            case 11:
                return "Eleven";
                break;
            case 12:
                return "Twelve";
                break;
            case 13:
                return "Thirteen";
                break;
            case 14:
                return "Fourteen";
                break;
            case 15:
                return "Fifteen";
                break;
            case 16:
                return "Sixteen";
                break;
            case 17:
                return "Seventeen";
                break;
            case 18:
                return "Eighteen";
                break;
            case 19:
                return "Nineteen";
                break;
        }
    } else {
        $ones = $a % 10;
        $tens = intval($a / 10) % 10;
        $w = tenth($tens) . " " . ones($ones);
        return $w;
    }
}

function hundreds($a)
{
    $hun = ones(intval($a / 100));
    $te = substr($a, (strlen($a) - 2), 2);
    $ten = tens($te);
    return $hun . " Hundred " . $ten;
}

function thousands($a)
{
    $thou = ones(intval($a / 1000));
    $hunv = intval($a / 100) % 10;
    $ten = tens($a % 100);
    if ($hunv == 0)
        return $thou . " Thousand " . $ten;
    else {
        $hun = ones($hunv);
        return $thou . " Thousand " . $hun . " Hundred " . $ten;
    }
}

function tthou($a)
{
    $thou = tens(intval($a / 1000));
    $hunv = intval($a / 100) % 10;
    $ten = tens($a % 1000);
    if ($hunv == 0)
        return $thou . " Thousand " . $ten;
    else {
        $hun = ones($hunv);
        return $thou . " Thousand " . $hun . " Hundred " . $ten;
    }
}

function inwords($a)
{
    if (strchr($a, "."))
        if (strlen(strchr($a, ".")) == 3)
            $paisa = tens(substr($a, (strlen($a) - 2), 2));
        else
            $paisa = tenth(substr($a, (strlen($a) - 1), 1));
    else
        $paisa = '';
    if ($a < 10)
        $rupees = ones($a);
    elseif ($a < 100)
        $rupees = tens($a);
    elseif ($a < 1000)
        $rupees = hundreds($a);
    elseif ($a < 10000)
        $rupees = thousands($a);
    elseif ($a <= 100000)
        $rupees = tthou($a);
    else
        $rupees = $a;
    $xx = $rupees . " Rupees";
    if ($paisa != '')
        $xx .= " And " . $paisa . " Paisa";
    $xx .= " Only";
    echo $xx;
}

function nature_of_project()
{
    /*
     * HBU
    */
    $return['eng'][341][0] = "Chose Construction Nature";
    $return['eng'][341][1] = "New Construction";
    $return['eng'][341][2] = "Re-construction";
    $return['eng'][341][3] = "Maintenance";
    $return['eng'][341][4] = "Storey Addition";
    $return['eng'][341][5] = "Block Addition";
    $return['eng'][341][6] = "Miscellaneous";

    $return['nep'][341][0] = "प्रकृति चयन गर्नुहोस";
    $return['nep'][341][1] = "नया निर्माण";
    $return['nep'][341][2] = "पुनर्निर्माण";
    $return['nep'][341][3] = "मर्मत";
    $return['nep'][341][4] = "तला थप";
    $return['nep'][341][5] = "ब्लक थप";
    $return['nep'][341][6] = "विविध";

    /*
        NTP
    */

    $return['eng'][343][0] = "Chose Construction Nature";
    $return['eng'][343][7] = "Physical Infrastructure";
    $return['eng'][343][8] = "Social Infrastructure";
    $return['eng'][343][9] = "Economic Infrastructure";
    $return['eng'][343][10] = "Other";
    $return['eng'][343][11] = "Data Not Available";

    $return['nep'][343][0] = "प्रकृति चयन गर्नुहोस";
    $return['nep'][343][7] = "भौतिक पुर्बाधार";
    $return['nep'][343][8] = "सामाजिक पुर्बाधार";
    $return['nep'][343][9] = "आर्थिक पुर्बाधार";
    $return['nep'][343][10] = "विविध";
    $return['nep'][343][11] = "डाटा उपलब्ध नभएको";
    /*
        Planning Section
    */


    $return['eng'][342][0] = "Chose Construction Nature";
    $return['eng'][342][11] = "Unknown";
    $return['eng'][342][12] = "विविध";
    $return['eng'][342][13] = "New Construction";
    $return['eng'][342][14] = "Maintenance";
    $return['eng'][342][15] = "Engineering/Report";

    $return['nep'][342][0] = "प्रकृति चयन गर्नुहोस";
    $return['nep'][342][11] = "थाहा नभएको";
    $return['nep'][342][12] = "विविध";
    $return['nep'][342][13] = "नया निर्माण";
    $return['nep'][342][14] = "मर्मत";
    $return['nep'][342][15] = "इन्जिनियर/रिपोर्ट";

    $return['eng'][397][6] = "Miscellaneous";

    $return['nep'][397][6] = "विविध";

    $return['eng'][397][0] = "Chose Construction Nature";
    $return['eng'][397][6] = "Miscellaneous";
    $return['eng'][397][11] = "Unknown";
    $return['eng'][397][12] = "विविध";
    $return['eng'][397][13] = "New Construction";
    $return['eng'][397][14] = "Maintenance";
    $return['eng'][397][15] = "Engineering/Report";

    $return['nep'][397][0] = "प्रकृति चयन गर्नुहोस";
    $return['nep'][397][6] = "विविध";
    $return['nep'][397][11] = "थाहा नभएको";
    $return['nep'][397][12] = "विविध";
    $return['nep'][397][13] = "नया निर्माण";
    $return['nep'][397][14] = "मर्मत";
    $return['nep'][397][15] = "इन्जिनियर/रिपोर्ट";


//    Special Building Construction Project Coordination Office
    $return['eng'][410][6] = "Miscellaneous";

    $return['nep'][410][6] = "विविध";

    $return['eng'][410][0] = "Chose Construction Nature";
    $return['eng'][410][6] = "Miscellaneous";
    $return['eng'][410][11] = "Unknown";
    $return['eng'][410][12] = "विविध";
    $return['eng'][410][13] = "New Construction";
    $return['eng'][410][14] = "Maintenance";
    $return['eng'][410][15] = "Engineering/Report";

    $return['nep'][410][0] = "प्रकृति चयन गर्नुहोस";
    $return['nep'][410][6] = "विविध";
    $return['nep'][410][11] = "थाहा नभएको";
    $return['nep'][410][12] = "विविध";
    $return['nep'][410][13] = "नया निर्माण";
    $return['nep'][410][14] = "मर्मत";
    $return['nep'][410][15] = "इन्जिनियर/रिपोर्ट";
    return $return;
}

function visibleNature_of_project()
{
    if (Auth::User()->implementingOffice->isMonitoring == 1) {
        return nature_of_project()['eng'][Auth::User()->implementing_office_id];
    } else {
        $offices = Auth::User()->implementingOffice->implementingSeesMonitor->pluck('id');
        $unsorted = array();
        foreach ($offices as $office) {
            $unsorted += nature_of_project()['eng'][$office];
        }
        ksort($unsorted);
        return $unsorted;
    }
}

function construction_located_area()
{
    $return['eng'][] = "Choose Construction Area";
    $return['nep'][] = "निर्माण स्थल चयन गर्नुहोस";
// just for saving myself in -case some one is calling this function. because it was a static array later converted to database model
    $cla = \PMIS\ConstructionLocatedArea::all();
    foreach ($cla as $area) {
        $return['eng'][] = $area->located_area;
        $return['nep'][] = $area->located_area_nep;
    }
    return $return;
}

function getLand()
{
    $return[0] = "तराई";
    $return[1] = "पहाड";
    $return[2] = "हिमाल";
    return $return;
}

function insideHeadquarter()
{
    $return[0] = "थाहा नभएको";
    $return[1] = "होइन";
    $return[2] = "हो";
    return $return;
}

function swamittwo()
{
    $return[0] = "थाहा नभएको";
    $return[1] = "संस्थासंग नरहेकाे";
    $return[2] = "संस्थासंग रहेकाे";
    return $return;
}

function jaggaType()
{
    $return[0] = "थाहा नभएको";
    $return[1] = "ऐलानी";
    $return[2] = "प्रति";
    $return[3] = "गुठी";
    $return[4] = "वन";
    $return[6] = "अन्य";
    $return[5] = "सार्बजनिक";
    $return[7] = "नगर विकास समिति";
    $return[8] = "नेपाल सरकार";
    return $return;
}

function designType()
{
    $return[3] = "थाहा नभएको";
    $return[0] = "Standard Design";
    $return[1] = "Type 1";
    $return[2] = "Type 2";
    $return[4] = "PH A1";
    $return[5] = "PH A2";
    $return[6] = "PH A3";
    $return[7] = "PH B1";
    $return[8] = "PH B2";
    $return[9] = "PH B3";
    $return[10] = "HP Type A";
    $return[11] = "HP Type B";
    $return[12] = "HP Type C";
    $return[13] = "HP Type D";

    return $return;
}

function getProjectStatus()
{
    $return[4] = "Any";
    $return[0] = "Running";
    $return[6] = "Not Running";
    $return[1] = "Work Complete";
    $return[2] = "Hand Over";
    $return[3] = "Terminated";
    $return[5] = "Cancelled";
    return $return;
}

function getPaymentStatus()
{
    $return[0] = "Due Payment";
    $return[1] = "Payment Done";
    return $return;
}

function getPurchaseMethod()
{
    $return["NCB"] = "NCB";
    $return["ICB"] = "ICB";
    $return["DP"] = "DP";
    $return["SQ"] = "SQ";
    return $return;
}

function verifiedFrom()
{
    $return[0] = "थाहा नभएको";
    $return[1] = "बिभाग";
    $return[2] = "डिभिजन";
    $return[3] = "मन्त्रालय";
    return $return;
}


function engineersType()
{
    $return['Engineer'] = "Engineer";
    $return['Sub Engineer'] = "Sub Engineer";
    return $return;
}

function unit()
{
    $return[0] = "वर्ग मि";
    $return[1] = "प्रतिशत";
    $return[2] = "संख्या";
    $return[3] = "कि.मि";
    $return[4] = "लिटर";
    $return[5] = "मि";
    $return[6] = "जना";
    $return[7] = "दिन";
    $return[8] = "महिना";
    $return[9] = "रोपनी";
    $return[10] = "थान";
    $return[11] = "पटक";
    $return[12] = "वटा";
    $return[13] = "प्याकेज";
    $return[14] = "प्रदेश/जिल्ला";
    $return[15] = "सट";
    return $return;
}

//function getStoreyArea(){
//    $return[0] = "थाहा नभएको";
//    $return[1] = "१ तल्ले";
//    $return[2] = "२ तल्ले";
//    $return[3] = "३ तल्ले";
//    $return[4] = "४ तल्ले";
//    $return[5] = "५ तल्ले";
//    $return[6] = "लागु नहुने (Not Applicable)";
//    return $return;
//}

function getStoreyArea()
{
    $return[0] = "थाहा नभएको";
    $return[1] = "१ तल्ले";
    $return[2] = "१.५ तल्ले";
    $return[3] = "२ तल्ले";
    $return[4] = "२.५ तल्ले";
    $return[5] = "३ तल्ले";
    $return[6] = "३.५ तल्ले";
    $return[7] = "४ तल्ले";
    $return[8] = "४.५ तल्ले";
    $return[9] = "५ तल्ले";
    $return[10] = "५.५ तल्ले";
    $return[11] = "लागु नहुने (Not Applicable)";
    return $return;
}

function rooftype()
{
    $return[0] = "थाहा नभएको";
    $return[1] = "ढलान";
    $return[2] = "जस्ता";
    $return[3] = "यु.पी.भी.सी छत";
    return $return;
}

function doorWindow()
{
    $return[0] = "थाहा नभएको";
    $return[1] = "काठे";
    $return[2] = "फलामे";
    $return[3] = "अल्मुनियम";
    return $return;

}

function wallType()
{
    $return[0] = "थाहा नभएको";
    $return[1] = "इट्टा";
    $return[2] = "ढुंगा";
    return $return;
}

function bsType()
{
    $return[0] = 'थाहा नभएको';
    $return[1] = 'Load Bearing';
    $return[2] = 'Frame Structure';
    $return[3] = 'RCC';
    $return[4] = 'Steel';
    $return[5] = 'Structure';
    $return[6] = 'Other';
    return $return;
}

function soilTest()
{
    $return[0] = 'थाहा नभएको';
    $return[1] = 'नभएको';
    $return[2] = 'भएको';
    $return[3] = 'लागु नहुने ';
    return $return;
}

/**
 * @param $date
 * @return bool|string
 */
function date_display($date)
{
    $return = '<span>' . date('Y-m-d', strtotime($date)) . '</span><br />';
    return $return .= '<span class="label label-default">' . date('G:i a', strtotime($date)) . '</span>';

}

function variations_choose()
{
    return \PMIS\Variation::variationOptions();
}


function exportFields()
{
    $return['address'] = ['Address', '', '', 0, 0, 'section_project'];
    $return['approved_date'] = ['Approved Date', '', '', 0, 0, 'section_project'];
    $return['fy_id'] = ['Base Year', 'fiscal_year', 'fy', 0, 0, 'section_project'];
    $return['design_type'] = ['Design Type', '', '', 0, 0, 'section_project'];
    $return['district'] = ['District', '', 'district', 0, 0, 'section_project'];
    $return['district_eng'] = ['District Eng', '', 'district_eng', 0, 0, 'section_project'];
    $return['door_window'] = ['Door/Window', '', '', 0, 0, 'section_project'];
    $return['geo_location'] = ['Geographical Location', '', '', 0, 0, 'section_project'];
    $return['implementing_office'] = ['Implementing Office', 'implementing_office', 'name', 1, 0, 'section_project'];
    $return['implementing_office_eng'] = ['Implementing Office Eng', 'implementing_office_eng', 'name', 1, 0, 'section_project'];
    $return['land_ownership'] = ['Land Ownership', '', '', 0, 0, 'section_project'];
    $return['nature_of_project_id'] = ['Nature of Project', '', '', 0, 0, 'section_project'];
    $return['payment_status'] = ['Payment Status', '', '', 0, 0, 'section_project'];
    $return['project_code'] = ['Project Code', '', '', 1, 0, 'section_project'];
    $return['project_code_old'] = ['Old Project Code', '', '', 1, 0, 'section_project'];
    $return['project_cost'] = ['Project Cost', '', 'project_cost', 1, 0, 'section_project', 'section_project'];
    $return['name'] = ['Project Name', '', '', 1, 0, 'section_project'];
    $return['name_eng'] = ['Project Name Eng', '', '', 0, 0, 'section_project'];
    $return['project_status'] = ['Project Status', '', '', 0, 0, 'section_project'];
    $return['roof_type'] = ['Roof Type', '', '', 0, 0, 'section_project'];
    $return['soil_test'] = ['Soil Test', '', '', 0, 0, 'section_project'];
    $return['wall_type'] = ['Wall Type', '', '', 0, 0, 'section_project'];
    $return['yearly_budget'] = ['Yearly Budget', '', '', 0, 0, 'section_project'];
    $return['zone_name'] = ['Zone', '', '', 0, 0, 'section_project'];
    //
    $return['bid_does_ready_est'] = ['Bid Ready Est', 'procurement', 'bid_does_ready_est', 0, 0, 'section_project'];
    $return['bid_does_ready_act'] = ['Bid Ready Act', 'procurement', 'bid_does_ready_act', 0, 0, 'section_project'];
    $return['no_obj_est1'] = ['No objection Est1', 'procurement', 'no_obj_est1', 0, 0, 'section_project'];
    $return['no_obj_act1'] = ['No objection Act1', 'procurement', 'no_obj_act1', 0, 0, 'section_project'];
    $return['no_obj_est2'] = ['No objection Est2', 'procurement', 'no_obj_est2', 0, 0, 'section_project'];
    $return['no_obj_act2'] = ['No objection Act2', 'procurement', 'no_obj_act2', 0, 0, 'section_project'];
    $return['call_for_bid_est'] = ['Call for Bid Est', 'procurement', 'call_for_bid_est', 0, 0, 'section_project'];
    $return['call_for_bid_act'] = ['Call for Bid Act', 'procurement', 'call_for_bid_act', 0, 0, 'section_project'];
    $return['bid_open_est'] = ['Bid open Est', 'procurement', 'bid_open_est', 0, 0, 'section_project'];
    $return['bid_open_act'] = ['Bid open Act', 'procurement', 'bid_open_act', 0, 0, 'section_project'];
    $return['bid_eval_est'] = ['Bid Evaluation Est', 'procurement', 'bid_eval_est', 0, 0, 'section_project'];
    $return['bid_eval_act'] = ['Bid Evaluation Act', 'procurement', 'bid_eval_act', 0, 0, 'section_project'];
    $return['con_sign_est'] = ['Contract Signed Est', 'procurement', 'con_sign_est', 0, 0, 'section_project'];
    $return['contract_date'] = ['Contract Signed Act', 'procurement', 'contract_date', 0, 0, 'section_project'];
    $return['con_end_est'] = ['Contract End Est', 'procurement', 'con_end_est', 0, 0, 'section_project'];
    $return['completion_date'] = ['Contract End Act', 'procurement', 'completion_date', 0, 0, 'section_project'];


    $return['project_group_id'] = ['Project group', 'group', 'name', 0, 0, 'section_group'];

    $return['contract_amount'] = ['Contract Amount', 'procurement', 'contract_amount', 0, 0, 'section_procurement'];
    $return['completed_date'] = ['Completed Date', '', '', 0, 0, 'section_procurement'];
    $return['timeExtension'] = ['Completion Date (Time Extension)', 'timeExtension', 'end_date', 0, 1, 'section_procurement'];
    $return['completion_date'] = ['Completion Date (Contract)', 'procurement', 'completion_date', 0, 0, 'section_procurement'];
    $return['con_est_amt_net'] = ['Cost Estimate', 'procurement', 'con_est_amt_net', 0, 0, 'section_procurement'];
    $return['est_approved_date'] = ['Cost Estimate Date', 'procurement', 'est_approved_date', 0, 0, 'section_procurement'];
    $return['construction_located_area'] = ['Construction Located Area', '', '', 0, 0, 'section_procurement'];
    $return['contract_date'] = ['Contract Date', 'procurement', 'contract_date', 0, 0, 'section_procurement'];
    $return['method'] = ['Contract Method', 'procurement', 'method', 0, 0, 'section_procurement'];
    $return['contractor'] = ['Contractor', 'procurement', 'contractor', 0, 0, 'section_procurement'];
    $return['contractor_address'] = ['Contractor Address', 'contractor', 'contractor_address', 0, 0, 'section_procurement'];
    $return['authorized_person'] = ['Authorized Person', 'contractor', 'authorized_person', 0, 0, 'section_procurement'];
    $return['authorized_person_email'] = ['Authorized Person Email', 'contractor', 'authorized_person_email', 0, 0, 'section_procurement'];
    $return['authorized_person_phone'] = ['Authorized Person Phone', 'contractor', 'authorized_person_phone', 0, 0, 'section_procurement'];
    $return['expenditure_topic_id'] = ['Expenditure Topic', 'expenditure_topic', 'expenditure_head', 0, 0, 'section_budget_expenditure'];
    $return['ho_date'] = ['Hand Over Date', '', '', 0, 0, 'section_procurement'];
    $return['estimated_amount'] = ['Initial Cost Estimate', 'procurement', 'estimated_amount', 0, 0, 'section_procurement'];
    $return['wo_date'] = ['Work order Date', 'procurement', 'wo_date', 0, 0, 'section_procurement'];
    $return['contract_id_div'] = ['Contract Id (Division)', 'procurement', 'con_id_div', 0, 0, 'section_procurement'];
    $return['contract_id_web'] = ['Contract Id (World bank)', 'procurement', 'con_id_web', 0, 0, 'section_procurement'];
    $return['contingency'] = ['Contingency', 'contractor', 'contingency', 0, 0, 'section_procurement'];


    $return['budget_topic_id'] = ['Budget Topic', 'budget_topic', 'budget_topic_num', 0, 0, 'section_budget_expenditure'];
    $return['allocations'] = ['Current Allocation', '', 'allocations', 0, 0, 'section_budget_expenditure'];
    $return['budget_topic_priority'] = ['Budget Priority', 'budget_topic', 'priority', 0, 0, 'section_budget_expenditure'];
    $return['expenditure_till_last_fy'] = ['Expenditure Till Last Fiscal Fy', '', 'expenditure_till_last_fy', 0, 0, 'section_budget_expenditure'];
    $return['total_expenditure'] = ['Total Expenditure', '', 'total_expenditure', 0, 0, 'section_budget_expenditure'];

    $return['current_physical_progress'] = ['Current physical Progress', '', 'current_physical_progress', 0, 0, 'section_progress'];
    $return['project_remarks'] = ['Progress Narration', 'progresses', 'project_remarks', 0, 1, 'section_progress'];
    $return['progress_track'] = ['Progress Track', 'progresses', 'progress_eng', 0, 1, 'section_progress'];
    $return['monetary_progress'] = ['Monetary Progress', '', '', 0, 0, 'section_progress', 'section_progress'];
    $return['state'] = ['State', '', '', 0, 0, 'section_project', 'section_project'];
    return $return;

    //  $return['field_name_on_my_table'] = ['Label','model','field',is_compulsory,has_many,'which_section_in_view'];
}

function listProgressStatusReport($child, $fy_id, $implementingOffice, $till_now, $running = 0, $budget_topic)
{
    $total = (\PMIS\Project::where('project_group_id', $child->id)->where('budget_topic_id', $budget_topic));
    if ($till_now == 1) {
        $total = $total->where('start_fy_id', '<=', $fy_id);
    } else {
        $total = $total->where('start_fy_id', $fy_id);
    }
    if ($implementingOffice != 0) {
        $total = $total->where('implementing_office_id', $implementingOffice);
    }
    if ($running == 1) {
        // show on running =1
        $total = $total->where('show_on_running', '1');
    } elseif ($running == 2) {
        // Terminated
        $total = $total->where('cancelled', '1');
    } elseif ($running == 3) {
        // cancelled
        $total = $total->onlyTrashed();
    }
    return $total;
}

function listProgressStatusReport_v2($child, $fy_id, $implementingOffice, $till_now, $running = 0, $budget_topic)
{
    $total = (\PMIS\Project::where('project_group_id', $child->id)->whereHas('projectSettings', function($setting) use ($budget_topic){
        $setting->where('fy', session()->get('pro_fiscal_year'))
            ->where('budget_id', $budget_topic);
    }));
        /*->join('pro_project_settings', 'pro_project_settings.project_id', '=', 'pro_projects.id')->select('pro_projects.*', 'pro_project_settings.id as setting_id', 'pro_project_settings.fy', 'pro_project_settings.budget_id', 'pro_project_settings.expenditure_id', 'pro_project_settings.implementing_id', 'pro_project_settings.monitoring_id', 'pro_project_settings.code')
        ->where('fy', session()->get('pro_fiscal_year'))
        ->where('budget_id', $budget_topic));*/
    if ($till_now == 1) {
        $total = $total->where('start_fy_id', '<=', $fy_id);
    } else {
        $total = $total->where('start_fy_id', $fy_id);
    }
    if ($implementingOffice != 0) {
        $total = $total->where('implementing_id', $implementingOffice);
    }
    if ($running == 1) {
        // show on running =1
        $total = $total->where('show_on_running', '1');
    } elseif ($running == 2) {
        // Terminated
        $total = $total->where('cancelled', '1');
    } elseif ($running == 3) {
        // cancelled
        $total = $total->onlyTrashed();
    }
    return $total;
}


function crud()
{
    $return[0] = "Created";
    $return[1] = "Updated";
    $return[2] = "Deleted";
    $return[3] = "Restored";
    return $return;
}

function logDescriptionCreate($requests)
{
    $description = "<table>";
    foreach ($requests as $index => $item) {
        if ($index != 'updated_at' && $index != '_token' && $index != '_method') {
            if ($item != '') {
                if (!is_array($item)) {
                    $description .= "<tr>";
                    $description .= "<td>" . $index . "</td>";
                    $description .= "<td>" . $item . "</td>";
                    $description .= "</tr>";
                }
            }
        }
    }
    $description .= "</table>";
    return $description;
}

function logDescriptionUpdate($updateObject, $oldObjectArray)
{
    $iteration = 0;
    $change = '<table> <tr> <th>Field Name</th> <th> Old Value </th> <th> New Value</th></tr>';
    foreach ($updateObject->getAttributes() as $index => $attribute) {
        if ($index != 'updated_at' && $index != '_token' && $index != '_method' && $index != 'slug') {
            if ($updateObject->$index != $oldObjectArray[$index]) {
                if (!is_array($attribute)) {
                    $iteration++;
                    $change .= '<tr>';
                    $change .= '<td>' . $index . '</td>';
                }
                $change .= '<td>' . $oldObjectArray[$index] . '</td>';
                $change .= '<td>' . $updateObject->$index . '</td>';
                $change .= '</tr>';
            }
        }
    }
    $change .= '</table>';
    if ($iteration == 0) {
        return false;
    }
    return $change;
}


function logPivot($modelName, $newPivot, $title)
{
    $change = '<table> <tr> <th> ' . $title . ' for ' . $modelName . ' </th></tr>';
    foreach ($newPivot as $index => $attribute) {
        $change .= '<tr>';
        $change .= '<td>' . $attribute . '</td>';
        $change .= '</tr>';
    }
    $change .= '</table>';
    return $change;

}

function storeLog($id, $change, $status, $baseModel, $apiTokenUserId = null)
{
    $creator = Auth::User() ? Auth::User()->id : $apiTokenUserId;
    ProLog::create([
        'user_id' => $creator,
        'project_id' => $id,
        'description' => $change,
        'status' => $status,
        'base_model' => $baseModel,
    ]);
}


function getProjectCode($implementing_office, $monitoringOffice_id, $explode = false)
{

    $monitoring = ImplementingOffice::find($monitoringOffice_id);
    $io = ImplementingOffice::find($implementing_office);
    $tempCode = $monitoring->id . ':' . $io->piu_code . "-" . $io->office_code;

    // do {
    //     $itsId = $count++;
    //     $itsIdFourDigit = sprintf("%'.04d", $itsId);
    //     $finalCode = $tempCode . "-" . $itsIdFourDigit;
    // } while (Project::withTrashed()->join('pro_project_settings', 'pro_project_settings.project_id', '=', 'pro_projects.id')->select('pro_projects.*', 'pro_project_settings.id as setting_id', 'pro_project_settings.budget_id', 'pro_project_settings.budget_id', 'pro_project_settings.fy', 'pro_project_settings.budget_id', 'pro_project_settings.expenditure_id', 'pro_project_settings.implementing_id', 'pro_project_settings.monitoring_id','pro_project_settings.code')->whereIn('pro_project_settings.code', $finalCode)->first() instanceof Project);
    
    $queryCode = $io->piu_code . "-" . $io->office_code;
    $codes = [];
    foreach(ProjectSetting::where('code', 'like', '%'.$queryCode.'%')->get() as $project) array_push($codes, $project->code);
    foreach(Project::where('project_code', 'like', '%'.$queryCode.'%')->get() as $project) array_push($codes, $project->project_code);
    foreach(OldProjectCode::where('old_project_code','like', '%'.$queryCode.'%')->get() as $old_project_code) array_push($codes, $old_project_code);

    if($codes){
        $last_digits = [];
        foreach($codes as $code){
            $digit =  explode('-',$code);
            array_push($last_digits,intval($digit[2]));
        }

        $max = max($last_digits);
        $itsIdFourDigit = sprintf("%'.04d", $max+1);
        $finalCode = $tempCode . "-" . $itsIdFourDigit;
    }else{
        $itsIdFourDigit = '0001';
        $finalCode = $tempCode . "-" . $itsIdFourDigit;
    }

    if ($explode)
        return $io->piu_code . "-" . $io->office_code . '-' . $itsIdFourDigit;

    return $finalCode;


//
//
//    dd("this should be updated with commented code above !!!");
//
//    $implementing_office = ImplementingOffice::find($implementing_office);
//    $officeCode = sprintf("%02d", $implementing_office->office_code); // make 5 as 05
//
//    $project_id = Project::whereImplementingOfficeId($implementing_office->id)->where('project_code','like',$monitoringOffice_id.'-'.$officeCode.'-%')->withTrashed()->max('project_code');
//
//    if($project_id==null){
//        $project_id = $monitoringOffice_id.'-00-00';
//    }
//
//    $project_ids = explode("-", $project_id);
//
//    $project_ids = $project_ids[2]+1;
//
//    $return['project_code']= $officeCode;
//
//
//    $return['project_code'].='-'.sprintf("%03d",$project_ids);
//    if($explode==true){
//        return $return['project_code'];
//    }
//
//    $return['project_code']=$monitoringOffice_id.'-'.$return['project_code'];
//    return $return['project_code'];
}


function projectCost($project)
{
    // in case some one is calling this function, it was re-written !!!!
    return $project->projectCost();
}

function getYearRange($fiscalYear)
{
    // fy object expected
    $arrayDate = explode("-", $fiscalYear->fy);
    $date = array();
    $date[] = $arrayDate[0] . '-03-31';
    $date[] = '2' . $arrayDate[1] . '-04-01';
    return $date;
}

function getFyFromTo($from_id, $to_id){
    $fy =  Fiscalyear::where('id', '>=', $from_id)->where('id','<=',$to_id)->whereStatus(1)->pluck('fy','id');
    return $fy;
}
function getFyDetail($fy_id){
    $fy =  Fiscalyear::where('id',$fy_id)->whereStatus(1)->pluck('fy','id');
    return $fy;
}

function getFy($date)
{
    if ($date == '0000-00-00') {
        return '-';
    }
    try{
        $arrayDate = explode("-", $date);// returns "f"
        $ficalYear = $arrayDate[0] . '-' . intval($arrayDate[0] + 1);
    }catch(\Exception $e){
        return null;
    }
    if (intval($arrayDate[1]) <= 3) {
        $ficalYear = intval($arrayDate[0] - 1) . '-' . $arrayDate[0];
    }
    $ficalYear = explode("-", $ficalYear);
    $ficalYear = $ficalYear[0] . '-' . substr($ficalYear[1], 1);
    return $ficalYear;

}

function getFyId($date){
    $fy = getFy($date);
    $fyId = Fiscalyear::where('fy',$fy)->first();
    if($fyId) return $fyId->id;
}

function getFiscalyearFromDate($date, $separator = "/")
{
    if ($date == '0000-00-00') {
        return '-';
    }
    $arrayDate = explode("-", $date);
    $fiscalYear = $arrayDate[0] . $separator . substr(intval($arrayDate[0] + 1), 1, 3);

    if (intval($arrayDate[1]) <= 3) {
        $fiscalYear = intval($arrayDate[0] - 1) . $separator . substr($arrayDate[0], 1, 3);
    }
    return $fiscalYear;
}

function fiscalYearToday()
{
    return \PMIS\Fiscalyear::fyToday();
}


function getRomanValue()
{
    $return[1] = "I";
    $return[2] = "II";
    $return[3] = "III";
    $return[4] = "IV";
    $return[5] = "V";
    $return[6] = "VI";
    $return[7] = "VII";
    $return[8] = "VIII";
    $return[9] = "IX";
    $return[10] = "X";
    $return[11] = "XI";
    $return[12] = "XII";
    $return[13] = "XIII";
    $return[14] = "XIV";
    $return[15] = "XV";
    return $return;

}


function androidUnAuthorized()
{
    $return[1] = 342;
//    $return[2] = 343;
    $return[3] = 410;
    $return[4] = 380;
    //    $return[2] = 341;
//    $return[3] = 397;
    return $return;
}



function progressActivityLogsTypes()
{
    $return['project'][0] = 'Project (कार्यक्रम)';
    $return['project'][1] = 'Variation (भेरियसन)';
    $return['project'][2] = 'Progress Photo (प्रगति फोटो)';
    $return['project'][3] = 'Liquidated Damage (हर्जाना)';
    $return['project'][4] = 'Time Extension (म्याद थप)';
    $return['project'][5] = 'Expenditure Related (खर्चसंग सम्बन्धित)';
    $return['project'][6] = 'Hand Over Form (हस्तान्तरण फारम)';
    $return['project'][7] = 'Application (निबेदन)';
    $return['project'][8] = 'PB Letter (कार्य सम्पादन जमानत पत्र)';
    $return['project'][9] = 'APG Letter (अग्रिम भुक्तानी जमानत पत्र)';
    $return['project'][10] = 'Insurance Letter (इन्स्योरेन्स पत्र)';
    $return['project'][11] = 'Drawing (नक्सा)';
    $return['project'][12] = 'Land ownership (जग्गाको स्वमित्व)';
    $return['project'][13] = 'Firm Document (फर्मसंग सम्बन्धित कागजात)';
    /*    $return['project'][14] = 'Approval Of Cost Estimate';*/
    $return['project'][15] = 'Tender Notice (बोलपत्र सूचना)';
    $return['project'][16] = 'Approved Cost Estimate (स्वीकृत ल.इ - Summary Sheet)';
    $return['project'][17] = 'मुल्यांकन सम्पन्न मिति';
    $return['project'][18] = 'No Objection1';
    $return['project'][19] = 'No Objection2';
    $return['project'][20] = 'Agreement (सम्झौता)';
    $return['project'][21] = 'Price Escalation (मूल्य समायोजन)';
    $return['project'][22] = 'Bonus (बोनस)';
    $return['project'][23] = 'अन्य';
    $return['implementing_office'][23] = 'अन्य';
    $return['implementing_office'][24] = 'अखितियारी';
    $return['implementing_office'][25] = 'पत्र';
    $return['implementing_office'][26] = 'स्वीकृत कार्यक्रम';
    $return['project'][27] = 'JV Agreement ';
    $return['project'][28] = 'बोलपत्र स्वीकृत मिति';
    $return['project'][29] = 'Work Order (कार्यादेश)';
    $return['other'][30] = 'Feedback (प्रतिक्रिया)';
    $return['project'][31] = 'Tender Open (वोलपत्र खुलेको)';
    $return['project'][32] = 'Soil Test Report (माटो परीक्षण रिपोर्ट)';
    $return['project'][33] = 'Bank Guarantee (बैंक ग्यारेन्टी खिचिएको सम्बन्धि)';
    $return['project'][34] = 'कालोसूचीमा राखिएको / ठेक्का तोडिएको';
    $return['project'][35] = 'नि. का. सम्पन्न गराउने ताकेता';
    $return['project'][36] = 'Public Notices (सार्वजनिक सुचनाहरु)';
    $return['project'][37] = 'Document (कागत पत्र)';
    $return['other'][38] = 'Blog (कागत पत्र)';
    $return['project'][39] = 'BOQ';
    $return['project'][40] = 'Work Schedule';
    $return['project'][41] = 'Incomming Letter';
    $return['project'][42] = 'Outgoing Letter';
    $return['project'][43] = 'QAP';
    $return['project'][44] = 'Lab Report';
    $return['block'][45] = 'Photo Progress';
    $return['block'][46] = 'Drawing';
    $return['block'][47] = 'Other';

    return $return;
}

function fieldsThatCanHaveActivityLogs()
{
    $return[6] = 'ho_date';
    $return[11] = 'design_est_swikrit_miti';
    $return[12] = 'swamittwo';
    $return[15] = 'call_for_bid_act';
    $return[17] = 'bid_eval_act';
    $return[18] = 'no_obj_act1';
    $return[19] = 'no_obj_act2';
    $return[20] = 'contract_date';
    $return[27] = 'bolapatraswikriti';
    $return[29] = 'wo_date';
    $return[31] = 'bid_open_act';
    $return[32] = 'soiltest';
    return $return;
}

function procurementDates()
{
    $return[1] = 'PB';
    $return[2] = 'APG';
    $return[3] = 'Insurance';
    return $return;
}


function seeDetail($pluck, $id)
{
    if (in_array($id, $pluck->pluck('id')->toArray())) {
        return true;
    }
    abort(403);
}

function restrictToImplementingOffice($action = null)
{
    if (optional(optional(Auth::User())->implementingOffice)->isMonitoring != 1 || optional(Auth::User())->access !== 'Root Level') {
        // recevies information to abort or display none (from $action)
        if ($action == 'abort') {
            abort(403);
        }
        return "style=display:none";
    }
    return true;
}


function allowEdit($object)
{
    return true;
    if (Auth::User()->implementingOffice->isMonitoring != 1 && Auth::User()->access != 'Root Level') {
        $cDate = Carbon::parse($object->created_at);
        if ($cDate->diffInDays() > 3) {
            return false;
        }
    }
    return true;
}

function documentCategory()
{
    return [
        1 => 'Documents',
        'Others',
        // more to add
    ];
}

function projectCancelledReasons()
{
    return [
        1 => ['title' => 'Contract Break', 'title_nepali' => 'ठेक्का तोडिएको'],
        2 => ['title' => 'Office Changes', 'title_nepali' => 'अफिस परिवर्तन'],
        3 => ['title' => 'Project Dropped', 'title_nepali' => 'कार्यक्रम अघि बढ्न नसक्ने']
    ];
}


function notifiableDays()
{
    return array(25,35);
}


function filterObjectsToImplementingOffice($object)
{
    $user = Auth::user();
    if ($user->implementing_office_id != 1) {
        $object = $object->where('monitoring_office_id', $user->implementing_office_id);
        if ($object->count() == 0) {
            $object = $object->where('implementing_office_id', $user->implementing_office_id);
        }
    }
    return $object;
}


function seeGivenFyAndReturnIfItRequiresOldFyorNew()
{
    $return = null;
    if ((request()->acceptsJson() && request()->has('fy_id') && request()->get('fy_id') <= 15) || (session()->has('pro_fiscal_year') && session()->get('pro_fiscal_year') <= 15)) {
        $return = '_old';
    }
    return $return;
}

function piuOfficesSelectList($implementing_offices_new_update, $selected_implementing_office_id = null, $name = 'implementing_office', $multiple = null)
{
    $return = '<select name="' . $name . '" id="'.$name.'" class="form-control" ' . $multiple . '>';
    $return .= '<option value="null" disabled selected>Select Implementing Office</option>';
    $return .= '<option value="null">Any</option>';
    foreach ($implementing_offices_new_update as $implementing_ofc) {
        if (count($implementing_ofc->child)) {
            $return .= '<optgroup label="' . $implementing_ofc->getOriginal()['name_eng'] . '">';
            foreach ($implementing_ofc->child as $child_ofc) {
                $selected = '';
                if ($selected_implementing_office_id == $child_ofc->id) {
                    $selected = 'selected';
                }
                $return .= '<option value="' . $child_ofc->id . '" ' . $selected . '>' . $child_ofc->getOriginal()['name'] . '</option>';
            }
            $return .= '</optgroup>';
        } else {
            $selected = '';
            if ($selected_implementing_office_id == $implementing_ofc->id) {
                $selected = 'selected';
            }
            $return .= '<option value="' . $implementing_ofc->id . '" ' . $selected . '>' . $implementing_ofc->getOriginal()['name'] . '</option>';
        }
    }
    $return .= '</select>';
    return $return;
}

function formatcurrency($floatcurr, $curr = "NPR")
{
    $currencies['ARS'] = array(2, ',', '.');          //  Argentine Peso
    $currencies['AMD'] = array(2, '.', ',');          //  Armenian Dram
    $currencies['AWG'] = array(2, '.', ',');          //  Aruban Guilder
    $currencies['AUD'] = array(2, '.', ' ');          //  Australian Dollar
    $currencies['BSD'] = array(2, '.', ',');          //  Bahamian Dollar
    $currencies['BHD'] = array(3, '.', ',');          //  Bahraini Dinar
    $currencies['BDT'] = array(2, '.', ',');          //  Bangladesh, Taka
    $currencies['BZD'] = array(2, '.', ',');          //  Belize Dollar
    $currencies['BMD'] = array(2, '.', ',');          //  Bermudian Dollar
    $currencies['BOB'] = array(2, '.', ',');          //  Bolivia, Boliviano
    $currencies['BAM'] = array(2, '.', ',');          //  Bosnia and Herzegovina, Convertible Marks
    $currencies['BWP'] = array(2, '.', ',');          //  Botswana, Pula
    $currencies['BRL'] = array(2, ',', '.');          //  Brazilian Real
    $currencies['BND'] = array(2, '.', ',');          //  Brunei Dollar
    $currencies['CAD'] = array(2, '.', ',');          //  Canadian Dollar
    $currencies['KYD'] = array(2, '.', ',');          //  Cayman Islands Dollar
    $currencies['CLP'] = array(0, '', '.');           //  Chilean Peso
    $currencies['CNY'] = array(2, '.', ',');          //  China Yuan Renminbi
    $currencies['COP'] = array(2, ',', '.');          //  Colombian Peso
    $currencies['CRC'] = array(2, ',', '.');          //  Costa Rican Colon
    $currencies['HRK'] = array(2, ',', '.');          //  Croatian Kuna
    $currencies['CUC'] = array(2, '.', ',');          //  Cuban Convertible Peso
    $currencies['CUP'] = array(2, '.', ',');          //  Cuban Peso
    $currencies['CYP'] = array(2, '.', ',');          //  Cyprus Pound
    $currencies['CZK'] = array(2, '.', ',');          //  Czech Koruna
    $currencies['DKK'] = array(2, ',', '.');          //  Danish Krone
    $currencies['DOP'] = array(2, '.', ',');          //  Dominican Peso
    $currencies['XCD'] = array(2, '.', ',');          //  East Caribbean Dollar
    $currencies['EGP'] = array(2, '.', ',');          //  Egyptian Pound
    $currencies['SVC'] = array(2, '.', ',');          //  El Salvador Colon
    $currencies['ATS'] = array(2, ',', '.');          //  Euro
    $currencies['BEF'] = array(2, ',', '.');          //  Euro
    $currencies['DEM'] = array(2, ',', '.');          //  Euro
    $currencies['EEK'] = array(2, ',', '.');          //  Euro
    $currencies['ESP'] = array(2, ',', '.');          //  Euro
    $currencies['EUR'] = array(2, ',', '.');          //  Euro
    $currencies['FIM'] = array(2, ',', '.');          //  Euro
    $currencies['FRF'] = array(2, ',', '.');          //  Euro
    $currencies['GRD'] = array(2, ',', '.');          //  Euro
    $currencies['IEP'] = array(2, ',', '.');          //  Euro
    $currencies['ITL'] = array(2, ',', '.');          //  Euro
    $currencies['LUF'] = array(2, ',', '.');          //  Euro
    $currencies['NLG'] = array(2, ',', '.');          //  Euro
    $currencies['PTE'] = array(2, ',', '.');          //  Euro
    $currencies['GHC'] = array(2, '.', ',');          //  Ghana, Cedi
    $currencies['GIP'] = array(2, '.', ',');          //  Gibraltar Pound
    $currencies['GTQ'] = array(2, '.', ',');          //  Guatemala, Quetzal
    $currencies['HNL'] = array(2, '.', ',');          //  Honduras, Lempira
    $currencies['HKD'] = array(2, '.', ',');          //  Hong Kong Dollar
    $currencies['HUF'] = array(0, '', '.');           //  Hungary, Forint
    $currencies['ISK'] = array(0, '', '.');           //  Iceland Krona
    $currencies['INR'] = array(2, '.', ',');          //  Indian Rupee
    $currencies['IDR'] = array(2, ',', '.');          //  Indonesia, Rupiah
    $currencies['IRR'] = array(2, '.', ',');          //  Iranian Rial
    $currencies['JMD'] = array(2, '.', ',');          //  Jamaican Dollar
    $currencies['JPY'] = array(0, '', ',');           //  Japan, Yen
    $currencies['JOD'] = array(3, '.', ',');          //  Jordanian Dinar
    $currencies['KES'] = array(2, '.', ',');          //  Kenyan Shilling
    $currencies['KWD'] = array(3, '.', ',');          //  Kuwaiti Dinar
    $currencies['LVL'] = array(2, '.', ',');          //  Latvian Lats
    $currencies['LBP'] = array(0, '', ' ');           //  Lebanese Pound
    $currencies['LTL'] = array(2, ',', ' ');          //  Lithuanian Litas
    $currencies['MKD'] = array(2, '.', ',');          //  Macedonia, Denar
    $currencies['MYR'] = array(2, '.', ',');          //  Malaysian Ringgit
    $currencies['MTL'] = array(2, '.', ',');          //  Maltese Lira
    $currencies['MUR'] = array(0, '', ',');           //  Mauritius Rupee
    $currencies['MXN'] = array(2, '.', ',');          //  Mexican Peso
    $currencies['MZM'] = array(2, ',', '.');          //  Mozambique Metical
    $currencies['NPR'] = array(2, '.', ',');          //  Nepalese Rupee
    $currencies['ANG'] = array(2, '.', ',');          //  Netherlands Antillian Guilder
    $currencies['ILS'] = array(2, '.', ',');          //  New Israeli Shekel
    $currencies['TRY'] = array(2, '.', ',');          //  New Turkish Lira
    $currencies['NZD'] = array(2, '.', ',');          //  New Zealand Dollar
    $currencies['NOK'] = array(2, ',', '.');          //  Norwegian Krone
    $currencies['PKR'] = array(2, '.', ',');          //  Pakistan Rupee
    $currencies['PEN'] = array(2, '.', ',');          //  Peru, Nuevo Sol
    $currencies['UYU'] = array(2, ',', '.');          //  Peso Uruguayo
    $currencies['PHP'] = array(2, '.', ',');          //  Philippine Peso
    $currencies['PLN'] = array(2, '.', ' ');          //  Poland, Zloty
    $currencies['GBP'] = array(2, '.', ',');          //  Pound Sterling
    $currencies['OMR'] = array(3, '.', ',');          //  Rial Omani
    $currencies['RON'] = array(2, ',', '.');          //  Romania, New Leu
    $currencies['ROL'] = array(2, ',', '.');          //  Romania, Old Leu
    $currencies['RUB'] = array(2, ',', '.');          //  Russian Ruble
    $currencies['SAR'] = array(2, '.', ',');          //  Saudi Riyal
    $currencies['SGD'] = array(2, '.', ',');          //  Singapore Dollar
    $currencies['SKK'] = array(2, ',', ' ');          //  Slovak Koruna
    $currencies['SIT'] = array(2, ',', '.');          //  Slovenia, Tolar
    $currencies['ZAR'] = array(2, '.', ' ');          //  South Africa, Rand
    $currencies['KRW'] = array(0, '', ',');           //  South Korea, Won
    $currencies['SZL'] = array(2, '.', ', ');         //  Swaziland, Lilangeni
    $currencies['SEK'] = array(2, ',', '.');          //  Swedish Krona
    $currencies['CHF'] = array(2, '.', '\'');         //  Swiss Franc
    $currencies['TZS'] = array(2, '.', ',');          //  Tanzanian Shilling
    $currencies['THB'] = array(2, '.', ',');          //  Thailand, Baht
    $currencies['TOP'] = array(2, '.', ',');          //  Tonga, Paanga
    $currencies['AED'] = array(2, '.', ',');          //  UAE Dirham
    $currencies['UAH'] = array(2, ',', ' ');          //  Ukraine, Hryvnia
    $currencies['USD'] = array(2, '.', ',');          //  US Dollar
    $currencies['VUV'] = array(0, '', ',');           //  Vanuatu, Vatu
    $currencies['VEF'] = array(2, ',', '.');          //  Venezuela Bolivares Fuertes
    $currencies['VEB'] = array(2, ',', '.');          //  Venezuela, Bolivar
    $currencies['VND'] = array(0, '', '.');           //  Viet Nam, Dong
    $currencies['ZWD'] = array(2, '.', ' ');          //  Zimbabwe Dollar

    if ($curr == 'NPR') {
        $input = $floatcurr;
        $dec = "";
        $pos = strpos($input, ".");
        if ($pos === false) {
            //no decimals
        } else {
            //decimals
            $dec = substr(round(substr($input, $pos), 2), 1);
            $input = substr($input, 0, $pos);
        }
        $num = substr($input, -3); //get the last 3 digits
        $input = substr($input, 0, -3); //omit the last 3 digits already stored in $num
        while (strlen($input) > 0) //loop the process - further get digits 2 by 2
        {
            $num = substr($input, -2) . "," . $num;
            $input = substr($input, 0, -2);
        }
        return $num . $dec;
    } else {
        return number_format($floatcurr, $currencies[$curr][0], $currencies[$curr][1], $currencies[$curr][2]);
    }
}

function multiyearProcurementData($budget_topic)
{
    switch ($budget_topic) {
        case 8:
            return [
                ['year' => '2074-075', 'value' => 13022908],
                ['year' => '2075-076', 'value' => '0'],
                ['year' => '2076-077', 'value' => '0'],
                ['year' => '2077-078', 'value' => '0']
            ];
            break;
        case 9:
            return [
                ['year' => '2074-075', 'value' => 157550],
                ['year' => '2075-076', 'value' => 477504],
                ['year' => '2076-077', 'value' => 293054],
                ['year' => '2077-078', 'value' => 181502]
            ];
            break;
        case 34:
            return [
                ['year' => '2073-074', 'value' => 3014420],
                ['year' => '2074-075', 'value' => 13022908],
                ['year' => '2075-076', 'value' => 7404517],
                ['year' => '2076-077', 'value' => 3030418]
            ];
            break;
        case 35:
            return [
                ['year' => '2073-074', 'value' => 3014420],
                ['year' => '2074-075', 'value' => 13022908],
                ['year' => '2075-076', 'value' => 7404517],
                ['year' => '2076-077', 'value' => 3030418]
            ];
        case 36:
            return [
                ['year' => '2074-075', 'value' => 157550],
                ['year' => '2075-076', 'value' => 477504],
                ['year' => '2076-077', 'value' => 293054],
                ['year' => '2077-078', 'value' => 181502]
            ];
        default:
            return [
                ['year' => '2073-074', 'value' => 0],
                ['year' => '2074-075', 'value' => 0],
                ['year' => '2075-076', 'value' => 0],
                ['year' => '2076-077', 'value' => 0]
            ];
    }

}

function contingencyPercentages(){
    return [
        4=>4,
        5=>5
    ];
}


function integerToRoman($integer)
{
 // Convert the integer into an integer (just to make sure)
 $integer = intval($integer);
 $result = '';
 
 // Create a lookup array that contains all of the Roman numerals.
 $lookup = array('M' => 1000,
 'CM' => 900,
 'D' => 500,
 'CD' => 400,
 'C' => 100,
 'XC' => 90,
 'L' => 50,
 'XL' => 40,
 'X' => 10,
 'IX' => 9,
 'V' => 5,
 'IV' => 4,
 'I' => 1);
 
 foreach($lookup as $roman => $value){
  // Determine the number of matches
  $matches = intval($integer/$value);
 
  // Add the same number of characters to the string
  $result .= str_repeat($roman,$matches);
 
  // Set the integer to be the remainder of the integer and the value
  $integer = $integer % $value;
 }
 
 // The Roman numeral should be built, return it
 return $result;
}

function manpowerTypes(){
    return [
        '1' => 'Client',
        '2' => 'Consultant',
        '3' => 'Contractor',
    ];
}

function times(){
    return [
        1=>'प्रथम',
        'द्वितीय',
        'तृतिय',
        'चौथो',
        'पाचौं',
        'छठौं',
        'सातौ',
        'आठौ',
        'नवौ',
        'दसौ',

    ];
}

function json_encode_decode($data){
    return json_decode(json_encode($data, JSON_UNESCAPED_UNICODE),true);
}

function restrictEngineers($typeFlag){
    if($typeFlag === 5){
        abort(403);
    }
}