@extends('web::layouts.app')

@section('content')
<h1>Состав корпорации: майны и альты</h1>

<div class="mb-3">
    <input type="text" id="filter" placeholder="Фильтр по имени..." onkeyup="filterTable()" class="form-control" />
</div>

<table class="table table-bordered table-hover" id="membersTable">
    <thead>
        <tr>
            <th onclick="sortTable(0)">Character ID ▲▼</th>
            <th onclick="sortTable(1)">Name ▲▼</th>
            <th onclick="sortTable(2)">Майн/Альт ▲▼</th>
            <th onclick="sortTable(3)">Status ▲▼</th>
            <th onclick="sortTable(4)">Дата вступления ▲▼</th>
            <th onclick="sortTable(5)">Дата последнего посещения ▲▼</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($members as $member)
            <tr>
                <td>{{ $member->character_id }}</td>
                <td>{{ $member->character_name }}</td>
                <td>{{ $member->main_alt }}</td>
                <td>
                    @if ($member->status == 'Online')
                        <span class="text-success">Online</span>
                    @else
                        <span class="text-secondary">Offline</span>
                    @endif
                </td>
                <td>{{ $member->start_date_fmt }}</td>
                <td>{{ $member->logoff_date_fmt }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
function filterTable() {
    var input = document.getElementById("filter");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("membersTable");
    var tr = table.getElementsByTagName("tr");
    for (var i = 1; i < tr.length; i++) {
        var td = tr[i].getElementsByTagName("td")[1];
        if (td) {
            var txtValue = td.textContent || td.innerText;
            tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
        }
    }
}

function sortTable(n) {
    var table = document.getElementById("membersTable");
    var rows = Array.from(table.rows).slice(1);
    var asc = table.asc = !table.asc;
    rows.sort(function(a, b) {
        var x = a.cells[n].textContent.trim();
        var y = b.cells[n].textContent.trim();
        return (x === y ? 0 : (x > y ? 1 : -1)) * (asc ? 1 : -1);
    });
    rows.forEach(row => table.tBodies[0].appendChild(row));
}
</script>
@endsection
