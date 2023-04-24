<!DOCTYPE html>
<html>
<head>
    <title>Заявка на выдачу материалов со склада</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-size: 12px;">
    <div">
        <div style="width:300px;float: left; font-weight: bold;">
            <br>
            ______________________________
            <p>(структурное подразделение)</p>
        </div>
        <div style="width:300px; text-align: right; float: right; font-weight: bold;">
            УТВЕРЖДАЮ<br>
            непосредственный руководитель<br>
            ООО «НьюБио»<br>
            _________________________<br>
            «___»______________ 20___г.
        </div>
        <div style="text-align:center; margin-top: 120px; margin-bottom: 20px; font-weight: bold;">
            ЗАЯВКА №_____  КП:{{ $last_request->id }}<br>
            на выдачу материалов со склада<br>
            от «___»______________ 20___г.
        </div>
        Прошу выдать со склада следующие материалы:
        <table width="100%" cellpadding="0" cellspacing="0" border="1">
            <thead>
                <tr>
                    <th style="font-size: 12px; text-align: center; padding: 3px 5px;">№ п/п</th>
                    <th style="font-size: 12px; text-align: center; padding: 3px 5px;">Наименование</th>
                    <th style="font-size: 12px; text-align: center; padding: 3px 5px;">Ед. измер.</th>
                    <th style="font-size: 12px; text-align: center; padding: 3px 5px;">Количество</th>
                </tr>
            </thead>
            <tbody>
                {{ $i = 1 }}
                @foreach($now as $el)  
                <tr>
                    <td style="font-size: 12px; text-align: center; padding: 5px 10px;">{{ $i }}</td>
                    <td style="font-size: 12px; text-align: left; padding: 5px 10px;">{{ $el->key }}; {{ $el->name }}</td>
                    <td style="font-size: 12px; text-align: center; padding: 5px 10px;">{{ $el->unit }}</td>
                    <td style="font-size: 12px; text-align: center; padding: 5px 10px;">{{ $el->count }}</td>
                </tr>
                {{ $i++ }}
                @endforeach
            </tbody>
        </table>
        <table style="margin-top: 20px;" width="100%" cellpadding="0" cellspacing="0" border="1">
            <thead>
                <tr>
                    <th style="text-align: center; padding: 5px 10px; width: 150px;">Должность</strong></th>
                    <th style="text-align: center; padding: 5px 10px; width: 150px;">Фамилия И. О.</th>
                    <th style="text-align: center; padding: 5px 10px;">Подпись</th>
                    <th style="text-align: center; padding: 5px 10px;">Дата</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 5px 10px;">@foreach($user as $el) {{ $el->post }}  @endforeach</td>
                    <td style="padding: 5px 10px;">@foreach($user as $el) {{ $el->fio }}  @endforeach</td>
                    <td style="padding: 5px 10px;"></td>
                    <td style="padding: 5px 10px;"></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>