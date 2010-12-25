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
    
    public static function ubb2html($str, $order="") 
    {
        $match = array(
            "%\[b\](.*?)\[\/b\]%si",
            "%\[center\](.*?)\[\/center\]%si",
            "%\[url\](.*?)\[\/url\]%si",
            "%\[url=(.*?)\](.*?)\[\/url\]%si",
            "%\[img\](.*?)\[\/img\]%si",
            "%\[fieldset=(.*?)\](.*?)\[\/fieldset\]%si"
        );
        $replace = array(
            "<b>$1</b>",
            "<center>$1</center>",
            "<a href=\"$1\" target=_blank>$1</a>",
            "<a href=\"$1\" target=_blank>$2</a>",
            "<a href=\"$1\" target=\"_blank\"><img src=\"$1\" border=\"0\" onload=\"javascript:if(this.width>800)this.width=800\" title=\"点击这里用新窗口浏览图片\"></a>",
            "<fieldset><legend>$1</legend><blockquote>$2</blockquote></fieldset>"
        );
    
        $str = preg_replace($match, $replace, $str);

        return $str ;
    }

    public function ubbClear($str)
    {
        $match = array(
            "%\[b\](.*?)\[\/b\]%si",
            "%\[center\](.*?)\[\/center\]%si",
            "%\[url\](.*?)\[\/url\]%si",
            "%\[url=(.*?)\](.*?)\[\/url\]%si",
            "%\[img\](.*?)\[\/img\]%si",
            "%\[fieldset=(.*?)\](.*?)\[\/fieldset\]%si"
        );
        $replace = array(
            "$1",
            "$1",
            "$1",
            "$1",
            "",
            "$2"
        );
        $str = preg_replace($match, $replace, $str);
        return $str;
    }
    
    /**
     * function cutstr()
     * from Discuz_5.5.0_SC_UTF8
     */
    function cutstr($string, $length, $dot = ' ...') {

	    if (strlen($string) <= $length) {
	    	return $string;
	    }
	
	    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
	    $strcut = '';
	    if (true) { // fix utf-8
	    	$n = $tn = $noc = 0;
	    	while ($n < strlen($string)) {
	    		$t = ord($string[$n]);
	    		if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
	    			$tn = 1; $n++; $noc++;
	    		} elseif (194 <= $t && $t <= 223) {
	    			$tn = 2; $n += 2; $noc += 2;
	    		} elseif (224 <= $t && $t < 239) {
	    			$tn = 3; $n += 3; $noc += 2;
	    		} elseif (240 <= $t && $t <= 247) {
	    			$tn = 4; $n += 4; $noc += 2;
	    		} elseif (248 <= $t && $t <= 251) {
	    			$tn = 5; $n += 5; $noc += 2;
	    		} elseif ($t == 252 || $t == 253) {
	    			$tn = 6; $n += 6; $noc += 2;
	    		} else {
	    			$n++;
	    		}
	    		if ($noc >= $length) {
	    			break;
	    		}
	    	}
	    	if ($noc > $length) {
	    		$n -= $tn;
	    	}
	    	$strcut = substr($string, 0, $n);
	    } else {
	    	for ($i = 0; $i < $length - strlen($dot) - 1; $i++) {
	    		$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
	    	}
	    }
	    // $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
	    return $strcut.$dot;
    }
}
