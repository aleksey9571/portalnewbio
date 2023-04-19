<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Sklad_nomenclature_categories;
use App\Models\Sklad_nomenclature;
use App\Http\Controllers\Nomenclature;

class Kernel extends ConsoleKernel{
    protected function schedule(Schedule $schedule) {
        // $schedule->('inspire')->hourly();
        $schedule->call(
            function () {
                $inputFileName = './uploads/nomenclature (XLS).xls';
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($inputFileName);
                for ($i = 2; $i <= $spreadsheet->getActiveSheet()->getHighestRow(); $i++){
                    $nomenclature_now_categories = new Sklad_nomenclature_categories();
                    $nomenclature_now = new Sklad_nomenclature();
                    $A = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $i)->getValue();
                    $B = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(2, $i)->getValue();
                    $C = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(3, $i)->getValue();
                    $D = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(4, $i)->getValue();
                    if ($nomenclature_now_categories->where('name', '=', $A)->first() == null){
                        $nomenclature_now_categories->name = $A;
                        $nomenclature_now_categories->save(); 
                    }
                    if ($nomenclature_now->where('key', '=', $D)->first() == null){
                        if ($C != null){
                            $nomenclature_now->categorie_id = $nomenclature_now_categories->where('name', '=', $A)->select('id')->first()->id;
                            $nomenclature_now->name = $B;
                            $nomenclature_now->unit = $C;
                            $nomenclature_now->key = $D;
                            $nomenclature_now->save();
                        }
                    }else{
                        break;
                    }
                }
            }
        )->everyMinute()->runInBackground()->evenInMaintenanceMode();
    }
}
