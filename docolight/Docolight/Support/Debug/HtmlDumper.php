<?php

namespace Docolight\Support\Debug;

use Symfony\Component\VarDumper\Dumper\HtmlDumper as SymfonyHtmlDumper;

/**
 * Customize the color of Symfony dumper.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class HtmlDumper extends SymfonyHtmlDumper
{
    /**
     * Colour definitions for output.
     *
     * @var array
     */
    protected $styles = [
        'default' => 'background-color:#222222; color:#F8F8F2; line-height:1.2em; font-weight:normal; font:12px Monaco, Consolas, "Droid Sans Mono", "Ubuntu Mono", monospace; word-wrap: break-word; white-space: pre-wrap; position:relative; z-index:100000; width: 100%; text-align: left !important;',
        'num' => 'font-weight:bold; color:#ae81ff',
        'const' => 'font-weight:bold',
        'str' => 'font-weight:bold; color:#F92672',
        'cchr' => 'font-style:italic',
        'note' => 'font-weight:bold; color:#89BDFF',
        'ref' => 'color:#839496',
        'solo-ref' => 'color:#839496',
        'public' => 'color:#E6DB74',
        'protected' => 'font-style:italic; color:#ff9100',
        'private' => 'font-style:italic; color:#fd2929',
        'meta' => 'color:#b729d9',
        'key' => 'color:#FD5FF1',
        'index' => 'color:#FD5FF1',
    ];
}
