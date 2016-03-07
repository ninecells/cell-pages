<?php

namespace NineCells\Pages\Models;

class PageParsedown extends \Parsedown
{
    function __construct()
    {
        $this->InlineTypes['['][] = 'WikiLink';

        $this->inlineMarkerList .= '[';
    }

    protected function inlineWikiLink($Excerpt)
    {
        if (preg_match('/^\[\[wiki:(.*?)\]\]/', $Excerpt['text'], $matches)) {
            return [
                'extent' => strlen($matches[0]),
                'element' => [
                    'name' => 'div',
                    'text' => view('ncells::pages.parts.wikitag_' . $matches[1]),
                ],
            ];
        }

        if (preg_match('/^\[\[(.*?)\]\]/', $Excerpt['text'], $matches)) {
            return [
                'extent' => strlen($matches[0]),
                'element' => [
                    'name' => 'a',
                    'text' => $matches[1],
                    'attributes' => [
                        'href' => '/pages/' . $matches[1],
                    ],
                ],
            ];
        }
    }
}