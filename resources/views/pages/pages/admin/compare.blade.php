@extends('ncells::admin.app')

@section('title', '페이지 비교')
@section('page-title', '"<a href="/pages/'.$page->slug.'">'.$page->title.'</a>" 페이지 비교')
@section('page-description', '두 페이지의 차이를 비교합니다')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                @include('ncells::pages.parts.admin.top_tab', ['type' => null])
            </div>
            <div class="box-body">
                <div class="panecontainer">
                    <div id="htmldiff" class="pane" style="white-space:pre-wrap"><?php echo $rendered_diff; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('head')
<style type="text/css">
    ins {
        color: green;
        background: #dfd;
        text-decoration: none
    }

    del {
        color: red;
        background: #fdd;
        text-decoration: none
    }

    .panecontainer > p {
        margin: 0;
        border: 1px solid #bcd;
        border-bottom: none;
        padding: 1px 3px;
        background: #def;
        font: 14px sans-serif
    }

    .panecontainer > p + div {
        margin: 0;
        padding: 2px 0 2px 2px;
        border: 1px solid #bcd;
        border-top: none
    }

    .pane {
        margin: 0;
        padding: 0;
        border: 0;
        width: 100%;
        min-height: 20em;
        overflow: auto;
        font: 12px monospace
    }

    #htmldiff {
        color: gray
    }

    #htmldiff.onlyDeletions ins {
        display: none
    }

    #htmldiff.onlyInsertions del {
        display: none
    }
</style>
@endsection
