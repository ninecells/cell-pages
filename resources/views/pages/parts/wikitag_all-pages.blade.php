<?php
use NineCells\Pages\Models\Page;
$pages = Page::orderBy('id', 'desc')->paginate(10);
?>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>제목</th>
        <th>최근변경</th>
        <th>변경한 사람</th>
    </tr>
    </thead>
    <tbody>
    @foreach ( $pages as $page )
    <tr>
        <td><a href="/pages/{{ $page->slug }}">{{ $page->title }}</a></td>
        <td>{{ $page->updated_at->diffForHumans() }}</td>
        <td><a href="/members/{{ $page->writer_id }}">{{ $page->writer->name }}</a></td>
    </tr>
    @endforeach
    </tbody>
</table>
{!! $pages->links() !!}

