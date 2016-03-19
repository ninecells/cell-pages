@extends('ncells::app')

@section('content')
@if ($page->exists())
{!! $page->md_content !!}
@else
<p>준비 중입니다.</p>
@endif
@endsection
