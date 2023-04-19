@component('mail::message')
<div style="font-weight: bold; font-size:20px;">
    Сообщение было отправлено:
</div>
<div style="font-weight: bold; font-size:20px;">
    {{ Auth::user()->name }}, {{ Auth::user()->email }}
</div>
<br />
<div style="font-weight: bold; font-size:18px;">
    Наименование:
</div>
<div style="font-size:16px;">
    {{ $name }}
</div>
<br />
<div style="font-weight: bold; font-size:18px;">
    Производитель:
</div>
<div style="font-size:16px;">
    {{ $generator }}
</div>
<br />
<div style="font-weight: bold; font-size:18px;">
    Артикул:
</div>
<div style="font-size:16px;">
    {{ $artikul }}
</div>
<br />
<div style="font-weight: bold; font-size:18px;">
    Обоснование:
</div>
<div style="font-size:16px;">
    {{ $message }}
</div>
@endcomponent
