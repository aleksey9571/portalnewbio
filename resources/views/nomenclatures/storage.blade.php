<x-app-layout>
    <div class="container">
        <header class="d-flex justify-content-center py-3">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a href="{{ route('nomenclature', 1) }}" type="button" class="btn btn-primary position-relative ms-3">
                    Назад
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('nomenclature_tmcExcel') }}" type="button" class="btn btn-primary position-relative ms-3">
                    Сформировать заявку
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('nomenclature_clearstorage') }}" type="button" class="btn btn-danger position-relative ms-3">
                    Очистить корзину
                </a>
            </li>
        </ul>
        </header>
    </div>
    <div class="container">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th scope="col">Код</th>
                    <th scope="col">Наименование</th>
                    <th scope="col" style="width: 20%;" >Наименование у поставщика</th>
                    <th scope="col" style="width: 20%;" >Нормативная документация</th>
                    <th scope="col" style="width: 5%; word-wrap: break-word">Ед. измер</th>
                    <th scope="col">Кол-во</th>
                    <th scope="col">Стоимость</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $el)
                <tr>
                    <th scope="col" >{{ $el->key }}</th>
                    <th scope="col">{{ $el->name }}</th>
                    <th scope="col">
                        <form action='{{ route("nomenclature_storage_edit") }}' method='post'>
                            @csrf
                            <input type="hidden" name="id" value='{{ $el->id }}'>
                            <textarea name="obosnovaniye" class="form-control" rows="3" placeholder="" autocomplete = "off" onchange="this.form.submit()">{{ $el->obosnovaniye }}</textarea>
                        </form>
                    </th>
                    <th scope="col">
                        <form action='{{ route("nomenclature_storage_edit") }}' method='post'>
                            @csrf
                            <input type="hidden" name="id" value='{{ $el->id }}'>
                            <textarea name="statia" class="form-control" rows="3" placeholder="" autocomplete = "off" onchange="this.form.submit()">{{ $el->statia }}</textarea>
                        </form>
                    </th>
                    <th scope="col">{{ $el->unit }}</th>
                    <th scope="col">
                        <form action='{{ route("nomenclature_storage_edit") }}' method='post'>
                            @csrf
                            <input type="hidden" name="id" value='{{ $el->id }}'>
                            <input style="padding-left: 5px; width: 80px;" step="0.1" type="number" min="1" value="{{ $el->count }}" name="count" onchange="this.form.submit()"/>
                        </form>
                    </th>
                    <th scope="col">
                        <form action='{{ route("nomenclature_storage_edit") }}' method='post'>
                            @csrf
                            <input type="hidden" name="id" value='{{ $el->id }}'>
                            <input style="padding-left: 5px; width: 80px;" type="number" min="1" value="{{ $el->price }}" name="price" onchange="this.form.submit()"/>
                        </form>
                    </th>
                    <th scope="col">
                        <form action='{{ route("nomenclature_storage_delete") }}' method='post'>
                            @csrf
                            <input type="hidden" name="id" value='{{ $el->id }}'>
                            <button class='btn btn-primary'>X</button>
                        </form>
                    </th>
                </tr>
            </tbody>
            @endforeach
        </table>
        <br>
        <div class="form-group my-2">
            <form action='{{ route("nomenclature_expl") }}' method='post'>
                @csrf                        
                <label class="form-lable">Обоснование</label>
                <textarea style="resize: none;" name="obosnovaniye" class="form-control" rows="5" placeholder="" autocomplete = "off" onchange="this.form.submit()">@foreach($expls as $el){{ $el->obosnovaniye }}@endforeach</textarea>
            </form>
        </div>
        <div class="form-group my-2">
            <form action='{{ route("nomenclature_expl") }}' method='post'>
                @csrf    
                <label class="form-lable">Статья затрат</label>
                <select class="form-control" name="statia" placeholder="" autocomplete = "off" onchange="this.form.submit()">
                    <option disabled selected value="@foreach($expls as $el){{ $el->statia }}@endforeach">@foreach($expls as $el){{ $el->statia }}@endforeach</option>
                    <option value=""></option>
                    <option value="1.1 Закупка ЗИП">1.1 Закупка запасных частей и принадлежностей для планового и аварийного ремонта и технического обслуживания оборудования</option>
                    <option value="1.2 Закупка ОС и оборудования">1.2 Закупка основных средств и оборудования</option>
                    <option value="1.3 Закупка СИЗ">1.3 Закупка спец.одежды, систем безопасности при работах повышенной опасности, средств индивидуальной защиты и т.д.</option>
                    <option value="1.4 Закупка расходных материалов">1.4 Расходные материалы для выполнения ремонтных работ (круги отрезные, аргон, электроды и т.д.)</option>
                    <option value="1.5 Закупка материалов для СМР">1.5 Закупка материалов по утвержденным заявкам на услуги или техническим заданиям на модернизацию</option>
                    <option value="1.6 Закупка инструмента">1.6 Закупка инструмента</option>
                    <option value="1.7 Закупка вспомогательных материалов">1.7 Закупка реагентов для ведения технологического процесса</option>
                    <option value="2 Обучение персонала">2 Повышение квалификации, гос.пошлины за аттестации, обучения правилам ОТ и ПБ и т.д.</option>
                    <option value="3 Утилизация отходов">3 Вывоз и утилицация отходов в соответствии с утвержденным перечнем</option>
                    <option value="4 Организация рабочих мест">4 Закупка мебели, компьютеров, шкафов в раздевалки и т.д.</option>
                    <option value="5 ТО, ремонт и эксплуатация (подряд)">5 Организация эксплуатации, ТО и ремонта по Договорам подряда</option>
                    <option value="6 СМР (подряд)">6 Выполнение строительно-монтажных работ по Договорам подряда</option>
                    <option value="7 Энергоресурсы">7 Оплаты за эл.энергию, водоснабжение и газоснабжение</option>
                    <option value="8 Доставка материалов">8 Доставки выполняемые сторонними организациями</option>
                    <option value="9 ФОТ">9 Фонд оплаты труда</option>
                </select>
            </form>
        </div>
        <br>
        <label class="fs-5">Сведения о рекомендуемом поставщике или изготовителе (при наличии)</label>
        <div class="form-group my-2">
            <form action='{{ route("nomenclature_expl") }}' method='post'>
                @csrf 
                <label class="form-lable">Наименование</label>
                <input type="" name="postavshchik" class="form-control" placeholder="" value="@foreach($expls as $el){{ $el->postavshchik }}@endforeach" autocomplete = "off" onchange="this.form.submit()">
            </form>
        </div>
        <div class="form-group my-2">
            <form action='{{ route("nomenclature_expl") }}' method='post'>
                @csrf 
                <label class="form-lable">Адрес/телефон</label>
                <input type="" name="adrestelefon" class="form-control" placeholder="" value="@foreach($expls as $el){{ $el->adrestelefon }}@endforeach" autocomplete = "off" onchange="this.form.submit()">
            </form>
        </div>
        <div class="form-group my-2">
            <form action='{{ route("nomenclature_expl") }}' method='post'>
                @csrf 
                <label class="form-lable">Контактные лица</label>
                <input type="" name="kontaks" class="form-control" placeholder="" value="@foreach($expls as $el){{ $el->kontaks }}@endforeach" autocomplete = "off" onchange="this.form.submit()">
            </form>
        </div>
        <br>
        <label class="fs-5">Спиоск согласующих</label>
        <div class="form-group my-2">
            <form action='{{ route("nomenclature_expl") }}' method='post'>
                @csrf 
                <label class="form-lable">Согласовано: </label><a href="{{ route('nomenclature_clearagreedone') }}"> Очистить</a>
                <input type="" name="agreedone" class="form-control" placeholder="" value="@foreach($expls as $el){{ $el->agreedone }}@endforeach" autocomplete = "off" onchange="this.form.submit()">
            </form>
        </div>
        <div class="form-group my-2">
            <form action='{{ route("nomenclature_expl") }}' method='post'>
                @csrf 
                <label class="form-lable">Согласовано: </label><a href="{{ route('nomenclature_clearagreedtwo') }}"> Очистить</a>
                <input type="" name="agreedtwo" class="form-control" placeholder="" value="@foreach($expls as $el){{ $el->agreedtwo }}@endforeach" autocomplete = "off" onchange="this.form.submit()">
            </form>
        </div>
        <div class="form-group my-2">
            <form action='{{ route("nomenclature_expl") }}' method='post'>
                @csrf 
                <label class="form-lable">Согласовано: </label><a href="{{ route('nomenclature_clearagreedthree') }}"> Очистить</a>
                <input type="" name="agreedthree" class="form-control" placeholder="" value="@foreach($expls as $el){{ $el->agreedthree }}@endforeach" autocomplete = "off" onchange="this.form.submit()">
            </form>
        </div>
    </div>
</x-app-layout>