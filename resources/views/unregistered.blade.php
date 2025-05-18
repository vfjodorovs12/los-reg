@extends('web::layouts.grids.12')

@section('title', 'Без регистрации')

@section('page_header', 'Без регистрации')

@section('full')
    <div class="card">
        <div class="card-header">
            <h3>Пользователи без регистрации</h3>
        </div>
        <div class="card-body">
            @if (isset($unregisteredMembers) && count($unregisteredMembers) > 0)
                <ul>
                    @foreach ($unregisteredMembers as $member)
                        <li>{{ $member }}</li>
                    @endforeach
                </ul>
            @else
                <p>Все пользователи зарегистрированы!</p>
            @endif
        </div>
    </div>
@endsection
