<?php

namespace NineCells\Pages\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use NineCells\Pages\Repositories\Page;

class AdminController extends Controller
{
    public function GET_rev_page($key, $rev)
    {
        $page = Page::getPage($key);

        if (!$page->exists()) {
            // 존재하지 않는 문서면 revision 을 보여줄 수 없으므로 생성 권유 페이지로 이동 시킨다.
            return redirect("/pages/$key");
        }

        if ($page->isTitle($key)) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            return redirect("/pages/{$page->slug}/$rev");
        }

        $page = Page::getRevPage($page->id, $rev);

        if (!$page) {
            // 존재하지 않는 revision 인 경우 문서로 이동
            return redirect("/pages/{$page->slug}");
        }

        Page::setMetaTags($page);

        return view('ncells::pages.pages.wiki_rev_page', ['page' => $page, 'rev' => $rev]);
    }

    public function GET_page_form($key)
    {
        $this->authorize('pages-write');

        $page = Page::getPage($key);

        if ($page->isTitle($key)) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            return redirect("/pages/{$page->slug}/edit");
        }

        Page::setMetaTags($page);

        return view('ncells::pages.pages.wiki_page_form', ['page' => $page]);
    }

    //TODO: 정리필요
    public function PUT_page_form(Request $request)
    {
        $this->authorize('pages-write');
        $title = $request->input('title');
        $content = $request->input('content');
        $slug = Page::slug($title);

        $page = Page::getPage($title);
        $page->rev = $page->rev + 1;
        $page->title = $title;
        $page->slug = $slug;
        $page->content = $content;
        $page->writer_id = Auth::user()->id;
        $page->save();

        $history = new PagesHistory();
        $history->pages_page_id = $page->id;
        $history->rev = $page->rev;
        $history->title = $page->title;
        $history->slug = $page->slug;
        $history->content = $page->content;
        $history->writer_id = $page->writer_id;
        $history->created_at = $page->updated_at;
        $history->updated_at = $page->updated_at;
        $history->save();

        return redirect("/pages/$slug");
    }

    public function GET_page_history($key)
    {
        $page = Page::getPage($key);

        if (!$page->exists()) {
            return redirect("/pages/$key");
        }

        if ($page->isTitle($key)) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            return redirect("/pages/{$page->slug}/history");
        }

        $histories = Page::getPageHistories($page->id);

        Page::setMetaTags($page);

        return view('ncells::pages.pages.wiki_page_history', ['page' => $page, 'histories' => $histories]);
    }

    //TODO: 정리필요
    public function GET_page_compare($key, $left, $right)
    {
        $page = Page::getPage($key);

        if (!$page->exists()) {
            // 존재하지 않는 문서이므로 생성 권장
            return redirect("/pages/$key");
        }

        if ($page->isTitle($key)) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            return redirect("/pages/{$page->slug}/compare/$left/$right");
        }

        $l_page = Page::getRevPage($page->id, $left);
        $r_page = Page::getRevPage($page->id, $right);
        if (!$l_page || !$r_page) {
            // l 과 r 중 하나가 revision 이 없으므로 문서로 이동
            return redirect("/pages/{$page->slug}");
        }

        Page::setMetaTags($page);

        include "filediff.php";
        $opcodes = \FineDiff::getDiffOpcodes($l_page->content, $r_page->content, \FineDiff::characterDelimiters);
        $rendered_diff = \FineDiff::renderDiffToHTMLFromOpcodes($l_page->content, $opcodes);
        $rendered_diff = str_replace('\r\n', '\n', $rendered_diff);
        $rendered_diff = str_replace('\r', '\n', $rendered_diff);
        $rendered_diff = str_replace('\n', '&nbsp;<br/>', $rendered_diff);

        return view('ncells::pages.pages.wiki_compare', ['page' => $page, 'rendered_diff' => $rendered_diff]);
    }
}