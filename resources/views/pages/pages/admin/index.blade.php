<?php
use NineCells\Pages\Models\History;

$hs = History::orderBy('id', 'desc')->paginate(10);
?>

@extends('ncells::admin.app')

@section('page-title', '최근 변경 페이지')
@section('page-description', '최근 변경 순으로 정렬된 페이지 목록입니다')

@section('content')
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
        <td><a href="/admin/pages/{{ $h->slug }}/edit">{{ $h->title }}</a></td>
        <td><a href="/pages/{{ $h->slug }}">페이지 이동</a></td>
        <td>{{ $h->created_at->diffForHumans() }}</td>
        <td><a href="/members/{{ $h->writer_id }}">{{ $h->writer->name }}</a></td>
    </tr>
    @endforeach
    </tbody>
</table>
{!! $hs->links() !!}
@endsection
