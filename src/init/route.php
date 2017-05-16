<?php
/*

    Route asset request

*/

// get uri without additional parameters
$pageToLoad = strtok($_SERVER['REQUEST_URI'], '?');


/*

    Generate pretty GET parameters array

*/


// Remove subfolder from request
if (EARLY_SYS_SUB != '') {
    $pageToLoad = str_replace('/' . EARLY_SYS_SUB, '', $pageToLoad);
} else {
    $pageToLoad = implode('', explode('/', $pageToLoad, 2));
}

// Handle ":" and "/" in the same way
$pageToLoad = str_replace(':', '/', $pageToLoad);

$getpageToLoad = substr($pageToLoad, strpos($pageToLoad, '/') + 1);

// Split URI by "/" into array
$rawGetParameters = explode('/', $getpageToLoad);


// Generate pretty GET array
$__GET = foldArray($rawGetParameters);

// Get requested page name
$uri = explode('/', $pageToLoad);
$pageToLoad = $uri[0];

// Sanitize page name
$pageToLoad = preg_replace("/[^a-zA-Z0-9\-]+/", '', $pageToLoad);


// Define SYS_PAGE constant
if ($pageToLoad != '') {
    define('SYS_PAGE', $pageToLoad);
} else {
    define('SYS_PAGE', 'default');
}

if(file_exists(SYS_PATH_PAGES . '/' . SYS_PAGE . '.php')) {
	require_once SYS_PATH_PAGES . '/index.php';
	// require_once SYS_PATH_PAGES . '/initialize.php';
	die();
} else {
	fatalError('404. This page does not exist.');
}



// Check if page is private or public
if (file_exists(SYS_PATH_PAGES . '/public/' . SYS_PAGE . '.php')) {
    define('SYS_PAGE_TYPE', 'public');
} else if (file_exists(SYS_PATH_PAGES . '/private/' . SYS_PAGE . '.php')) {
    define('SYS_PAGE_TYPE', 'private');
}



// Check if page exists
if (defined('SYS_PAGE_TYPE')) {

     require_once SYS_PATH_INIT . '/initialize.php';

} else {

    if (SYS_PAGE !== '') {
        // Send 404 header and die pretty 
        header('HTTP/1.0 404 Not Found');
        fatalError('404. This page does not exist.');
    } else {
        // Generate default page
        require_once SYS_PATH_INIT . '/initialize.php';
    }

}






/*

    Functions

*/



// Function to fold an array into key => value
function foldArray($a) {
    $r = array();
    for ($n = 1; $n < count($a); $n += 2) {
        $r[$a[$n - 1]] = $a[$n];
    }

    return $r;
}

?>