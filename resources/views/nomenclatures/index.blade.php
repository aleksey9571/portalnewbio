<x-app-layout>
    <div class="container">
        <div class="d-flex">
            <div class="p-2 w-100 fs-1 mb-3">Перечень номенклатуры</div>
            <div class="p-2 flex-shrink-1">
                <a href="{{ route('nomenclature_storage') }}" type="button" class="btn btn-primary position-relative ms-3">
                    Корзина
                </a>
            </div>
        </div>
        <p class="text-center fs-3 my-4">{{ $heading }}</p>
        <header class="d-flex justify-content-center py-3">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a href="{{ route('nomenclature_form_send_add_nomenclature') }}" type="button" class="btn btn-primary position-relative ms-3">
                    Добавить номенклатуру
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('nomenclature_category') }}" type="button" class="btn btn-primary position-relative ms-3">
                    Категории
                </a>
            </li>
            <form action="{{ route('nomenclature_search') }}" method="post">
                @csrf
                <div class="row ms-3">
                    <div class="col-6">
                        <li class="nav-items">
                            <input type="" required class="form-control" name="search" placeholder="Поиск...">
                        </li>
                    </div>
                    <div class="col-6">
                        <li class="nav-items">
                            <button class='btn btn-primary' type='submit'>Поиск</button>
                        </li>
                    </div>
                </div>
            </form>
        </ul>
        </header>
    </div>
    <div class="container">
        <div class="d-flex justify-content-center fs-4 my-4">
            <ul class="pagination pagination-sm ">
                @foreach($pages as $el)
                    @if($el == $id)
                        <li class="page-item active"><a class="page-link" href="{{ route('nomenclature', $el) }}">{{ $el }}</a></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ route('nomenclature', $el) }}">{{ $el }}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
        <table class="table table-hover table-striped">
            <tr>
                <th scope="col">Код</th>
                <th scope="col">Наименование</th>
                <th scope="col">Ед. имерения</th>
                <th scope="col"></th>
            </tr>
            @foreach($data as $el)
                <tr>
                    <form action='{{ route("nomenclature_add_storage") }}' method='post'>
                        @csrf
                        <input type="hidden" name="id" value='{{ $el->id }}'>
                        <input type="hidden" name="page" value='{{ $id }}'>
                        <td>{{ $el->key }}</td>
                        <td>{{ $el->name }}</td>
                        <td>{{ $el->unit }}</td>
                        <td><button class='btn btn-primary'>Добавить</button></td>
                    </form>
                </tr>
            @endforeach
        </table>
        <div class="d-flex justify-content-center fs-4 my-4">
            <ul class="pagination pagination-sm ">
                @foreach($pages as $el)
                    @if($el == $id)
                        <li class="page-item active"><a class="page-link" href="{{ route('nomenclature', $el) }}">{{ $el }}</a></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ route('nomenclature', $el) }}">{{ $el }}</a></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
