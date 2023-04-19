<!DOCTYPE html>
<html>
<head>
    <title>ТМЦ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-size: 12px;">
    <div">
        <div style="width:300px;float: left; font-weight: bold;">
            ИСПОЛНИТЕЛЬ<br>
            <br>
            ______________________________
            <p>(структурное подразделение)</p>
        </div>
        <div style="width:300px; text-align: right; float: right; font-weight: bold;">
            УТВЕРЖДАЮ<br>
            Генеральный директор<br>
            ООО «НьюБио»<br>
            _________________________<br>
            «___»______________ 20___г.
        </div>
        <div style="text-align:center; margin-top: 120px; margin-bottom: 20px; font-weight: bold;">
            ЗАЯВКА №_____  КП:{{ $last_request->id }}<br>
            на закупку ТМЦ<br>
            от «___»______________ 20___г.
        </div>
        <table width="100%" cellpadding="0" cellspacing="0" border="1">
            <thead>
                <tr>
                    <th style="font-size: 10px; text-align: center; padding: 3px 5px;">№ п/п</th>
                    <th style="font-size: 10px; text-align: center; padding: 3px 5px;">Наименование продукции согласно каталога ООО НьюБио<</th>
                    <th style="font-size: 10px; text-align: center; padding: 3px 5px;">Наименование продукции поставщика /производителя Безусловный признак идентификации</th>
                    <th style="font-size: 10px; text-align: center; padding: 3px 5px;">Нормативная документация на продукцию</th>
                    <th style="font-size: 10px; text-align: center; padding: 3px 5px;">Ед. измер.</th>
                    <th style="font-size: 10px; text-align: center; padding: 3px 5px;">Заявлено (кол-во)</th>
                    <th style="font-size: 10px; text-align: center; padding: 3px 5px;">Ориен-ная цена за ед.</th>
                </tr>
            </thead>
            <tbody>
                {{ $i = 1 }}
                @foreach($now as $el)  
                <tr>
                    <td style="font-size: 10px; text-align: center; padding: 5px 10px;">{{ $i }}</td>
                    <td style="font-size: 10px; text-align: left; padding: 5px 10px;">{{ $el->key }}; {{ $el->name }}</td>
                    <td style="font-size: 10px; word-break:break-all; text-align: left; padding: 5px 10px;">{{ $el->obosnovaniye }}</td>
                    <td style="font-size: 10px; text-align: left; padding: 5px 10px;">{{ $el->statia }}</td>
                    <td style="font-size: 10px; text-align: center; padding: 5px 10px;">{{ $el->unit }}</td>
                    <td style="font-size: 10px; text-align: center; padding: 5px 10px;">{{ $el->count }}</td>
                    <td style="font-size: 10px; text-align: center; padding: 5px 10px;">{{ $el->price }}</td>
                </tr>
                {{ $i++ }}
                @endforeach
                <tr>
                    <td style="font-size: 10px; text-align: right; padding: 5px 10px;" colspan="6">ИТОГО: </td>
                    <td style="font-size: 10px; text-align: center; padding: 5px 10px;">{{ $summa }}</td>
                </tr>
            </tbody>
        </table>
        <div style="margin-top: 20px; text-align: center; font-weight: bold;">
            Обоснование заявки
        </div>
        <div style="margin-top: 10px;">
            @foreach($expls as $el){{ $el->obosnovaniye }}@endforeach
        </div>
        <div style="margin-top: 20px;">
            Статья затрат: @foreach($expls as $el){{ $el->statia }}@endforeach
        </div>
        <div style="margin-top: 20px; text-align: center; font-weight: bold;">
            Сведения о рекомендуемом поставщике или изготовителе (при наличии)
        </div>
        <table style="margin-top: 10px;" width="100%" cellpadding="0" cellspacing="0" border="1">
            <tbody>
                <tr>
                    <td style="padding: 5px 10px; width: 150px;">Наименование</td>
                    <td style="padding: 5px 10px;">@foreach($expls as $el){{ $el->postavshchik }}@endforeach</td>
                </tr>
                <tr>
                    <td style="padding: 5px 10px; width: 150px;">Адрес/телефон</td>
                    <td style="padding: 5px 10px;">@foreach($expls as $el){{ $el->adrestelefon }}@endforeach</td>
                </tr>
                <tr>
                    <td style="padding: 5px 10px; width: 150px;">Контактные лица</td>
                    <td style="padding: 5px 10px;">@foreach($expls as $el){{ $el->kontaks }}@endforeach</td>
                </tr>
            </tbody>
        </table>
        <div style="margin-top: 20px;">
            Контрольные образцы (при необходимости)__________________________________________<br>
            Количество поставки _____________________________________________________ед. изм.<br>
            Комплект поставки:
            <p style="padding-left: 50px; margin: 0px;">чертежи _______________шт.</p>
            <p style="padding-left: 50px; margin: 0px;">ТУ или ТО_______________</p>
            <p style="padding-left: 50px; margin: 0px;">программы и методики испытаний, анализов _________________</p>
            <p style="padding-left: 50px; margin: 0px;">другие __________________________________________________</p>
        </div>
        <table style="margin-top: 20px;" width="100%" cellpadding="0" cellspacing="0" border="1">
            <thead>
                <tr>
                    <th style="padding: 5px 10px; width: 100px;"></th>
                    <th style="text-align: center; padding: 5px 10px; width: 150px;">Должность</strong></th>
                    <th style="text-align: center; padding: 5px 10px; width: 150px;">Фамилия И. О.</th>
                    <th style="text-align: center; padding: 5px 10px;">Подпись</th>
                    <th style="text-align: center; padding: 5px 10px;">Дата</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 5px 10px;">Заказчик:</td>
                    <td style="padding: 5px 10px;">@foreach($user as $el) {{ $el->post }}  @endforeach</td>
                    <td style="padding: 5px 10px;">@foreach($user as $el) {{ $el->fio }}  @endforeach</td>
                    <td style="padding: 5px 10px;"></td>
                    <td style="padding: 5px 10px;"></td>
                </tr>
                @foreach($expls as $el)
                <tr>
                    <td style="padding: 5px 10px;">Подтверждаю целесообразность заявки:</td>
                    <td style="padding: 5px 10px;">{{ $el->agreedonedlj }}</td>
                    <td style="padding: 5px 10px;">{{ $el->agreedone }}</td>
                    <td style="padding: 5px 10px;"></td>
                    <td style="padding: 5px 10px;"></td>
                </tr>
                <tr>
                    <td style="padding: 5px 10px;">Подтверждаю целесообразность заявки:</td>
                    <td style="padding: 5px 10px;">{{ $el->agreedtwodlj }}</td>
                    <td style="padding: 5px 10px;">{{ $el->agreedtwo }}</td>
                    <td style="padding: 5px 10px;"></td>
                    <td style="padding: 5px 10px;"></td>
                </tr>
                <tr>
                    <td style="padding: 5px 10px;">Подтверждаю целесообразность заявки:</td>
                    <td style="padding: 5px 10px;">{{ $el->agreedthreedlj }}</td>
                    <td style="padding: 5px 10px;">{{ $el->agreedthree }}</td>
                    <td style="padding: 5px 10px;"></td>
                    <td style="padding: 5px 10px;"></td>
                </tr>
                @endforeach
                <tr>
                    <td style="padding: 5px 10px;">Согласовано:</td>
                    <td style="padding: 5px 10px;">Заместитель финансового директора</td>
                    <td style="padding: 5px 10px;">Шустов В.Ю.</td>
                    <td style="padding: 5px 10px;"></td>
                    <td style="padding: 5px 10px;"></td>
                </tr>
            </tbody>
        </table>
        <div style="margin-top: 20px;">
            Срок исполнения заявки:   «___»______________20__г. (желаемый)
        </div>
        <div style="margin-top: 20px;">
            Согласовано с исполнителем: __________________ _____________ ___________________________
        </div>
        <div style="margin:0px;">
            <div style="float: left; padding-left: 220px;">(должность)</div>
            <div style="float: left; padding-left: 20px;">(подпись)</div>
            <div style="float: left; padding-left: 50px;">(ФИО)</div>
        </div>
    </div>
</body>
</html>