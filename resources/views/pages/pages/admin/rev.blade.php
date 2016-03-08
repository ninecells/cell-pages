@extends('ncells::jumbotron.app')

@section('content')
<h1>{{ $page->title }} - Rev. {{ $rev }}</h1>
<p>{!! $page->md_content !!}</p>
@endsection
