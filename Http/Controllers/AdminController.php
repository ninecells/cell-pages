<?php

namespace NineCells\Pages\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use NineCells\Pages\Models\PagesHistory;
use NineCells\Pages\Repositories\Page;

class AdminController extends Controller
{
    public function GET_rev_page($key, $rev)
    {
        $page = Page::getPage($key);
        $slug = $page->slug;

        if (!$slug) {
            // 존재하지 않는 문서면 revision 을 보여줄 수 없으므로 생성 권유 페이지로 이동 시킨다.
            // 나중에 생성될 수 있는 문서이므로 일시적이동(302)
            return redirect("/pages/$key", 302);
        }

        if ($slug && $slug != $key) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            // url 에 title 보다는 slug 를 권장하므로 영구적이동(301)
            return redirect("/pages/$slug/$rev", 301);
        }

        $page = PagesHistory::where('pages_page_id', $page->id)
            ->where('rev', $rev)
            ->first();

        if (!$page) {
            // 존재하지 않는 revision 인 경우 문서로 일시적이동(302)
            return redirect("/pages/$slug", 302);
        }

        Page::setMetaTags($page);

        return view('ncells::pages.pages.wiki_rev_page', ['page' => $page, 'rev' => $rev]);
    }

    public function GET_page_form($key)
    {
        $this->authorize('pages-write');

        $page = Page::getPage($key);
        $slug = $page->slug;

        if ($slug && $slug != $key) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            // url 에 title 보다는 slug 를 권장하므로 영구적이동(301)
            return redirect("/pages/$slug/edit", 301);
        }

        Page::setMetaTags($page);

        return view('ncells::pages.pages.wiki_page_form', ['page' => $page]);
    }

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
        $slug = $page->slug;

        if (!$slug) {
            return redirect("/pages/$key", 302);
        }

        if ($slug && $slug != $key) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            // url 에 title 보다는 slug 를 권장하므로 영구적이동(301)
            return redirect("/pages/$slug/history", 301);
        }

        $histories = PagesHistory::where('pages_page_id', $page->id)
            ->with('writer')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        Page::setMetaTags($page);

        return view('ncells::pages.pages.wiki_page_history', ['page' => $page, 'histories' => $histories]);
    }

    public function GET_page_compare($key, $left, $right)
    {
        $page = Page::getPage($key);
        $slug = $page->slug;

        if (!$slug) {
            // 존재하지 않는 문서이므로 생성 권장
            return redirect("/pages/$key", 302);
        }

        if ($slug && $slug != $key) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            // url 에 title 보다는 slug 를 권장하므로 영구적이동(301)
            return redirect("/pages/$slug/compare/$left/$right", 301);
        }

        $l_page = PagesHistory::where('pages_page_id', $page->id)->where('rev', $left)->first();
        $r_page = PagesHistory::where('pages_page_id', $page->id)->where('rev', $right)->first();

        if (!$l_page || !$r_page) {
            // l 과 r 중 하나가 revision 이 없으므로 문서로 이동
            return redirect("/pages/$slug", 302);
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