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

	die( $errorMessage );

    // Get error Template
    $errorTemplate = file_get_contents(SYS_PATH_RESOURCES . '/templates/error/error.html');

    // Set variables
    $errorTemplate = str_replace('###APP_NAME###', SYS_NAME, $errorTemplate);
    $errorTemplate = str_replace('###APP_VERSION###', SYS_VERSION, $errorTemplate);
    $errorTemplate = str_replace('###ERROR_TYPE###', $errorType, $errorTemplate);
    $errorTemplate = str_replace('###DATE###', date('d.m.Y - H:i:s'), $errorTemplate);
    $errorTemplate = str_replace('###ERROR_MESSAGE###', $errorMessage, $errorTemplate);



    // Check if system is initialized
    $appStatus = 'bootstrap';

    if (defined('SYS_INSTALLED')) {
        $appStatus = 'loaded';
    }

    $errorTemplate = str_replace('###APP_STATUS###', $appStatus, $errorTemplate);



    // Set base url if possible
    if (defined('EARLY_SYS_BASE')) {
        $errorTemplate = str_replace('###SYS_BASE###', EARLY_SYS_BASE, $errorTemplate);
    } else {
        $errorTemplate = str_replace('###SYS_BASE###', '#', $errorTemplate);
    }
    


    // Clean output buffer
    ob_end_clean();


    // Close database connection
    $sysDatabase = null;


    echo $errorTemplate;


    // Exit
    exit();
}



?>