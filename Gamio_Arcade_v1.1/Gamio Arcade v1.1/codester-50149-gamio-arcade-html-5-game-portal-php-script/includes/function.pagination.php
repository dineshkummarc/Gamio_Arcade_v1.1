<?php
// Script  : Gamio Arcade HTML 5 Game Portal PHP Script
// Author  : DeluxeScript
// Website : deluxescript.com
// Version : 1.0
if(!defined('V1_INSTALLED')){
    header("HTTP/1.0 404 Not Found");
	exit;
}


function admin_pagination($query,$ver,$per_page = 10,$page = 1, $url = '?') { 
    	global $db;
		$query = $db->query("SELECT * FROM $query");
    	$total = $query->num_rows;
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	
    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<ul class='pagination'>";
                
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li class='page-item'><a class='active page-link'>$counter</a></li>";
    				else
    					$pagination.= "<li class='page-item'><a href='$ver&page=$counter' class='page-link'>$counter</a></li>";					
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='page-item'><a class='active page-link'>$counter</a></li>";
    					else
    						$pagination.= "<li class='page-item'><a href='$ver&page=$counter' class='page-link'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='disabled page-item'>...</li>";
    				$pagination.= "<li class='page-item'><a href='$ver&page=$lpm1' class='page-link'>$lpm1</a></li>";
    				$pagination.= "<li class='page-item'><a href='$ver&page=$lastpage' class='page-link'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li class='page-item'><a href='$ver&page=1' class='page-link'>1</a></li>";
    				$pagination.= "<li class='page-item'><a href='$ver&page=2' class='page-link'>2</a></li>";
    				$pagination.= "<li class='disabled page-item'><a class='page-link'>...</a></li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='page-item'><a class='active page-link'>$counter</a></li>";
    					else
    						$pagination.= "<li class='page-item'><a href='$ver&page=$counter' class='page-link'>$counter</a></li>";					
    				}
    				$pagination.= "<li class='disabled page-item'><a class='page-link'>..</a></li>";
    				$pagination.= "<li class='page-item'><a href='$ver&page=$lpm1' class='page-link'>$lpm1</a></li>";
    				$pagination.= "<li class='page-item'><a href='$ver&page=$lastpage' class='page-link'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li class='page-item'><a href='$ver&page=1' class='page-link'>1</a></li>";
    				$pagination.= "<li class='page-item'><a href='$ver&page=2' class='page-link'>2</a></li>";
    				$pagination.= "<li class='disabled page-item'><a class='page-link'>..</a></li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='page-item'><a class='active page-link'>$counter</a></li>";
    					else
    						$pagination.= "<li class='page-item'><a href='$ver&page=$counter' class='page-link'>$counter</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$pagination.= "<li class='page-item'><a href='$ver&page=$next' class='page-link'>Next</a></li>";
                $pagination.= "<li class='page-item'><a href='$ver&page=$lastpage' class='page-link'>Last</a></li>";
    		}else{
    			$pagination.= "<li class='page-item'><a class='disabled page-link'>Next</a></li>";
                $pagination.= "<li class='page-item'><a class='disabled page-link'>Last</a></li>";
            }
    		$pagination.= "</ul>\n";		
    	}
    
    
        return $pagination;
} 
function web_pagination($query,$ver,$per_page = 10,$page = 1, $url = '?') { 
	global $db;
	$query = $db->query("SELECT * FROM $query");
	$total = $query->num_rows;
	$adjacents = "2"; 

	$page = ($page == 0 ? 1 : $page);  
	$start = ($page - 1) * $per_page;								
	
	$prev = $page - 1;							
	$next = $page + 1;
	$lastpage = ceil($total/$per_page);
	$lpm1 = $lastpage - 1;
	
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<ul class='pagination'>";
			
		if ($lastpage < 7 + ($adjacents * 2))
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
				else
					$pagination.= "<li class='page-item'><a class='page-link' href='$ver/$counter'>$counter</a></li>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))
		{
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
					else
						$pagination.= "<li class='page-item'><a class='page-link' href='$ver/$counter'>$counter</a></li>";					
				}
				$pagination.= "<li class='disabled page-item'><a class='page-link'>...</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href='$ver/$lpm1'>$lpm1</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href='$ver/$lastpage'>$lastpage</a></li>";		
			}
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<li class='page-item'><a class='page-link' href='$ver/1'>1</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href='$ver/2'>2</a></li>";
				$pagination.= "<li class='disabled page-item'><a class='page-link'>...</a></li>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
					else
						$pagination.= "<li class='page-item'><a class='page-link' href='$ver/$counter'>$counter</a></li>";					
				}
				$pagination.= "<li class='disabled page-item'><a class='page-link'>...</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href='$ver/$lpm1'>$lpm1</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href='$ver/$lastpage'>$lastpage</a></li>";		
			}
			else
			{
				$pagination.= "<li class='page-item'><a class='page-link' href='$ver/1'>1</a></li>";
				$pagination.= "<li class='page-item'><a class='page-link' href='$ver/2'>2</a></li>";
				$pagination.= "<li class='disabled page-item'><a class='page-link'>...</a></li>";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
					else
						$pagination.= "<li class='page-item'><a class='page-link' href='$ver/$counter'>$counter</a></li>";					
				}
			}
		}
		
		if ($page < $counter - 1){ 
			$pagination.= "<li class='page-item'><a class='page-link' href='$ver/$next'>Next</a></li>";
			$pagination.= "<li class='page-item'><a class='page-link' href='$ver/$lastpage'>Last</a></li>";
		}else{
			$pagination.= "<li class='page-item'><a class='disabled page-link'>Next</a></li>";
			$pagination.= "<li class='page-item'><a class='disabled page-link'>Last</a></li>";
		}
		$pagination.= "</ul>\n";		
	}


	return $pagination;
} 
function web_paginationme($query, $ver, $per_page = 10, $page = 1, $url = '?') { 
    global $db;
    $query = $db->query("SELECT * FROM $query");
    $total = $query->num_rows;
    $adjacents = 2; 
    $page = ($page == 0 ? 1 : $page);  
    $start = ($page - 1) * $per_page;								
    
    $prev = $page - 1;							
    $next = $page + 1;
    $lastpage = ceil($total / $per_page);
    $lpm1 = $lastpage - 1;
    
    $pagination = "";
    
    if ($lastpage > 1) {
        $pagination .= "<div class='pagination jc'>";
        
        if ($page < $lastpage - 1) {
            $pagination .= "<a href='$ver&page=$next' class='pagination-button'><i class='fa fa-angle-left'></i></a>";
        } else {
            if ($page > 1) {
                $pagination .= "<a href='$ver&page=1' class='pagination-button'><i class='fa fa-angle-left'></i><i class='fa fa-angle-left'></i></a>";
                $pagination .= "<a href='$ver&page=$prev' class='pagination-button'><i class='fa fa-angle-left'></i></a>";
            } else {
                $pagination .= "<a class='pagination-button disabled'><i class='fa fa-angle-left'></i></a>";
            }
        }
        
        if ($lastpage < 7 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination .= "<a class='pagination-button current'>$counter</a>";
                else
                    $pagination .= "<a href='$ver&page=$counter' class='pagination-button'>$counter</a>";                    
            }
        } elseif ($lastpage > 5 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination .= "<a class='pagination-button current'>$counter</a>";
                    else
                        $pagination .= "<a href='$ver&page=$counter' class='pagination-button'>$counter</a>";                    
                }
                $pagination .= "<a class='pagination-button disabled'>...</a>";
                $pagination .= "<a href='$ver&page=$lpm1' class='pagination-button'>$lpm1</a>";
                $pagination .= "<a href='$ver&page=$lastpage' class='pagination-button'>$lastpage</a>";        
            } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination .= "<a href='$ver&page=1' class='pagination-button'>1</a>";
                $pagination .= "<a href='$ver&page=2' class='pagination-button'>2</a>";
                $pagination .= "<a class='pagination-button disabled'>...</a>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {    
                    if ($counter == $page)
                        $pagination .= "<a class='pagination-button current'>$counter</a>";
                    else
                        $pagination .= "<a href='$ver&page=$counter' class='pagination-button'>$counter</a>";                    
                }
                $pagination .= "<a class='pagination-button disabled'>...</a>";
                $pagination .= "<a href='$ver&page=$lpm1' class='pagination-button'>$lpm1</a>";
                $pagination .= "<a href='$ver&page=$lastpage' class='pagination-button'>$lastpage</a>";        
            } else {
                $pagination .= "<a href='$ver&page=1' class='pagination-button'>1</a>";
                $pagination .= "<a href='$ver&page=2' class='pagination-button'>2</a>";
                $pagination .= "<a class='pagination-button disabled'>...</a>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<a class='pagination-button current'>$counter</a>";
                    else
                        $pagination .= "<a href='$ver&page=$counter' class='pagination-button'>$counter</a>";                    
                }
            }
        }
        
        if ($page < $lastpage - 1) {
            $pagination .= "<a href='$ver&page=$next' class='pagination-button'><i class='fa fa-angle-right'></i></a>";
            $pagination .= "<a href='$ver&page=$lastpage' class='pagination-button'><i class='fa fa-angle-right'></i><i class='fa fa-angle-right'></i></a>";
        } else {
            $pagination .= "<a class='pagination-button disabled'><i class='fa fa-angle-right'></i></a>";
        }
        
        $pagination .= "</div>";        
    }
    
    return $pagination;
}

?>