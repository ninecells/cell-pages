<?php
use NineCells\Pages\Models\History;
$hs = History::orderBy('id', 'desc')->paginate(10);
?>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>제목</th>
        <th>변경</th>
        <th>변경한 사람</th>
    </tr>
    </thead>
    <tbody>
    @foreach ( $hs as $h )
    <tr>
        <td><a href="/wiki/{{ $h->slug }}/{{ $h->rev }}">{{ $h->title }}</a></td>
        <td>{{ $h->created_at->diffForHumans() }}</td>
        <td><a href="/members/{{ $h->writer_id }}">{{ $h->writer->name }}</a></td>
    </tr>
    @endforeach
    </tbody>
</table>
{!! $hs->links() !!}

