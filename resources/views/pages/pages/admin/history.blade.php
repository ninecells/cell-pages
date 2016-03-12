@extends('ncells::admin.app')

@section('page-title', '"'.$page->title.'" 페이지 역사')
@section('page-description', '페이지가 수정된 이력입니다')

@section('content')
@include('ncells::pages.parts.admin.top_tab', ['type' => 'history'])
<p>
    <a id="btn-compare" href="#" class="btn btn-success" data-page-slug="{{ $page->slug }}">비교하기</a>
</p>
<style>
    table .title {
        width: 100%;
    }

    table .rev,.left,.right,.created,.writer {
        width: auto;
        white-space: nowrap;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }
</style>
<table class="table table-bordered">
    <thead>
    <tr>
        <th class="rev">#</th>
        <th class="left">L</th>
        <th class="right">R</th>
        <th class="title">제목</th>
        <th class="created">변경</th>
        <th class="writer">변경한 사람</th>
    </tr>
    </thead>
    <tbody>
    @foreach ( $histories as $history )
    <tr>
        <td class="rev">{{ $history->rev }}</td>
        <td class="left"><input name="left" type="radio" value="{{ $history->rev }}"/></td>
        <td class="right"><input name="right" type="radio" value="{{ $history->rev }}"/></td>
        <td class="title"><a href="/admin/pages/{{ $history->slug }}/{{ $history->rev }}">{{ $history->title }}</a></td>
        <td class="created">{{ $history->created_at->diffForHumans() }}</td>
        <td class="writer"><a href="/members/{{ $history->writer_id }}">{{ $history->writer->name }}</a></td>
    </tr>
    @endforeach
    </tbody>
</table>
@endsection

@section('script')
<script>
    $(function () {
        $('#btn-compare').click(function (e) {
            var left = $('input[name=left]:checked').val(),
                right = $('input[name=right]:checked').val(),
                url = '/admin/pages/' + $(this).data('page-slug') + '/compare/' + left + '/' + right;
            if (!left) {
                alert('L을 선택하세요');
                return false;
            }
            if (!right) {
                alert('R을 선택하세요');
                return false;
            }
            window.location.href = url;
            return false;
        });
    });
</script>
@endsection