<x-app-layout>
    <div class="container">
        <header class="d-flex justify-content-center py-3">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a href="{{ route('sklad', 1) }}" type="button" class="btn btn-primary position-relative ms-3">
                    Назад
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('skladExcel') }}" type="button" class="btn btn-primary position-relative ms-3">
                    Сформировать заявку
                </a>
            </li>
        </ul>
        </header>
    </div>
    <div class="container">
        <table class="table table-hover table-striped">
            <tr>
                <th scope="col">Код</th>
                <th scope="col">Наименование</th>
                <th scope="col">Ед. имерения</th>
                <th scope="col">Количество доступное на складе</th>
                <th scope="col">Количество</th>
                <th scope="col"></th>
            </tr>
            @foreach($data as $el)
                <tr>
                    <td>{{ $el->key }}</td>
                    <td>{{ $el->name }}</td>
                    <td>{{ $el->unit }}</td>
                    <td>{{ $el->quantity }}</td>
                    <td>
                        <form action='{{ route("sklad_storage_edit") }}' method='post'>
                            @csrf
                            <input type="hidden" name="id" value='{{ $el->id }}'>
                            <input type="number" min="1" max="{{ $el->quantity }}" value="{{ $el->count }}" name="count" onchange="this.form.submit()"/>
                        </form>
                    </td>
                    <form action='{{ route("storage_delete") }}' method='post'>
                        @csrf
                        <input type="hidden" name="id" value='{{ $el->id }}'>
                        <td><button class='btn btn-primary'>Удалить</button></td>
                    </form>
                </tr>
            @endforeach
        </table>
    </div>
</x-app-layout>