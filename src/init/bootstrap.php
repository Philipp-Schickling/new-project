<?php

/* 

	Bootstrap app | Check for 

*/
	// start output buffering
	ob_start();


    // Get system subfolder settings
    $sysSettingsFile = SYS_PATH_CONFIG . 'system-config.json';

    if( file_exists($sysSettingsFile) ) {

    	$jsonFile = file_get_contents( $sysSettingsFile );
    	$sysSettings = json_decode( $jsonFile, true );

        $subPath = $sysSettings['Subdirectory'];

    } else {

        fatalError('The app config ("' . $sysSettingsFile . '") could not be found.');

    }

    // Set base url and sub folder
    if ($subPath != '') {
        define('EARLY_SYS_SUB', $subPath . '/');
    } else {
        define('EARLY_SYS_SUB', $subPath);
    }
    
    define('EARLY_SYS_BASE', (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . EARLY_SYS_SUB);

    // Route the request – src/init/route.php | src/init/route-asset.php
    switch ( SYS_MODE ) {
        case 'app':
            require SYS_PATH_INIT . '/route.php';
            break;
        
        case 'asset':
            require SYS_PATH_INIT . '/route-asset.php';
            break;

        default:
            fatalError('Could not detect system mode.');
            break;
    }



// Flush output, show error message and exit app
function fatalError($errorMessage, $errorType='server') {

    // get error Template
    $errorTemplate = file_get_contents(SYS_PATH_RESOURCES . '/templates/error/error.html');

    // replace 'variables' in error.html
    $errorTemplate = str_replace('###ERROR_MESSAGE###', $errorMessage, $errorTemplate);

    // clean output buffer
    ob_end_clean();

    // close database connection
    $sysDatabase = null;

    // display the page
    echo $errorTemplate;


    // Exit
    exit();
}



?>