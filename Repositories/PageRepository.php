<?php

namespace NineCells\Page\Repositories;

use FineDiff;
use NineCells\Page\Models\Archive;
use NineCells\Page\Models\Page;

class PageRepository
{
    public static function getPage($key)
    {
        $key = trim($key);
        $key = preg_replace('/\s+/', ' ', $key);
        $page = Page::where('slug', self::slug($key))->first();
        if (!$page) {
            $page = Page::where('title', $key)->first();
            if (!$page) {
                $page = new Page();
                $page->rev = 0;
                $page->title = $key;
                $page->slug = null; // view 에서 slug가 없으면 title을 사용하므로 null 처리
                $page->content = '';
            }
        }
        return $page;
    }

    public static function getRevPage($page_id, $rev)
    {
        $page = Archive::where('pages_page_id', $page_id)
            ->where('rev', $rev)
            ->first();

        return $page;
    }

    public static function getPageHistories($page_id)
    {
        $histories = Archive::where('pages_page_id', $page_id)
            ->with('writer')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return $histories;
    }

    public static function slug($title, $separator = '-')
    {
        $flip = $separator == '-' ? '_' : '-';
        $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);
        $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', mb_strtolower($title));
        $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);
        return trim($title, $separator);
    }

    public static function getDiffHtml($l_content, $r_content)
    {
        include "finediff.php";
        $l_text = mb_convert_encoding($l_content, 'HTML-ENTITIES', 'UTF-8');
        $r_text = mb_convert_encoding($r_content, 'HTML-ENTITIES', 'UTF-8');
        $opcodes = FineDiff::getDiffOpcodes($l_text, $r_text, [
            FineDiff::paragraphDelimiters,
            FineDiff::sentenceDelimiters,
            FineDiff::wordDelimiters,
            ';',
        ]);
        $rendered_diff = FineDiff::renderDiffToHTMLFromOpcodes($l_text, $opcodes);
        $rendered_diff = mb_convert_encoding($rendered_diff, 'UTF-8', 'HTML-ENTITIES');
        $rendered_diff = str_replace('\r\n', '\n', $rendered_diff);
        $rendered_diff = str_replace('\r', '\n', $rendered_diff);
        $rendered_diff = str_replace('\n', '&nbsp;<br/>', $rendered_diff);

        return $rendered_diff;
    }

    public static function archive($title, $content, $writer_id)
    {
        $slug = PageRepository::slug($title);

        $page = PageRepository::getPage($title);
        $page->rev = $page->rev + 1;
        $page->title = $title;
        $page->slug = $slug;
        $page->content = $content;
        $page->writer_id = $writer_id;
        $page->save();

        $history = new Archive();
        $history->pages_page_id = $page->id;
        $history->rev = $page->rev;
        $history->title = $page->title;
        $history->slug = $page->slug;
        $history->content = $page->content;
        $history->writer_id = $page->writer_id;
        $history->created_at = $page->updated_at;
        $history->updated_at = $page->updated_at;
        $history->save();

        return $page;
    }

    public static function setMetaTags($page)
    {
        // 메타 지정
        config(['title' => $page->title]);
        config(['og:title' => $page->title]);

        // 문서가 만들어진 경우에만 입력할 수 있는 meta 생성
        if ($page->slug) {
            $desc = strip_tags($page->md_content);
            $desc = str_replace("\r\n", "\n", $desc);
            $desc = str_replace("\r", " ", $desc);
            $desc = str_replace("\n", " ", $desc);
            $desc = self::limit_words($desc, 30);

            $keys = self::limit_words($page->title, 20);
            $keys = explode(' ', $keys);
            $keys = implode(',', $keys);

            config(['author' => $page->writer->name]);
            config(['description' => $desc]);
            config(['keywords' => $keys]);
            config(['og:description' => $desc]);
        }
    }

    public static function limit_words($words, $limit, $append = ' &hellip;')
    {
        // Add 1 to the specified limit becuase arrays start at 0
        $limit = $limit + 1;
        // Store each individual word as an array element
        // Up to the limit
        $words = explode(' ', $words, $limit);
        // Shorten the array by 1 because that final element will be the sum of all the words after the limit
        array_pop($words);
        // Implode the array for output, and append an ellipse
        $words = implode(' ', $words) . $append;
        // Return the result
        return $words;
    }
}