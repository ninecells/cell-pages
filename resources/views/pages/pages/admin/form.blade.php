@extends('ncells::admin.app')

@section('page-title', '"<a href="/pages/'.$page->slug.'">'.$page->title.'</a>" 페이지 편집')
@section('page-description', '페이지를 편집합니다')

@section('content')
@include('ncells::pages.parts.admin.top_tab', ['type' => 'edit'])
<form method="post" action="/admin/pages/update">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="form-group">
        <label for="title">제목</label>
        <input type="text" class="form-control"
               id="title" name="title" placeholder="제목" value="{{ $page->title }}"
               {{ $page->exists() ? 'readonly ' : '' }}/>
    </div>
    <div class="form-group">
        <label for="content">내용</label>
        <textarea class="form-control" id="content" name="content" placeholder="내용"
                  rows="20">{{ $page->content }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">저장</button>
</form>
@endsection
