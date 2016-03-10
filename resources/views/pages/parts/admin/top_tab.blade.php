<ul class="nav nav-tabs" role="tablist">
    @can('page-write')
    <li role="presentation"{!! $type === 'edit' ? ' class="active"' : '' !!}>
        <a href="/admin/pages/{{ $page->slug or $page->title }}/edit" aria-controls="edit" role="tab">편집</a>
    </li>
    @endcan

    @if ($page->slug)
    <li role="presentation"{!! $type === 'history' ? ' class="active"' : '' !!}>
        <a href="/admin/pages/{{ $page->slug }}/history" aria-controls="history" role="tab">역사</a>
    </li>
    @endif
</ul>
