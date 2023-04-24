<x-app-layout>
    <div class="container">
        <div class="d-flex justify-content-center fs-1 mb-3">Заявки ТМЦ</div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Номер заявки</th>
                    <th scope="col">Дата</th>
                    <th scope="col">#</th>
                    <th scope="col">#</th>
                    <th scope="col">#</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $el)
                <tr>
                    <th>{{ $el->id }}</th>
                    <td>{{ $el->created_at }}</td>
                    <td><a href="{{ route('nomenclature_requestsget', $el->id) }}" type="button" class="btn btn-primary position-relative ms-3">Скачать</a></td>
                    <td><a href="{{ route('nomenclature_repeatTmc', $el->id) }}" type="button" class="btn btn-primary position-relative ms-3">Повтор</a></td>
                    <td><a href="{{ route('nomenclature_repeatTmc', $el->id) }}" type="button" class="btn btn-primary position-relative ms-3">Повтор</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>