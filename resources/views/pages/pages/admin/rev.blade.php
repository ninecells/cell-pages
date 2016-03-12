@extends('ncells::admin.app')

@section('title', $page->title.' #'.$rev)
@section('page-title', $page->title.' #'.$rev)

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                @include('ncells::pages.parts.admin.top_tab', ['type' => null])
            </div>
            <div class="box-body">
                {!! $page->md_content !!}
            </div>
        </div>
    </div>
</div>
@endsection
