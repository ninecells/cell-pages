@extends('ncells::admin.app')

@section('title', $page->title.' #'.$rev)
@section('page-title', $page->title.' #'.$rev)

@section('content')
@include('ncells::pages.parts.admin.top_tab', ['type' => 'history'])
<p>{!! $page->md_content !!}</p>
@endsection
