<?php

namespace App\Http\Controllers;

use App\Mail\Nomenclature_add_mail;
use App\Models\User;
use App\Models\Sklad_nomenclature;
use App\Models\Sklad_nomenclature_storages;
use App\Models\Sklad_nomenclature_categories;
use App\Models\Sklad_nomenclature_historys;
use App\Models\Sklad_nomenclature_historys_items;
use App\Models\Sklad_nomenclature_expls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PDF;

class Nomenclature extends Controller{

    public function show_all(){
        $storage = Sklad_nomenclature_storages::where('user_id', '=', Auth::id())->select('product_id')->get();
        $now = Sklad_nomenclature::orderBy('key', 'desc')->get();
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
        return view('nomenclatures/index', ['data'=>$now, 'heading'=>$heading, 'pages'=>intval($pages/100)]);
    }

    public function page($id){
        $storage = Sklad_nomenclature_storages::where('user_id', '=', Auth::id())->select('product_id')->get();

        $nows = Sklad_nomenclature::orderBy('key', 'desc')->get();
        $pages = $nows->count();
        $now = $nows->skip(($id-1)*100)->take(100);
        //$i = 0;
        //foreach ($now as $elnow){
        //    foreach ($storage as $elstorage){
        //        if($elnow->id == $elstorage->product_id){
        //            $now->forget($i);
        //        }
        //    }
        //    $i++;
        //}
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
        
        return view('nomenclatures/index', ['data'=>$now, 'heading'=>$heading, 'pages'=>$p, 'id'=>$id]);
    }

    public function add_storage(Request $req){
        $nomenclature_now_storage = new Sklad_nomenclature_storages();
        $nomenclature_now_storage->product_id = $req->input('id');
        $nomenclature_now_storage->user_id = Auth::id();
        $nomenclature_now_storage->save();
        
        return redirect()->route('nomenclature', $req->input('page'));
    }

    public function storage(){
        $now = Sklad_nomenclature::leftJoin('sklad_nomenclature_storages', 'sklad_nomenclatures.id', '=', 'sklad_nomenclature_storages.product_id')
                ->where('sklad_nomenclature_storages.user_id', '=', Auth::id())
                ->get();
        $expls = Sklad_nomenclature_expls::where('user_id', '=', Auth::id())
                ->get();
        return view('nomenclatures/storage', ['data'=>$now, 'expls'=>$expls]);
    }

    public function storage_delete(Request $req){
        Sklad_nomenclature_storages::where('id', '=', $req->input('id'))->delete();
        
        return redirect()->route('nomenclature_storage');
    }

    public function storage_edit(Request $req){
        if ($req->has('obosnovaniye')){
            Sklad_nomenclature_storages::where('id', '=', $req->id)->update(['obosnovaniye' => $req->input('obosnovaniye')]);
        }elseif($req->has('statia')) {
            Sklad_nomenclature_storages::where('id', '=', $req->id)->update(['statia' => $req->input('statia')]);
        }elseif($req->has('price')) {
            Sklad_nomenclature_storages::where('id', '=', $req->id)->update(['price' => $req->input('price')]);
        }elseif($req->has('count')) {
            Sklad_nomenclature_storages::where('id', '=', $req->id)->update(['count' => $req->input('count')]);
        }

        return redirect()->route('nomenclature_storage');
    }

    public function form_send_add_nomenclature(){
        return view('nomenclatures/form_send_add_nomenclature');
    }

    public function send_add_nomenclature(Request $req){
        $name = $req->name;
        $generator = $req->generator;
        $artikul = $req->artikul;
        $message = $req->message;

        Mail::to($req->mail)->send(new Nomenclature_add_mail($name, $generator, $artikul, $message));

        return redirect()->route('nomenclature', 1);
    }

    public function category(){
        $storage = Sklad_nomenclature_categories::orderBy('name')->get();

        return view('nomenclatures/category', ['data'=>$storage]);
    }

    public function setcategory($id){
        $storage = Sklad_nomenclature_storages::where('user_id', '=', Auth::id())->select('product_id')->get();
        $now = Sklad_nomenclature::where('categorie_id', '=', $id)->get();
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
        $category = Sklad_nomenclature_categories::where('id', '=', $id)->select('name')->get();
        foreach ($category as $el){
            $cat = $el->name;
        }

        $p = [1];
        $idd = 1;
        return view('nomenclatures/index', ['data'=>$now, 'heading'=>$cat, 'pages'=>$p, 'id'=>$idd]);
    }

    public function search(Request $req){
        $storage = Sklad_nomenclature_storages::where('user_id', '=', Auth::id())->select('product_id')->get();
        $now = Sklad_nomenclature::where('key', 'like', '%'.$req->search.'%')->orWhere('name', 'like', '%'.$req->search.'%')->orderBy('key', 'desc')->get();
        $pages = $now->count();
        //$i = 0;
        //foreach ($now as $elnow){
        //    foreach ($storage as $elstorage){
        //        if($elnow->id == $elstorage->product_id){
        //            $now->forget($i);
        //        }
        //    }
        //    $i++;
        //}
        $heading = "Поиск...";
        $p = [1];
        $id = 1;
        
        return view('nomenclatures/index', ['data'=>$now, 'heading'=>$heading, 'pages'=>$p, 'id'=>$id]);
    }

    public function tmcExcel(){
        $spreadsheet = new Spreadsheet();
        $alignment = new \PhpOffice\PhpSpreadsheet\Style\Alignment;
        $Sklad_nomenclature_historys = new Sklad_nomenclature_historys();
        $Sklad_nomenclature_historys->id_user = Auth::id();
        $Sklad_nomenclature_historys->save();
        $last_request = Sklad_nomenclature_historys::orderby('id', 'desc')->select('id')->first();
        $user = User::where('id', '=', Auth::id())->get();

        $borderStyle = [
            'borders' => [
                'allBorders' => [
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

        $borderBottom = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);

        $sheet = $spreadsheet->getActiveSheet();        

        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('A4:C4');
        $sheet->mergeCells('A5:C5');
        $sheet->getStyle('A2')->applyFromArray($bold);
        $sheet->getStyle('A2:A5')->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A2', 'ИСПОЛНИТЕЛЬ');
        $sheet->setCellValue('A4', '_________________________');
        $sheet->setCellValue('A5', '(структурное подразделение)');        

        $sheet->mergeCells('G2:I2');
        $sheet->mergeCells('G3:I3');
        $sheet->mergeCells('G4:I4');
        $sheet->mergeCells('G5:I5');
        $sheet->mergeCells('G6:I6');
        $sheet->getStyle('G2:G4')->applyFromArray($bold);
        $sheet->getStyle('G2:G6')->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue('G2', 'УТВЕРЖДАЮ');
        $sheet->setCellValue('G3', 'Генеральный директор');
        $sheet->setCellValue('G4', 'ООО «НьюБио»');
        $sheet->setCellValue('G5', '_________________________');
        $sheet->setCellValue('G6', '«___»______________ 20___г.');

        $sheet->mergeCells('C8:G8');
        $sheet->mergeCells('C9:G9');
        $sheet->mergeCells('C10:G10');
        $sheet->getStyle('C8:C10')->applyFromArray($bold);
        $sheet->getStyle('C8:C10')->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('C8', 'ЗАЯВКА №_____');
        $sheet->setCellValue('C9', 'на закупку ТМЦ');
        $sheet->setCellValue('C10', 'от «___»______________ 20___г.');
        foreach ($last_request as $el){
            $sheet->setCellValue('A8', 'КП: '.$last_request->id);
        }
        
        $sheet->mergeCells('B12:E12');
        $sheet->mergeCells('F12:G12');
        $sheet->getStyle('A12:I12')->applyFromArray($bold);
        $sheet->getStyle('A12:I12')->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A12:I12')->getAlignment()->setVertical($alignment::VERTICAL_CENTER);
        $sheet->getStyle('A12:I12')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(12)->setRowHeight(50);
        $sheet->setCellValue('A12', '№ п/п');
        $sheet->setCellValue('B12', 'Наименование продукции или другой безусловный признак идентификации');
        $sheet->setCellValue('F12', 'Нормативная документация на продукцию склада');
        $sheet->setCellValue('H12', 'Ед. измер.');
        $sheet->setCellValue('I12', 'Заявлено (кол-во)');

        $sheet->getStyle('A12:I12')->applyFromArray($borderStyle);

        $now = Sklad_nomenclature::leftJoin('sklad_nomenclature_storages', 'sklad_nomenclatures.id', '=', 'sklad_nomenclature_storages.product_id')
                ->where('sklad_nomenclature_storages.user_id', '=', Auth::id())
                ->get();
        $i = 0;
        foreach ($now as $el) {
            $i++;

            $sheet->setCellValue('A'.$i+12, $i);

            $sheet->mergeCells('B'.$i+12 .':E'.$i+12);
            $sheet->setCellValue('B'.$i+12, $el->key.'; '.$el->name);
            $sheet->getStyle('B'.$i+12)->getAlignment()->setWrapText(true);

            $sheet->mergeCells('F'.$i+12 .':G'.$i+12);
            $sheet->setCellValue('H'.$i+12, $el->unit);

            $sheet->setCellValue('I'.$i+12, $el->count);

            $sheet->getStyle('A'.$i+12 .':I'.$i+12)->applyFromArray($borderStyle);
            $sheet->getStyle('A'.$i+12)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A'.$i+12 .':I'.$i+12)->getAlignment()->setVertical($alignment::VERTICAL_CENTER);
            $sheet->getStyle('F'.$i+12 .':I'.$i+12)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);  
            
            //Добавление в таблицу истории
            $Sklad_nomenclature_historys_items = new Sklad_nomenclature_historys_items();
            $Sklad_nomenclature_historys_items->id_req = $last_request->id;
            $Sklad_nomenclature_historys_items->key_position = $el->key;
            $Sklad_nomenclature_historys_items->name_position = $el->name;
            $Sklad_nomenclature_historys_items->quantity_position = $el->count;
            $Sklad_nomenclature_historys_items->save();
        }

        $this->dropsth(Auth::id());

        $sheet->mergeCells('C'.$i+14 .':G'.$i+14);
        $sheet->getStyle('C'.$i+14 .':G'.$i+14)->applyFromArray($bold);
        $sheet->getStyle('C'.$i+14 .':G'.$i+14)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('C'.$i+14, 'Обоснование закупки');

        $sheet->mergeCells('A'.$i+15 .':I'.$i+15);
        $sheet->mergeCells('A'.$i+16 .':I'.$i+16);
        $sheet->getStyle('A'.$i+15 .':I'.$i+15)->applyFromArray($borderBottom);
        $sheet->getStyle('A'.$i+16 .':I'.$i+16)->applyFromArray($borderBottom);
        
        $sheet->mergeCells('A'.$i+18 .':I'.$i+18);
        $sheet->getStyle('A'.$i+18 .':I'.$i+18)->applyFromArray($bold);
        $sheet->getStyle('A'.$i+18)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A'.$i+18, 'Сведения о рекомендуемом поставщике или изготовителе (при наличии)');

        $sheet->mergeCells('A'.$i+20 .':B'.$i+20);
        $sheet->mergeCells('C'.$i+20 .':I'.$i+20);
        $sheet->mergeCells('A'.$i+21 .':B'.$i+21);
        $sheet->mergeCells('C'.$i+21 .':I'.$i+21);
        $sheet->mergeCells('A'.$i+22 .':B'.$i+22);
        $sheet->mergeCells('C'.$i+22 .':I'.$i+22);
        $sheet->getStyle('A'.$i+20 .':I'.$i+22)->applyFromArray($borderStyle);
        $sheet->setCellValue('A'.$i+20, 'Наименование');
        $sheet->setCellValue('A'.$i+21, 'Адрес/телефон');
        $sheet->setCellValue('A'.$i+22, 'Контактные лица');

        $sheet->mergeCells('A'.$i+24 .':I'.$i+24);
        $sheet->setCellValue('A'.$i+24, 'Контрольные образцы (при необходимости)__________________________________________');
        $sheet->mergeCells('A'.$i+25 .':I'.$i+25);
        $sheet->setCellValue('A'.$i+25, 'Количество поставки _____________________________________________________ед. изм.');
        $sheet->mergeCells('A'.$i+26 .':I'.$i+26);
        $sheet->setCellValue('A'.$i+26, 'Комплект поставки:');
        $sheet->mergeCells('B'.$i+27 .':I'.$i+27);
        $sheet->setCellValue('B'.$i+27, 'чертежи _______________шт.');
        $sheet->mergeCells('B'.$i+28 .':I'.$i+28);
        $sheet->setCellValue('B'.$i+28, 'ТУ или ТО_______________');
        $sheet->mergeCells('B'.$i+29 .':I'.$i+29);
        $sheet->setCellValue('B'.$i+29, 'программы и методики испытаний, анализов _________________');
        $sheet->mergeCells('B'.$i+30 .':I'.$i+30);
        $sheet->setCellValue('B'.$i+30, 'другие __________________________________________________');

        $sheet->mergeCells('A'.$i+32 .':B'.$i+32);
        $sheet->mergeCells('C'.$i+32 .':D'.$i+32);
        $sheet->mergeCells('E'.$i+32 .':F'.$i+32);
        $sheet->mergeCells('G'.$i+32 .':H'.$i+32);
        $sheet->getStyle('C'.$i+32)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E'.$i+32)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G'.$i+32)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I'.$i+32)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('C'.$i+32, 'Должность');
        $sheet->setCellValue('E'.$i+32, 'Фамилия И. О.');
        $sheet->setCellValue('G'.$i+32, 'Подпись');
        $sheet->setCellValue('I'.$i+32, 'Дата');

        $sheet->setCellValue('A'.$i+33, 'Заказчик:');
        $sheet->setCellValue('A'.$i+34, 'Подтверждаю целесообразность заявки:');
        $sheet->setCellValue('A'.$i+35, 'Подтверждаю целесообразность заявки:');
        $sheet->setCellValue('A'.$i+36, 'Согласовано:');
        $sheet->getStyle('A'.$i+34)->getAlignment()->setWrapText(true);
        $sheet->getStyle('A'.$i+35)->getAlignment()->setWrapText(true);
        $sheet->getRowDimension($i+33)->setRowHeight(50);
        $sheet->getRowDimension($i+34)->setRowHeight(50);
        $sheet->getRowDimension($i+35)->setRowHeight(50);
        $sheet->getRowDimension($i+36)->setRowHeight(50);
        $sheet->getStyle('C'.$i+33)->getAlignment()->setWrapText(true);
        $sheet->getStyle('C'.$i+34)->getAlignment()->setWrapText(true);
        $sheet->getStyle('C'.$i+35)->getAlignment()->setWrapText(true);
        $sheet->getStyle('C'.$i+36)->getAlignment()->setWrapText(true);
        $sheet->mergeCells('A'.$i+33 .':B'.$i+33);
        $sheet->mergeCells('C'.$i+33 .':D'.$i+33);
        $sheet->mergeCells('E'.$i+33 .':F'.$i+33);
        $sheet->mergeCells('G'.$i+33 .':H'.$i+33);
        $sheet->mergeCells('A'.$i+34 .':B'.$i+34);
        $sheet->mergeCells('C'.$i+34 .':D'.$i+34);
        $sheet->mergeCells('E'.$i+34 .':F'.$i+34);
        $sheet->mergeCells('G'.$i+34 .':H'.$i+34);
        $sheet->mergeCells('A'.$i+35 .':B'.$i+35);
        $sheet->mergeCells('C'.$i+35 .':D'.$i+35);
        $sheet->mergeCells('E'.$i+35 .':F'.$i+35);
        $sheet->mergeCells('G'.$i+35 .':H'.$i+35);
        $sheet->mergeCells('A'.$i+36 .':B'.$i+36);
        $sheet->mergeCells('C'.$i+36 .':D'.$i+36);
        $sheet->mergeCells('E'.$i+36 .':F'.$i+36);
        $sheet->mergeCells('G'.$i+36 .':H'.$i+36);
        $sheet->getStyle('A'.$i+32 .':I'.$i+36)->applyFromArray($borderStyle);
        foreach ($user as $el){
            $sheet->setCellValue('C'.$i+33, $el->post);
            $sheet->setCellValue('E'.$i+33, $el->fio);
        }

        foreach ($user as $el){
            $sheet->setCellValue('C'.$i+36, 'Заместитель финансового директора');
            $sheet->setCellValue('E'.$i+36, 'Шустов В.Ю.');
        }

        $sheet->mergeCells('A'.$i+38 .':I'.$i+38);
        $sheet->setCellValue('A'.$i+38, 'Срок исполнения заявки:   «___»______________20__г. (желаемый)');

        $sheet->mergeCells('A'.$i+40 .':I'.$i+40);
        $sheet->setCellValue('A'.$i+40, 'Согласовано с исполнителем: __________________ _____________ ___________________________');

        $sheet->mergeCells('D'.$i+41 .':E'.$i+41);
        $sheet->setCellValue('D'.$i+41, '(должность)');
        $sheet->setCellValue('F'.$i+41, '(подпись)');
        $sheet->setCellValue('H'.$i+41, '(ФИО)');
        $sheet->getStyle('D'.$i+41)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F'.$i+41)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H'.$i+41)->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        
        $oWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=\"Заявка на ТМЦ.xlsx\"");
        header("Cache-Control: max-age=0");
        $oWriter->save('php://output');
    }

    public function nomenclatureGetadd(){
        $nomenclature_now_categories = new Sklad_nomenclature_categories();
        $nomenclature_now = new Sklad_nomenclature();
        $nomenclature_now_categories->truncate();
        $nomenclature_now->truncate();
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
            if ($C != null){
                $nomenclature_now->categorie_id = $nomenclature_now_categories->where('name', '=', $A)->select('id')->first()->id;
                $nomenclature_now->name = $B;
                $nomenclature_now->unit = $C;
                $nomenclature_now->key = $D;
                $nomenclature_now->save();
            }
            
        }
    }

    public function requests(Request $request){
        if (Auth::user()->id == 3 || Auth::user()->id == 9 || Auth::user()->id == 11 || Auth::user()->id == 12){
            $requests = Sklad_nomenclature_historys::orderby('id', 'desc')->get();
            return view('nomenclatures/all_request', ['data'=>$requests]);
        }else {
            $requests = Sklad_nomenclature_historys::where('id_user', '=', Auth::id())->orderby('id', 'desc')->get();
            return view('nomenclatures/all_request', ['data'=>$requests]);
        }
    }

    public function requests_get($id){
        $requests = Sklad_nomenclature_historys_items::where('id_req', '=', $id)->get();
        $Sklad_nomenclature_historys = Sklad_nomenclature_historys::where('id', '=', $id)->get();
        foreach ($Sklad_nomenclature_historys as $el){
            $user = User::where('id', '=', $el->id_user)->get();
        }
        

        $expls = Sklad_nomenclature_expls::where('user_id', '=', Auth::id());

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        foreach ($user as $el){
            $sheet->setCellValue('A1', $el->surname.' '.$el->firstname.' '.$el->middlename);
            $sheet->setCellValue('A2', $el->department);
            $sheet->setCellValue('A4', $el->email);
        }
        foreach ($Sklad_nomenclature_historys as $el){
            $sheet->setCellValue('A3', $el->statia);
            $sheet->setCellValue('B3', $el->obosnovaniye);
        }
        $sheet->setCellValue('A5', 'Код');
        $sheet->setCellValue('B5', 'Наименование');
        $sheet->setCellValue('C5', 'Наименование у поставщика');
        $sheet->setCellValue('D5', 'Кол-во');
        $sheet->setCellValue('E5', 'Цена за ед.');
        $i = 6;
        foreach ($requests as $el){
            $sheet->setCellValue('A'.$i, $el->key_position);
            $sheet->setCellValue('B'.$i, $el->name_position);
            $sheet->setCellValue('C'.$i, $el->name_provider);
            $sheet->setCellValue('D'.$i, $el->quantity_position);
            $sheet->setCellValue('E'.$i, $el->price);
            $i++;
        }
        
        $oWriter = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=\"ТМЦ №".$id.".xlsx\"");
        header("Cache-Control: max-age=0");
        $oWriter->save('php://output');
    }

    public function dr(){
        $expls = Sklad_nomenclature_expls::where('user_id', '=', Auth::id())->get();
        foreach ($expls as $el) {
            $Sklad_nomenclature_historys = new Sklad_nomenclature_historys();
            $Sklad_nomenclature_historys->id_user = Auth::id();
            $Sklad_nomenclature_historys->obosnovaniye = $el->obosnovaniye;
            $Sklad_nomenclature_historys->statia = $el->statia;
            $Sklad_nomenclature_historys->save();
        }
    }

    public function pdf(){
        $this->dr();
        $last_request = Sklad_nomenclature_historys::orderby('id', 'desc')->first();
        $user = User::where('id', '=', Auth::id())->get();
        $now = Sklad_nomenclature::leftJoin('sklad_nomenclature_storages', 'sklad_nomenclatures.id', '=', 'sklad_nomenclature_storages.product_id')
                ->where('sklad_nomenclature_storages.user_id', '=', Auth::id())->get();
        $expls = Sklad_nomenclature_expls::where('user_id', '=', Auth::id())->get();
        $summa = 0;
        foreach ($now as $el) {
            $summa += $el->price*$el->count;
            $Sklad_nomenclature_historys_items = new Sklad_nomenclature_historys_items();
            $Sklad_nomenclature_historys_items->id_req = $last_request->id;
            $Sklad_nomenclature_historys_items->key_position = $el->key;
            $Sklad_nomenclature_historys_items->name_position = $el->name;
            $Sklad_nomenclature_historys_items->name_provider = $el->obosnovaniye;
            $Sklad_nomenclature_historys_items->norm_doc = $el->statia;
            $Sklad_nomenclature_historys_items->price = $el->price;
            $Sklad_nomenclature_historys_items->quantity_position = $el->count;
            $Sklad_nomenclature_historys_items->save();
        }
        $summa = number_format($summa, 0, '', ' ');
        $pdf = PDF::loadView('pdf.tmc', compact('user', 'last_request', 'now', 'expls', 'summa'));
        $pdf->setPaper('A4', 'portrait');
        //return $last_request;
        return $pdf->download('ТМЦ.pdf');
    }   

    public function obosnovaniye(Request $req){
        if (Sklad_nomenclature_expls::where('user_id', '=', Auth::id())->exists()){//запись существует
            $table = new Sklad_nomenclature_expls;
            if ($req->has('obosnovaniye')){
                $table::where('user_id', '=', Auth::id())->update(['obosnovaniye' => $req->input('obosnovaniye')]);
            }elseif($req->has('statia')) {
                $table::where('user_id', '=', Auth::id())->update(['statia' => $req->input('statia')]);
            }elseif($req->has('postavshchik')) {
                $table::where('user_id', '=', Auth::id())->update(['postavshchik' => $req->input('postavshchik')]);
            }elseif($req->has('adrestelefon')) {
                $table::where('user_id', '=', Auth::id())->update(['adrestelefon' => $req->input('adrestelefon')]);
            }elseif($req->has('kontaks')) {
                $table::where('user_id', '=', Auth::id())->update(['kontaks' => $req->input('kontaks')]);
            }elseif($req->has('agreedone')) {
                $agree = User::where('fio', 'like', '%'.$req->input('agreedone').'%')->get();
                foreach ($agree as $el) {
                    $table::where('user_id', '=', Auth::id())->update(['agreedone' => $el->fio, 'agreedonedlj' => $el->post]);
                }
            }elseif($req->has('agreedtwo')) {
                $agree = User::where('fio', 'like', '%'.$req->input('agreedtwo').'%')->get();
                foreach ($agree as $el) {
                    $table::where('user_id', '=', Auth::id())->update(['agreedtwo' => $el->fio, 'agreedtwodlj' => $el->post]);
                }
            }elseif($req->has('agreedthree')) {
                $agree = User::where('fio', 'like', '%'.$req->input('agreedthree').'%')->get();
                foreach ($agree as $el) {
                    $table::where('user_id', '=', Auth::id())->update(['agreedthree' => $el->fio, 'agreedthreedlj' => $el->post]);
                }
            }
        }else{//запись не существует
            $table = new Sklad_nomenclature_expls;
            if ($req->has('obosnovaniye')){
                $p = 'obosnovaniye';
                $table->$p = $req->input('obosnovaniye');
                $table->user_id = Auth::id();
                $table->save();
            }elseif($req->has('statia')) {
                $p = 'statia';
                $table->$p = $req->input('statia');
                $table->user_id = Auth::id();
                $table->save();
            }elseif($req->has('postavshchik')) {
                $p = 'postavshchik';
                $table->$p = $req->input('postavshchik');
                $table->user_id = Auth::id();
                $table->save();
            }elseif($req->has('adrestelefon')) {
                $p = 'adrestelefon';
                $table->$p = $req->input('adrestelefon');
                $table->user_id = Auth::id();
                $table->save();
            }elseif($req->has('kontaks')) {
                $p = 'kontaks';
                $table->$p = $req->input('kontaks');
                $table->user_id = Auth::id();
                $table->save();
            }elseif($req->has('agreedone')) {
                $p = 'agreedone';
                $table->$p = $req->input('agreedone');
                $table->user_id = Auth::id();
                $table->save();
            }elseif($req->has('agreedtwo')) {
                $p = 'agreedtwo';
                $table->$p = $req->input('agreedtwo');
                $table->user_id = Auth::id();
                $table->save();
            }elseif($req->has('agreedthree')) {
                $p = 'agreedthree';
                $table->$p = $req->input('agreedthree');
                $table->user_id = Auth::id();
                $table->save();
            }
        }
        return $this->storage();
    }

    public function nomenclatureGet(){
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

        return redirect()->route('login');
        //for ($i = 2; $i <= $spreadsheet->getActiveSheet()->getHighestRow(); $i++){
        //    $nomenclature_now = new Sklad_nomenclature();
        //    $C = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(3, $i)->getValue();
        //    $D = $spreadsheet->getActiveSheet()->getCellByColumnAndRow(4, $i)->getValue();
        //    if ($nomenclature_now->where('key', '=', $D)->select('unit') != $C){
        //        if ($C != null){
        //            $nomenclature_now->where('key', '=', $D)->update(['unit' => $C]);
        //        }
        //    }
        //}
    }

    public function clearStorage(){
        Sklad_nomenclature_storages::where('user_id', '=', Auth::id())->delete();
        Sklad_nomenclature_expls::where('user_id', '=', Auth::id())->update(['obosnovaniye' => null, 'statia' => null, 'postavshchik' => null, 'adrestelefon' => null, 
        'kontaks' => null, 'agreedone' => null, 'agreedtwo' => null, 'agreedthree' => null, 'agreedonedlj' => null, 'agreedtwodlj' => null, 'agreedthreedlj' => null]);
        return $this->storage();
    }

    public function clearagreedone(){
        Sklad_nomenclature_expls::where('user_id', '=', Auth::id())->update(['agreedone' => null, 'agreedonedlj' => null]);
        return $this->storage();
    }

    public function clearagreedtwo(){
        Sklad_nomenclature_expls::where('user_id', '=', Auth::id())->update(['agreedtwo' => null, 'agreedtwodlj' => null]);
        return $this->storage();
    }

    public function clearagreedthree(){
        Sklad_nomenclature_expls::where('user_id', '=', Auth::id())->update(['agreedthree' => null, 'agreedthreedlj' => null]);
        return $this->storage();
    }

    public function statusTmc($id){
        $inputFileName = './uploads/rassilka (XLS).xls';
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);
        
    }

    public function repeatTmc($id){
        $this->clearStorage();
        $storageSt = sklad_nomenclature_historys::where('id', '=', $id)->first();
        $storage = sklad_nomenclature_historys_items::where('id_req', '=', $id)->get();
        $sklad_nomenclature_expls = new sklad_nomenclature_expls();
        $sklad_nomenclature_expls::where('user_id', '=', Auth::id())->update(['obosnovaniye' => $storageSt->obosnovaniye]);
        $sklad_nomenclature_expls::where('user_id', '=', Auth::id())->update(['statia' => $storageSt->statia]);
        foreach ($storage as $el){
            $nomenclature_now_storage = new Sklad_nomenclature_storages();
            $idpos = Sklad_nomenclature::where('key', '=', $el->key_position)->get();
            foreach ($idpos as $ell){
                $nomenclature_now_storage->product_id = $ell->id;
            }
            $nomenclature_now_storage->obosnovaniye = $el->name_provider;
            $nomenclature_now_storage->statia = $el->norm_doc;
            $nomenclature_now_storage->price = $el->price;
            $nomenclature_now_storage->count = $el->quantity_position;
            $nomenclature_now_storage->user_id = Auth::id();
            $nomenclature_now_storage->save();
        }
        return $this->storage();
    }
}