@extends('web::layouts.app')

@section('content')
<h1>Состав корпорации</h1>

@if(isset($log))
  <pre style="color:red">{{ $log }}</pre>
@endif

<div class="mb-3">
    <input type="text" id="filter" placeholder="Фильтр по всем колонкам..." onkeyup="filterTable()" class="form-control" />
</div>

<table class="table table-bordered table-hover" id="membersTable">
    <thead>
        <tr>
            <th onclick="sortTable(0)">Character ID ▲▼</th>
            <th onclick="sortTable(1)">Член корпорации ▲▼</th>
            <th onclick="sortTable(2)">Дата вступления ▲▼</th>
            <th onclick="sortTable(3)">Дата последнего посещения ▲▼</th>
            <th onclick="sortTable(4)">Status ▲▼</th>
            <th onclick="sortTable(5)">Пользователь SEAT ▲▼</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($members as $member)
            <tr>
                <td>{{ $member->character_id }}</td>
                <td>{{ $member->corp_name }}</td>
                <td>{{ $member->start_date_fmt }}</td>
                <td>{{ $member->logoff_date_fmt }}</td>
                <td>
                    @if ($member->status == 'Online')
                        <span class="text-success">Online</span>
                    @else
                        <span class="text-secondary">Offline</span>
                    @endif
                </td>
                <td>{{ $member->seat_name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
function filterTable() {
    var input = document.getElementById("filter");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("membersTable");
    var tr = table.tBodies[0].getElementsByTagName("tr");
    for (var i = 0; i < tr.length; i++) {
        var tds = tr[i].getElementsByTagName("td");
        var show = false;
        for (var j = 0; j < tds.length; j++) {
            if (tds[j] && tds[j].innerText.toUpperCase().indexOf(filter) > -1) {
                show = true;
                break;
            }
        }
        tr[i].style.display = show ? "" : "none";
    }
}

function sortTable(n) {
    var table = document.getElementById("membersTable");
    var rows = Array.from(table.tBodies[0].rows);
    var asc = table.ascCol === n ? !table.asc : true;
    table.asc = asc;
    table.ascCol = n;
    rows.sort(function(a, b) {
        var x = a.cells[n].textContent.trim();
        var y = b.cells[n].textContent.trim();
        if (!isNaN(Date.parse(x)) && !isNaN(Date.parse(y))) {
            return (new Date(x) - new Date(y)) * (asc ? 1 : -1);
        } else if (!isNaN(x) && !isNaN(y)) {
            return (parseFloat(x) - parseFloat(y)) * (asc ? 1 : -1);
        } else {
            return x.localeCompare(y, undefined, {numeric: true, sensitivity: 'base'}) * (asc ? 1 : -1);
        }
    });
    rows.forEach(row => table.tBodies[0].appendChild(row));
}
</script>
@endsection
