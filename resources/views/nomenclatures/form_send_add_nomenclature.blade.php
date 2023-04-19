<x-app-layout>
    <div class="container">
        <div class="text-center fs-4">Добавление номенклатуры в справочник</div>
        <form method="post" action="{{ route('nomenclature_send_add_nomenclature') }}">
            @csrf
            <div class="form-group my-2">
                <label class="form-lable">Почта</label>
                <select class="form-control" name="mail" placeholder="" autocomplete = "off" required>
                    <option selected value="m.smolyakov@newbio.su">Смольяков М.Г. (m.smolyakov@newbio.su)</option>
                    <option value="o.romanovskaya@newbio.su">Романовская О.В. (o.romanovskaya@newbio.su)</option>
                    <option value="d.sivolobov@newbio.su">Сиволобов Д.Ю. (d.sivolobov@newbio.su)</option>
                </select>
            </div>
            <div class="form-group my-2">
                <label class="form-lable">Наименование</label>
                <textarea style="resize: none;" name="name" class="form-control" rows="3" placeholder="" autocomplete = "off" required></textarea>
            </div>
            <div class="form-group my-2">
                <label class="form-lable">Производитель</label>
                <textarea style="resize: none;" name="generator" class="form-control" rows="3" placeholder="" autocomplete = "off"></textarea>
            </div>
            <div class="form-group my-2">
                <label class="form-lable">Артикул</label>
                <textarea style="resize: none;" name="artikul" class="form-control" rows="3" placeholder="" autocomplete = "off"></textarea>
            </div>
            <div class="form-group my-2">
                <label class="form-lable">Обоснование</label>
                <textarea style="resize: none;" name="message" class="form-control" rows="12" placeholder="" autocomplete = "off" required></textarea>
            </div>
            <button class='btn btn-primary mt-3 w-100 btn-lg'>Отправить</button>
        </form>
    </div>
</x-app-layout>