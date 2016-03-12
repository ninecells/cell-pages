<?php
use NineCells\Pages\Models\History;

$hs = History::orderBy('id', 'desc')->paginate(10);
?>

@extends('ncells::admin.app')

@section('title', '최근 변경 페이지')
@section('page-title', '최근 변경 페이지')
@section('page-description', '최근 변경 순으로 정렬된 페이지 목록입니다')

@section('content')
<style>
    table .title {
        width: 100%;
    }

    table .created,.writer {
        width: auto;
        white-space: nowrap;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }
</style>
<table class="table table-bordered">
    <thead>
    <tr>
        <th class="writer">변경한 사람</th>
        <th class="created">변경</th>
        <th class="title">제목</th>
    </tr>
    </thead>
    <tbody>
    @foreach ( $hs as $h )
    <tr>
        <td class="writer"><a href="/members/{{ $h->writer_id }}">{{ $h->writer->name }}</a></td>
        <td class="created">{{ $h->created_at->diffForHumans() }}</td>
        <td class="title">
            <a href="/pages/{{ $h->slug }}">{{ $h->title }}</a>
            (<a href="/admin/pages/{{ $h->slug }}/edit">편집</a>)
            (<a href="/admin/pages/{{ $h->slug }}/history">역사</a>)
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
{!! $hs->links() !!}
@endsection
