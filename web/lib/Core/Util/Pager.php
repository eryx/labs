<?php
final class Core_Util_Pager
{

    /**
     * build a generation pager array
     *
     * @param   int $page   Number of current page
     * @param   int $count  Number of sql query count
     * @param   int $limit  Number of rows to display per page
     * @param   int $quantity   Number of pages in the list
     * @return array
     */
    public static function get($page = 1, $count = 0, $limit = 20, $quantity = 12) 
    {
	    $pager = array('list' => array());
	    
	    // get pages, the beginning page and the end  
	    $pages = ceil($count / $limit);
	    $begin = $flag = (($page - (int)($quantity / 2)) < 1) ? 1 : ($page - (int)($quantity / 2));
	    $end   = (($begin + $quantity) < $pages) ? ($begin + $quantity - 1) : $pages;
	    
	    // fixed current page number
	    if ($page < 1) {
	    	$page = 1;
	    } elseif ($page > $pages) {
	    	$page = $pages;
	    }

        // taking first page
	    if ($begin > 1) {
	    	$pager['first'] = 1;
	    }
	    
	    // taking previous page
	    if ($page > 1) {
	        $pager['previous'] = $page - 1;
	    }
	    
        // taking pages list
	    while ($flag <= $end) {
	    	$pager['list'][] = array (
	    	    'page' => $flag,
	    	    'isCurrent' => ($flag == $page) ? true : false,
	    	);
	    	$flag++;
	    };

	    // taking next page
	    if ($page < $pages) {
		    $pager['next'] = $page + 1;
	    }
	    
	    // taking last page
	    if ($end < $pages) {
	    	$pager['last'] = $pages;
	    }
	    
	    // taking the pager information
	    $pager['itemCount'] = $count;
	    $pager['itemFrom'] = ($count == 0) ? 0 : ((($page - 1) * $limit) + 1);
	    $pager['itemTo'] = (($page * $limit) < $count) ? ($page * $limit) : $count;
	    
	    return $pager;
    }

}
