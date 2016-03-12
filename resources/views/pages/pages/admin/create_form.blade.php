@extends('ncells::admin.app')

@section('title', '페이지 생성')
@section('page-title', '페이지 생성')
@section('page-description', '페이지를 생성합니다')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <form method="post" action="/admin/pages/update">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label for="title">제목</label>
                        <input type="text" class="form-control"
                               id="title" name="title" placeholder="제목" value="" />
                    </div>
                    <div class="form-group">
                        <label for="content">내용</label>
                        <textarea class="form-control" id="content" name="content" placeholder="내용" rows="20"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">저장</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
