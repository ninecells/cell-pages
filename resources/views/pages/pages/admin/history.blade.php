@extends('ncells::admin.app')

@section('title', '페이지 역사')
@section('page-title', '"<a href="/pages/'.$page->slug.'">'.$page->title.'</a>" 페이지 역사')
@section('page-description', '페이지가 수정된 이력입니다')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                @include('ncells::pages.parts.admin.top_tab', ['type' => 'history'])
            </div>
            <div class="box-body">
                <style>
                    table .title {
                        width: 100%;
                    }

                    table .rev, .left, .right, .created, .writer {
                        width: auto;
                        white-space: nowrap;
                    }

                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }
                </style>
                <p>
                    <a id="btn-compare" href="#" class="btn btn-success" data-page-slug="{{ $page->slug }}">비교하기</a>
                </p>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="rev">#</th>
                        <th class="left">L</th>
                        <th class="right">R</th>
                        <th class="writer">변경한 사람</th>
                        <th class="created">변경</th>
                        <th class="title">제목</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ( $histories as $history )
                    <tr>
                        <td class="rev">{{ $history->rev }}</td>
                        <td class="left"><input name="left" type="radio" value="{{ $history->rev }}"/></td>
                        <td class="right"><input name="right" type="radio" value="{{ $history->rev }}"/></td>
                        <td class="writer"><a href="/members/{{ $history->writer_id }}">{{ $history->writer->name }}</a>
                        </td>
                        <td class="created">{{ $history->created_at->diffForHumans() }}</td>
                        <td class="title">
                            <a href="/admin/pages/{{ $history->slug }}/{{ $history->rev }}">
                                {{ $history->title }} #{{ $history->rev }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@parent
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