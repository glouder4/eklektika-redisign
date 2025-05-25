<?php
namespace intec\eklectika\excel;


class Controller {

    public static function getArray($sourse) {
        require_once __DIR__."/vendor/autoload.php";
        
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($sourse);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        return $sheetData;        
    }
}


