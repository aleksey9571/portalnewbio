<x-app-layout>
    <div class="container">
        <div class="d-flex justify-content-center fs-1 mb-3">Личный кабинет</div>
        <div>
            <div style="width: 20%; float: left;">

                @if (Auth::user()->id == 2 || Auth::user()->id == 9 || Auth::user()->id == 11 || Auth::user()->id == 12)
                    <div>
                        <a href="{{ route('nomenclature_requests', 0) }}" class="ms-3">
                            Сформированные заявки
                        </a>
                    </div>
                @endif

                <div>
                    <a href="{{ route('nomenclature_requests', Auth::user()->id) }}" class="ms-3">
                        Мои заявки
                    </a>
                </div>

                @if (Auth::user()->id == 2)
                    <div>
                        <a href="{{ route('skladGet') }}" type="button" class="ms-3">
                            Обновить базу cклад
                        </a>
                    </div>                    
                @endif

                @if (Auth::user()->id == 2)
                    <div>
                        <a href="{{ route('nomenclature_nomenclatureGet') }}" type="button" class="ms-3">
                            Обновить базу тмц
                        </a>
                    </div>
                @endif
<!--
                <div>
                    <a href="{{ route('nomenclature_requests', Auth::user()->id) }}" class="ms-3">
                        Статус заявок
                    </a>
                </div>
-->
                <div>
                    <a href="{{ route('downloadSklad') }}" class="ms-3">
                        Скачать остатки XLS
                    </a>
                </div>
                
            </div>
            <div style="width: 80%; float: right;">
            <div style="height: 260px; border-left: 1px solid black; float: left;"></div>
                <div class="d-flex justify-content-evenly">
                    @foreach($data as $el)
                    <!-- Фото профиля
                    <div>
                        <img style="width: 250px; width: 250px;" src="./photo/{{ $el->photo }}">
                    </div>
                    -->
                    <div>
                        <div style="height: 100px;">
                            <p style="width: 300px;" class="fs-6">Фамилия</p>
                            <p style="width: 300px;" class="fs-3">{{ $el->surname }}</p>
                        </div>
                        <div style="height: 100px;">
                            <p style="width: 300px;" class="fs-6">Имя</p>
                            <p style="width: 300px;" class="fs-3">{{ $el->firstname }}</p>
                        </div>
                        <div style="height: 100px;">
                            <p style="width: 300px;" class="fs-6">Отчество</p>
                            <p style="width: 300px;" class="fs-3">{{ $el->middlename }}</p>
                        </div>
                    </div>
                    <div>
                        <div style="height: 200px;">
                            <p style="width: 300px;" class="fs-6">Должность</p>
                            <p style="width: 300px;" class="fs-3 text-wrap">{{ $el->post }}</p>
                        </div>
                        <div style="height: 100px;">
                            <p style="width: 300px;" class="fs-6">Почта</p>
                            <p style="width: 300px;" class="fs-3">{{ $el->email }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
