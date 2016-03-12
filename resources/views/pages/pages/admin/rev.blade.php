@extends('ncells::admin.app')

@section('page-title', $page->title.' - Rev. '.$rev)

@section('content')
@include('ncells::pages.parts.admin.top_tab', ['type' => 'history'])
<p>{!! $page->md_content !!}</p>
@endsection
