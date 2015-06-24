<?php

namespace Docolight\Support;

/**
 * HTML helper.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Html
{
    /**
     * Get text between tags.
     *
     * @param string $string  The string with tags
     * @param string $tagname the name of the tag
     *
     * @return string Text between tags
     */
    public static function text($string, $tagname)
    {
        preg_match("#<\s*?$tagname\b[^>]*>(.*?)</$tagname\b[^>]*>#s", $string, $matches);

        return trim(def($matches, 1, null));
    }
}
