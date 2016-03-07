<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"{!! $type === 'view' ? ' class="active"' : '' !!}>
        <a href="/pages/{{ $page->slug or $page->title }}" aria-controls="view" role="tab" data-toggle="tab">보기</a>
    </li>
    @can('pages-write')
    <li role="presentation"{!! $type === 'edit' ? ' class="active"' : '' !!}>
        <a href="/pages/{{ $page->slug or $page->title }}/edit" aria-controls="edit" role="tab" data-toggle="tab">편집</a>
    </li>
    @endcan
    @if ($page->slug)
    <li role="presentation"{!! $type === 'history' ? ' class="active"' : '' !!}>
        <a href="/pages/{{ $page->slug }}/history" aria-controls="history" role="tab" data-toggle="tab">역사</a>
    </li>
    @endif
</ul>
