<?php

namespace NineCells\Page\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use NineCells\Page\Repositories\PageRepository;

class AdminController extends Controller
{
    public function GET_rev_page($key, $rev)
    {
        $page = PageRepository::getPage($key);

        if (!$page->exists()) {
            // 존재하지 않는 문서면 revision 을 보여줄 수 없으므로 생성 권유 페이지로 이동 시킨다.
            return redirect("/pages/$key");
        }

        if ($page->isTitle($key)) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            return redirect("/pages/{$page->slug}/$rev");
        }

        $page = PageRepository::getRevPage($page->id, $rev);
        if (!$page) {
            // 존재하지 않는 revision 인 경우 문서로 이동
            return redirect("/pages/{$page->slug}");
        }

        PageRepository::setMetaTags($page);

        return view('ncells::page.pages.admin.rev', ['page' => $page, 'rev' => $rev]);
    }

    public function GET_edit_page_form($key)
    {
        $this->authorize('pages-write');

        $page = PageRepository::getPage($key);

        if ($page->isTitle($key)) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            return redirect("/pages/{$page->slug}/edit");
        }

        PageRepository::setMetaTags($page);

        return view('ncells::page.pages.admin.form', ['page' => $page]);
    }

    public function GET_page_history($key)
    {
        $page = PageRepository::getPage($key);

        if (!$page->exists()) {
            return redirect("/pages/$key");
        }

        if ($page->isTitle($key)) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            return redirect("/pages/{$page->slug}/history");
        }

        $histories = PageRepository::getPageHistories($page->id);

        PageRepository::setMetaTags($page);

        return view('ncells::page.pages.admin.history', ['page' => $page, 'histories' => $histories]);
    }

    public function GET_page_compare($key, $left, $right)
    {
        $page = PageRepository::getPage($key);

        if (!$page->exists()) {
            // 존재하지 않는 문서이므로 생성 권장
            return redirect("/pages/$key");
        }

        if ($page->isTitle($key)) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            return redirect("/pages/{$page->slug}/compare/$left/$right");
        }

        $l_page = PageRepository::getRevPage($page->id, $left);
        $r_page = PageRepository::getRevPage($page->id, $right);
        if (!$l_page || !$r_page) {
            // l 과 r 중 하나가 revision 이 없으므로 문서로 이동
            return redirect("/pages/{$page->slug}");
        }

        PageRepository::setMetaTags($page);

        $diff = PageRepository::getDiffHtml($l_page->content, $r_page->content);

        return view('ncells::page.pages.admin.compare', ['page' => $page, 'rendered_diff' => $diff]);
    }

    public function PUT_edit_page_form(Request $request)
    {
        $this->authorize('pages-write');

        $title = $request->input('title');
        $content = $request->input('content');

        $page = PageRepository::archive($title, $content, Auth::user()->id);

        return redirect("/pages/{$page->slug}");
    }
}