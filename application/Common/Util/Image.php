<?php


defined('SYS_ENTRY') or die('Access Denied!');


/**
 * Common_Util_Image
 *
 */
class Common_Util_Image
{
    public function resampimagejpg($width, $height, $src, $dst, $comp = 0)
    {
        $g_imgcomp = 100 - $comp;
        $g_srcfile = $src;
        $g_dstfile = $dst;
        $g_fw = $width;
        $g_fh = $height;

        // Set artificially high because GD uses uncompressed images in memory
	    @ini_set('memory_limit', '256M');
	
        if (file_exists($g_srcfile)) {
            $g_is = getimagesize($g_srcfile);
            if (($g_is[0] - $g_fw) >= ($g_is[1] - $g_fh)) {
                $g_iw = $g_fw;
                $g_ih = ($g_fw / $g_is[0]) * $g_is[1];
            } else {
                $g_ih = $g_fh;
                $g_iw = ($g_ih / $g_is[1]) * $g_is[0];    
            }

            $f_srcfile = file_get_contents($g_srcfile);

            $img_src = imagecreatefromstring($f_srcfile);
            $img_dst = imagecreatetruecolor($g_iw, $g_ih);
            imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $g_iw, $g_ih, $g_is[0], $g_is[1]);

            imagepng($img_dst, $g_dstfile);

            imagedestroy($img_dst);
            @chmod ($g_dstfile, 0604) ;
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Scale down an image to fit a particular size and save a new copy of the image.
     *
     * The PNG transparency will be preserved using the function, as well as the
     * image type. If the file going in is PNG, then the resized image is going to
     * be PNG. The only supported image types are PNG, GIF, and JPEG.
     *
     * Some functionality requires API to exist, so some PHP version may lose out
     * support. This is not the fault of WordPress (where functionality is
     * downgraded, not actual defects), but of your PHP version.
     *
     *
     * @param string $srcimage Image file path.
     * @param int $max_w Maximum width to resize to.
     * @param int $max_h Maximum height to resize to.
     * @param bool $crop Optional. Whether to crop image or resize.
     * @param string $suffix Optional. File Suffix.
     * @return mixed WP_Error on failure. String with new destination path. Array of dimensions from {@link image_resize_dimensions()}
     */
    public function resize($srcimage, $max_w, $max_h, $crop = false, $suffix = null) 
    {    
        if (!file_exists($srcimage)) {
		    return false;
		}
		// The GD image library is installed
	    if (!function_exists('imagecreatefromstring')) {
	        return false;
	    }
	    // Set artificially high because GD uses uncompressed images in memory
	    @ini_set('memory_limit', '256M');
	    $src = imagecreatefromstring(file_get_contents($srcimage));

	    if (!is_resource($src)) {
		    return false;
        }
        
	    list($orig_w, $orig_h, $orig_type) = getimagesize($srcimage);
	    $dims = $this->_getResizeDimensions($orig_w, $orig_h, $max_w, $max_h, $crop);
	    if (!$dims) {
		    return false;
		}
	    list($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) = $dims;

	    $dst = imagecreatetruecolor($dst_w, $dst_h);

	    // preserve PNG transparency
	    if (IMAGETYPE_PNG == $orig_type && function_exists('imagealphablending') && function_exists('imagesavealpha')) {
		    imagealphablending($dst, false);
		    imagesavealpha($dst, true);
	    }

	    imagecopyresampled($dst, $src, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

	    // we don't need the original in memory anymore
	    imagedestroy($src);

	    // $suffix will be appended to the destination filename, just before the extension
	    if (!$suffix) {
		    $suffix = "{$dst_w}x{$dst_h}";
        }
                
	    $info = pathinfo($srcimage);
	    $dir = $info['dirname'];
	    $ext = $info['extension'];
	    $name = basename($srcimage, ".{$ext}");

	    // all formats are converted to jpg
		$dstimage = "{$dir}/{$name}-{$suffix}.jpg";
		if (!imagejpeg($dst, $dstimage)) {
			return false;
		}

	    imagedestroy($dst);

	    // Set correct file permissions
	    $stat = stat(dirname($dstimage));
	    $perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
	    @chmod($dstimage, $perms);

	    return $dstimage;
    }


    /**
     * Retrieve calculated resized dimensions for use in imagecopyresampled().
     *
     * Calculate dimensions and coordinates for a resized image that fits within a
     * specified width and height. If $crop is true, the largest matching central
     * portion of the image will be cropped out and resized to the required size.
     *
     * @since 2.5.0
     *
     * @param int $src_w Source width.
     * @param int $src_h Source height.
     * @param int $dst_w Destination width.
     * @param int $dst_h Destination height.
     * @param bool $crop Optional, default is false. Whether to crop image or resize.
     * @return bool|array False, on failure. Returned array matches parameters for imagecopyresampled() PHP function.
     */
    private function _getResizeDimensions($src_w, $src_h, $dst_w, $dst_h, $crop = false) 
    {

        if ($src_w <= 0 || $src_h <= 0) {
        	return false;
        }
        
        // at least one of dst_w or dst_h must be specific
        if ($dst_w <= 0 && $dst_h <= 0) {
        	return false;
        }
        
        if ($crop) {
        	// crop the largest possible portion of the srcinal image that we can size to $dst_w x $dst_h
        	$aspect_ratio = $src_w / $src_h;
        	$new_w = min($dst_w, $src_w);
        	$new_h = min($dst_h, $src_h);
        	if (!$new_w) {
        		$new_w = intval($new_h * $aspect_ratio);
        	}
        	if (!$new_h) {
        		$new_h = intval($new_w / $aspect_ratio);
        	}

        	$ratio = max($new_w / $src_w, $new_h / $src_h);
        	$crop_w = ceil($new_w / $ratio);
        	$crop_h = ceil($new_h / $ratio);

        	$src_x = intval(($src_w - $crop_w) / 2);
        	$src_y = intval(($src_h - $crop_h) / 2);	
		
        
        } else {
	        // don't crop, just resize using $dst_w x $dst_h as a maximum bounding box
            $crop_w = $src_w;
            $crop_h = $src_h;
		
            $width_ratio = $height_ratio = 1.0;
  	        if ($dst_w > 0 && $src_w > $dst_w) {
	            $width_ratio = $dst_w / $src_w;
            }
            if ($dst_h > 0 && $src_h > $dst_h) {
	            $height_ratio = $dst_h / $src_h;
            }
              
            // the smaller ratio is the one we need to fit it to the constraining box
            $ratio = min($width_ratio, $height_ratio);
            $new_w = intval($src_w * $ratio);
            $new_h = intval($src_h * $ratio);
            
            $src_x = 0;
	        $src_y = 0;
        }

        // if the resulting image would be the same size or larger we don't want to resize it
        if ($dst_w >= $src_w && $dst_h >= $src_h) {
	        return false;
        }
        
        // the return array matches the parameters to imagecopyresampled()
        // int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
        return array(0, 0, $src_x, $src_y, $new_w, $new_h, $crop_w, $crop_h);
    }    
    
}
