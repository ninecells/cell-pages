<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"{!! $type === 'view' ? ' class="active"' : '' !!}>
        <a href="/admin/pages/{{ $page->slug or $page->title }}" aria-controls="view" role="tab">보기</a>
    </li>
    @if ($page->slug)
    <li role="presentation"{!! $type === 'history' ? ' class="active"' : '' !!}>
        <a href="/admin/pages/{{ $page->slug }}/history" aria-controls="history" role="tab">역사</a>
    </li>
    @endif
    @can('page-write')
    <li role="presentation"{!! $type === 'edit' ? ' class="active"' : '' !!}>
        <a href="/admin/pages/{{ $page->slug or $page->title }}/edit" aria-controls="edit" role="tab">편집</a>
    </li>
    @endcan
</ul>
