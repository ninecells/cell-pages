@extends('ncells::admin.app')

@section('content')
@include('ncells::pages.parts.admin.top_tab', ['type' => 'history'])
<h1>{{ $page->title }} - Rev. {{ $rev }}</h1>
<p>{!! $page->md_content !!}</p>
@endsection
