<?php

namespace NineCells\Pages\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use NineCells\Pages\Repositories\Page;

class PagesController extends Controller
{
    public function GET_page($key = 'Main')
    {
        $page = Page::getPage($key);
        $slug = $page->slug;

        if ($slug && $slug != $key) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 redirect 한다.
            return redirect("/pages/$slug", 301);
        }

        Page::setMetaTags($page);

        return view('ncells::pages.pages.wiki_page', ['page' => $page]);
    }
}
