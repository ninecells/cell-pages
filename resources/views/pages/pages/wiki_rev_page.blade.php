@extends('ncells::jumbotron.app')

@section('content')

@include('ncells::pages.parts.top_menu')
@include('ncells::pages.parts.top_tab', ['type' => 'view'])

<h1>{{ $page->title }} - Rev. {{ $rev }}</h1>
<p>{!! $page->md_content !!}</p>

@endsection
