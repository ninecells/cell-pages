@extends('ncells::admin.app')

@section('content')
@include('ncells::pages.parts.admin.top_tab', ['type' => 'edit'])
<form method="post" action="/admin/pages/update">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="form-group">
        <label for="title">제목</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="제목" value="{{ $page->title }}">
    </div>
    <div class="form-group">
        <label for="content">내용</label>
        <textarea class="form-control" id="content" name="content" placeholder="내용"
                  rows="20">{{ $page->content }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">저장</button>
    <a href="/pages/{{ $page->slug }}" class="btn btn-default">미리보기</a>
</form>
@endsection