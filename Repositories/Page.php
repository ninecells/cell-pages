<?php

namespace NineCells\Pages\Repositories;

use NineCells\Pages\Models\PagesPage;

class Page
{
    public static function getPage($key)
    {
        $key = trim($key);
        $key = preg_replace('/\s+/', ' ', $key);
        $page = PagesPage::where('slug', self::slug($key))->first();
        if (!$page) {
            $page = PagesPage::where('title', $key)->first();
            if (!$page) {
                $page = new PagesPage();
                $page->rev = 0;
                $page->title = $key;
                $page->slug = null; // view 에서 slug가 없으면 title을 사용하므로 null 처리
                $page->content = '';
            }
        }
        return $page;
    }

    public static function slug($title, $separator = '-')
    {
        $flip = $separator == '-' ? '_' : '-';
        $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);
        $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', mb_strtolower($title));
        $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);
        return trim($title, $separator);
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