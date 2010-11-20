<?php


final class Core_Util_Format
{
    
    
    public static function textHtmlFilter($text)
    {
        $text = self::autoParagraph($text);
        
        /**
         * '&' (ampersand) becomes '&a$curl = str_replace(array("\r\n", "\r", "\n"), "<br />", $curl);mp;'
         * '<' (less than) becomes '&lt;' 
         * '>' (greater than) becomes '&gt;'
         */
        //$text = htmlspecialchars($text, ENT_NOQUOTES);
        
        $textarr = preg_split('/(<.*>|\[.*\])/Us', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        $text = '';
        $next = true;
        $has_pre_parent = false;
        foreach ($textarr as $curl) {
            if (!empty($curl) && '<' != $curl{0} && '[' != $curl{0} && $next && !$has_pre_parent) { // If it's not a tag
                $curl = str_replace("  ", " &nbsp;&nbsp;", $curl);
                //$curl = str_replace(" ", '&#160;', $curl);
                //$curl = str_replace(array("\r\n", "\r", "\n"), "\n", $curl);
            } elseif (strpos($curl, '<code') !== false || strpos($curl, '<kbd') !== false || strpos($curl, '<style') !== false || strpos($curl, '<script') !== false) {
                $next = false;
            } elseif (strpos($curl, '<pre') !== false) {
                $has_pre_parent = true;
            } elseif (strpos($curl, '</pre>') !== false) {
                $has_pre_parent = false;
            } else {
                $next = true;
            }
            
            $text .= $curl;
        }
        
        return self::stripScript($text);
    }
    
    public static function richEditFilter($text)
    {
        $text = self::autoParagraph($text);
        
        return self::stripScript($text);
    }

    
    public static function autoParagraph($text)
    {
        $text = preg_replace('|<br />\s*<br />|', "\n\n", $text);
        
        // Space things out a little
        $allblocks = '(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|map|area|blockquote|address|math|style|input|p|h[1-6]|hr)';
        
        $text = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $text);
        $text = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $text);
        
        $text = preg_replace('!(<pre[^>]*>)!', "\n\n$1", $text);
        $text = preg_replace('!(</pre>)!', "$1\n\n", $text);
        
        $text = str_replace(array("<br />", "<br>", "\r\n", "\r"), "\n", $text);  // cross-platform newlines
        
        $text = preg_replace("/\n\n+/", "\n\n", $text); // take care of duplicates
            
        // make paragraphs, including one at the end
        $texts = preg_split('/\n\s*\n/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $text = '';
        $has_pre_parent = false;
        foreach ($texts as $tinkle) {

            //$tinkle = trim($tinkle, "\n");

            if ($has_pre_parent || strpos($tinkle, '<pre') !== false) {

                $tinkle = str_replace('<br />', '', $tinkle);
                $tinkle = str_replace('<p>', "\n", $tinkle);
                $text .= str_replace('</p>', '', $tinkle);

                if (strpos($tinkle, '</pre>') !== false) {
                    $has_pre_parent = false;
                } else {
                    $has_pre_parent = true;
                }

            } else {
                $tinkle = str_replace("\n", "<br />\n", $tinkle);
                $text .= '<p>'. $tinkle ."</p>\n";
            }
            
        }
        
        $text = preg_replace('|<p>\s*?</p>|', '', $text); // under certain strange conditions it could create a P of entirely whitespace
        $text = preg_replace('!<p>([^<]+)\s*?(</(?:div|address|form)[^>]*>)!', "<p>$1</p>$2", $text);
        $text = preg_replace( '|<p>|', "$1<p>", $text );
    
        $text = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $text); // don't convert all over a tag
        
        $text = preg_replace("|<p>(<li.+?)</p>|", "$1", $text); // problem with nested lists
        
        $text = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $text);
        $text = str_replace('</blockquote></p>', '</p></blockquote>', $text);
    
        $text = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $text);
        $text = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $text);
        
        //
        $text = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $text);
        $text = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $text);

        $text = preg_replace("|\n</p>$|", '</p>', $text);
        
        return $text;
    }
    

    public static function stripScript($text)
    {
        $farr = array(
            "/<(\/?)(script|iframe|style|html|body|title|link|meta|form|input|\?|\%)([^>]*?)>/isU",
            "/(<[^>]*)on[a-zA-Z] \s*=([^>]*>)/isU",
        );
        
        $tarr = array(
            "&lt;\\1\\2\\3&gt;",
            "\\1\\2",
        );
        
        return preg_replace($farr, $tarr, $text);
    }

}
