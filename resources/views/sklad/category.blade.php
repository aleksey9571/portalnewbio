<x-app-layout>
    <div class="container">
        <header class="d-flex justify-content-center py-3">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a href="{{ route('sklad', 1) }}" type="button" class="btn btn-primary position-relative ms-3">
                    Назад
                </a>
            </li>
        </ul>
        </header>
    </div>
    <div class="d-flex justify-content-between flex-wrap">
        <div class="py-3 px-3 flex-fill bd-highlight w-25">
            <a href="{{ route('sklad', 1) }}">Полный список</a>
        </div>
        @foreach($data as $el)
            <div class="py-3 px-3 flex-fill bd-highlight w-25">
                <a href="{{ route('setcategory', $el->id) }}">{{ $el->name }}</a>
            </div>
        @endforeach
    </div>
</x-app-layout>