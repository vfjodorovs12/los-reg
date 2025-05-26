@extends('web::layouts.grids.12')

@section('title', 'Лос Рег')
@section('page_header', 'Лос Рег')

@section('full')
    <div class="panel panel-default">
        <div class="panel-heading">Незарегистрированные члены корпорации</div>
        <div class="panel-body">
            @if($error)
                <div style="color:red;">{{ $error }}</div>
            @elseif(empty($members))
                <p>Все члены корпорации зарегистрированы!</p>
            @else
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Character ID</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                            <tr>
                                <td>{{ $member['character_id'] }}</td>
                                <td>{{ $member['name'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
