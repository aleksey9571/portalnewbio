<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\Nomenclature_add_mail;
use App\Models\Sklad_now;
use App\Models\Sklad_now_storage;
use App\Models\Sklad_now_categories;
use App\Models\Sklad_now_historys;
use App\Models\Sklad_now_historys_items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDF;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Sklad extends Controller{

    public function show_all(){
        $storage = Sklad_now_storage::where('user_id', '=', Auth::id())->select('product_id')->get();
        $now = Sklad_now::orderBy('key', 'desc')->get();
        $pages = $now->count();
        $i = 0;
        foreach ($now as $elnow){
            foreach ($storage as $elstorage){
                if($elnow->id == $elstorage->product_id){
                    $now->forget($i);
                }
            }
            $i++;   
        }
        $heading = "Полный список";
        return view('sklad/index', ['data'=>$now, 'heading'=>$heading, 'pages'=>intval($pages/100)]);
    }

    public function page($id){
        $storage = Sklad_now_storage::where('user_id', '=', Auth::id())->select('product_id')->get();

        $nows = Sklad_now::orderBy('key', 'desc')->get();
        $pages = $nows->count();
        $now = $nows->skip(($id-1)*100)->take(100);
        $i = 0;
        foreach ($now as $elnow){
            foreach ($storage as $elstorage){
                if($elnow->id == $elstorage->product_id){
                   $now->forget($i);
                   //if($now->search($elstorage->keyproduct_id)){
                       //echo 'нашел<br>';
                   //}
                   
                }
            }
            $i++;
        }
        $heading = "Полный список, страница: ".$id;
        $l = intval($pages/100);
        if ($id == 1){
            $p = [1,2,3,$l];
        }elseif ($id == 2) {
            $p = [1,2,3,4,$l];
        }elseif ($id == 3) {
            $p = [1,2,3,4,5,$l];
        }elseif ($id == $l){
            $p = [1,$l-2,$l-1,$l];
        }elseif ($id == $l-1){
            $p = [1,$l-3,$l-2,$l-1,$l];
        }elseif ($id == $l-2){
            $p = [1,$l-4,$l-3,$l-2,$l-1,$l];
        }else{
            $p = [1,$id-2,$id-1,$id,$id+1,$id+2,$l];
        }
        return view('sklad/index', ['data'=>$now, 'heading'=>$heading, 'pages'=>$p, 'id'=>$id]);
    }

    public function add_storage(Request $req){
        $sklad_now_storage = new Sklad_now_storage();
        $sklad_now_storage->product_id = $req->input('id');
        $sklad_now_storage->user_id = Auth::id();
        $sklad_now_storage->save();
        
        return redirect()->route('sklad', $req->input('page'));
    }

    public function storage(){
        $now = Sklad_now::leftJoin('sklad_now_storages', 'sklad_nows.id', '=', 'sklad_now_storages.product_id')
                ->where('sklad_now_storages.user_id', '=', Auth::id())
                ->get();

        return view('sklad/storage', ['data'=>$now]);
    }

    public function storage_delete(Request $req){
        Sklad_now_storage::where('id', '=', $req->input('id'))->delete();
        
        return redirect()->route('storage');
    }

    public function storage_edit(Request $req){
        $storage = Sklad_now_storage::find($req->id);
        $storage->count = $req->count;
        $storage->save();

        return redirect()->route('storage');
    }

    public function form_send_add_nomenclature(){
        return view('sklad/form_send_add_nomenclature');
    }

    public function send_add_nomenclature(Request $req){
        $name = $req->name;
        $generator = $req->generator;
        $artikul = $req->artikul;
        $message = $req->message;

        Mail::to($req->mail)->send(new Nomenclature_add_mail($name, $generator, $artikul, $message));

        return redirect()->route('sklad', 1);
    }

    public function category(){
        $storage = Sklad_now_categories::orderBy('name')->get();

        return view('sklad/category', ['data'=>$storage]);
    }

    public function setcategory($id){
        $storage = Sklad_now_storage::where('user_id', '=', Auth::id())->select('product_id')->get();
        $now = Sklad_now::where('categorie_id', '=', $id)->get();
        $pages = $now->count();
        $i = 0;
        foreach ($now as $elnow){
            foreach ($storage as $elstorage){
                if($elnow->id == $elstorage->product_id){
                    $now->forget($i);
                }
            }
            $i++;
        }
        $category = Sklad_now_categories::where('id', '=', $id)->select('name')->get();
        foreach ($category as $el){
            $cat = $el->name;
        }
        $p = [1];
        $idd = 1;
        return view('sklad/index', ['data'=>$now, 'heading'=>$cat, 'pages'=>$p, 'id'=>$idd]);
    }

    public function search(Request $req){
        $storage = Sklad_now_storage::where('user_id', '=', Auth::id())->select('product_id')->get();
        $now = Sklad_now::where('key', 'like', '%'.$req->search.'%')->orWhere('name', 'like', '%'.$req->search.'%')->orderBy('key', 'desc')->get();
        $pages = $now->count();
        $i = 0;
        foreach ($now as $elnow){
            foreach ($storage as $elstorage){
                if($elnow->id == $elstorage->product_id){
                    $now->forget($i);
                }
            }
            $i++;
        }
        $heading = "Поиск...";
        $p = [1];
        $id = 1;
        return view('sklad/index', ['data'=>$now, 'heading'=>$heading, 'pages'=>$p, 'id'=>$id]);
    }

    public function skladExcel(){//Вывод Excel формы с корзины
        $spreadsheet = new Spreadsheet();
        $alignment = new \PhpOffice\PhpSpreadsheet\Style\Alignment;

        $borderStyle = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $bold = [
            'font' => [
                'bold' => true,
            ]
        ];

        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);

        $sheet = $spreadsheet->getActiveSheet();        

        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        $sheet->getStyle('A2:A3')->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A2', '_________________________');
        $sheet->setCellValue('A3', '(структурное подразделение)');        

        $sheet->mergeCells('F2:I2');
        $sheet->mergeCells('F3:I3');
        $sheet->mergeCells('F4:I4');
        $sheet->mergeCells('F5:I5');
        $sheet->getStyle('F2:F3')->applyFromArray($bold);
        $sheet->getStyle('F2:F5')->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('F2', 'УТВЕРЖДАЮ');
        $sheet->setCellValue('F3', 'непосредственный руководитель');
        $sheet->setCellValue('F4', '_________________________');
        $sheet->setCellValue('F5', '«___»______________ 20___г.');

        $sheet->mergeCells('C8:G8');
        $sheet->mergeCells('C9:G9');
        $sheet->mergeCells('C10:G10');
        $sheet->getStyle('C8:C10')->applyFromArray($bold);
        $sheet->getStyle('C8:C10')->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('C8', 'ЗАЯВКА');
        $sheet->setCellValue('C9', 'на выдачу материалов со склада');
        $sheet->setCellValue('C10', 'от «___»______________ 20___г.');

        $sheet->mergeCells('A12:I12');
        $sheet->setCellValue('A12', 'Прошу выдать со склада следующие материалы:');
        
        $sheet->mergeCells('B14:E14');
        $sheet->mergeCells('F14:G14');
        $sheet->mergeCells('H14:I14');
        $sheet->getStyle('A14:H14')->applyFromArray($bold);
        $sheet->getStyle('A14:H14')->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A14:H14')->getAlignment()->setVertical($alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A14', '№ п/п');
        $sheet->setCellValue('B14', 'Полное наименование');
        $sheet->setCellValue('F14', 'Ед. измерения');
        $sheet->setCellValue('H14', 'Количество');

        $sheet->getStyle('A14')->applyFromArray($borderStyle);
        $sheet->getStyle('B14')->applyFromArray($borderStyle);
        $sheet->getStyle('C14')->applyFromArray($borderStyle);
        $sheet->getStyle('D14')->applyFromArray($borderStyle);
        $sheet->getStyle('E14')->applyFromArray($borderStyle);
        $sheet->getStyle('F14')->applyFromArray($borderStyle);
        $sheet->getStyle('G14')->applyFromArray($borderStyle);
        $sheet->getStyle('H14')->applyFromArray($borderStyle);
        $sheet->getStyle('I14')->applyFromArray($borderStyle);

        $now = Sklad_now::leftJoin('sklad_now_storages', 'sklad_nows.id', '=', 'sklad_now_storages.product_id')
                ->where('sklad_now_storages.user_id', '=', Auth::id())
                ->get();
        $i = 0;
        $Sklad_now_historys = new Sklad_now_historys();
        $Sklad_now_historys->id_user = Auth::id();
        $Sklad_now_historys->save();
        $last_request = Sklad_now_historys::orderby('id', 'desc')->select('id')->first();
        foreach ($now as $el) {
            $i++;

            $sheet->setCellValue('A'.$i+14, $i);

            $sheet->mergeCells('B'.$i+14 .':E'.$i+14);
            $sheet->setCellValue('B'.$i+14, $el->name);
            $sheet->getCell('B'.$i+14)->getStyle()->getAlignment()->setWrapText(true);

            $sheet->mergeCells('F'.$i+14 .':G'.$i+14);
            $sheet->setCellValue('F'.$i+14, $el->unit);

            $sheet->mergeCells('H'.$i+14 .':I'.$i+14);
            $sheet->setCellValue('H'.$i+14, $el->count);

            $sheet->getStyle('A'.$i+14)->applyFromArray($borderStyle);
            $sheet->getStyle('B'.$i+14)->applyFromArray($borderStyle);
            $sheet->getStyle('C'.$i+14)->applyFromArray($borderStyle);
            $sheet->getStyle('D'.$i+14)->applyFromArray($borderStyle);
            $sheet->getStyle('E'.$i+14)->applyFromArray($borderStyle);
            $sheet->getStyle('F'.$i+14)->applyFromArray($borderStyle);
            $sheet->getStyle('G'.$i+14)->applyFromArray($borderStyle);
            $sheet->getStyle('H'.$i+14)->applyFromArray($borderStyle);
            $sheet->getStyle('I'.$i+14)->applyFromArray($borderStyle);
            $sheet->getStyle('A'.$i+14)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F'.$i+14 .':I'.$i+14)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
            
            //Добавление в таблицу истории
            $Sklad_now_historys_items = new Sklad_now_historys_items();
            $Sklad_now_historys_items->id_req = $last_request->id;
            $Sklad_now_historys_items->key_position = $el->key;
            $Sklad_now_historys_items->name_position = $el->name;
            $Sklad_now_historys_items->quantity_position = $el->quantity;
            $Sklad_now_historys_items->save();
        }

        $this->dropsth(Auth::id());

        $sheet->mergeCells('A'.$i+16 .':C'.$i+16);
        $sheet->getStyle('A'.$i+16)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A'.$i+16, '____________________');

        $sheet->mergeCells('D'.$i+16 .':F'.$i+16);
        $sheet->getStyle('D'.$i+16)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('D'.$i+16, '____________________');

        $sheet->mergeCells('G'.$i+16 .':I'.$i+16);
        $sheet->getStyle('G'.$i+16)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('G'.$i+16, '____________________');

        $sheet->mergeCells('A'.$i+17 .':C'.$i+17);
        $sheet->getStyle('A'.$i+17)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$i+17)->getAlignment()->setVertical($alignment::VERTICAL_TOP);
        $sheet->getCell('A'.$i+17)->getStyle()->getFont()->setSize(8);
        $sheet->setCellValue('A'.$i+17, '(должность заявителя)');

        $sheet->mergeCells('D'.$i+17 .':F'.$i+17);
        $sheet->getStyle('D'.$i+17)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D'.$i+17)->getAlignment()->setVertical($alignment::VERTICAL_TOP);
        $sheet->getCell('D'.$i+17)->getStyle()->getFont()->setSize(8);
        $sheet->setCellValue('D'.$i+17, '(подпись)');

        $sheet->mergeCells('G'.$i+17 .':I'.$i+17);
        $sheet->getStyle('G'.$i+17)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G'.$i+17)->getAlignment()->setVertical($alignment::VERTICAL_TOP);
        $sheet->getCell('G'.$i+17)->getStyle()->getFont()->setSize(8);
        $sheet->setCellValue('G'.$i+17, '(расшифровка)');

        $sheet->mergeCells('A'.$i+19 .':C'.$i+19);
        $sheet->getStyle('A'.$i+19)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A'.$i+19, '____________________');

        $sheet->mergeCells('D'.$i+19 .':F'.$i+19);
        $sheet->getStyle('D'.$i+19)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('D'.$i+19, '____________________');

        $sheet->mergeCells('G'.$i+19 .':I'.$i+19);
        $sheet->getStyle('G'.$i+19)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('G'.$i+19, '____________________');

        $sheet->mergeCells('A'.$i+20 .':C'.$i+20);
        $sheet->getStyle('A'.$i+20)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$i+20)->getAlignment()->setVertical($alignment::VERTICAL_TOP);
        $sheet->getCell('A'.$i+20)->getStyle()->getFont()->setSize(8);
        $sheet->setCellValue('A'.$i+20, '(должность получателя)');

        $sheet->mergeCells('D'.$i+20 .':F'.$i+20);
        $sheet->getStyle('D'.$i+20)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D'.$i+20)->getAlignment()->setVertical($alignment::VERTICAL_TOP);
        $sheet->getCell('D'.$i+20)->getStyle()->getFont()->setSize(8);
        $sheet->setCellValue('D'.$i+20, '(подпись)');

        $sheet->mergeCells('G'.$i+20 .':I'.$i+20);
        $sheet->getStyle('G'.$i+20)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G'.$i+20)->getAlignment()->setVertical($alignment::VERTICAL_TOP);
        $sheet->getCell('G'.$i+20)->getStyle()->getFont()->setSize(8);
        $sheet->setCellValue('G'.$i+20, '(расшифровка)');

        $oWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=\"Заявка на выдачу материалов со склада.xlsx\"");
        header("Cache-Control: max-age=0");
        $oWriter->save('php://output');
    }

    public function skladPdf(){
        $Sklad_now_historys = new Sklad_now_historys();
        $Sklad_now_historys->id_user = Auth::id();
        $Sklad_now_historys->save();
        $last_request = Sklad_now_historys::orderby('id', 'desc')->first();
        $user = User::where('id', '=', Auth::id())->get();
        $now = Sklad_now::leftJoin('sklad_now_storages', 'sklad_nows.id', '=', 'sklad_now_storages.product_id')
                ->where('sklad_now_storages.user_id', '=', Auth::id())
                ->get();
        $i = 0;
        foreach ($now as $el) {
            $Sklad_now_historys_items = new Sklad_now_historys_items();
            $Sklad_now_historys_items->id_req = $last_request->id;
            $Sklad_now_historys_items->key_position = $el->key;
            $Sklad_now_historys_items->name_position = $el->name;
            $Sklad_now_historys_items->quantity_position = $el->quantity;
            $Sklad_now_historys_items->save();
        }
        $pdf = PDF::loadView('pdf.sclad', compact('user', 'last_request', 'now'));
        $pdf->setPaper('A4', 'portrait');
        //return $last_request;
        return $pdf->download('ТМЦ.pdf');
    }

    public function skladGet(){//Импорт базы из Excel
        $sklad_now_categories = new sklad_now_categories();
        $sklad_now = new sklad_now();
        $sklad_now_categories->truncate();
        $sklad_now->truncate();
        $inputFileName = './uploads/sklad (XLS).xls';
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);
        for ($i = 2; $i <= $spreadsheet->getActiveSheet()->getHighestRow()-1; $i++){
            $sklad_now_categories = new sklad_now_categories();
            $sklad_now = new sklad_now();
            $A = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(1, $i)->getValue();
            if ($A == null){
                $A == "ZzZ";
            }
            $B = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(2, $i)->getValue();
            $C = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(3, $i)->getValue();
            $D = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(4, $i)->getValue();
            $E = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(5, $i)->getValue();
            if ($sklad_now_categories->where('name', '=', $A)->first() == null){
                $sklad_now_categories->name = $A;
                $sklad_now_categories->save(); 
            }
            $sklad_now->categorie_id = $sklad_now_categories->where('name', '=', $A)->select('id')->first()->id;
            $sklad_now->name = $B;
            $sklad_now->key = $C;
            $sklad_now->unit = $D;
            $sklad_now->quantity = $E;
            $sklad_now->save();
        }
    }

    public function dropsth($id){
        Sklad_now_storage::where('user_id', '=', $id)->delete();
    }
}