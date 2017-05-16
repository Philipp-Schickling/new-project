<?php
/*

    Route asset request

*/

// get uri without additional parameters
$assetToLoad = strtok($_SERVER['REQUEST_URI'], '?');


    // Remove subfolder from request
    if (EARLY_SYS_SUB != '') {
        $assetToLoad = str_replace('/' . EARLY_SYS_SUB, '', $assetToLoad);
    } else {
        $assetToLoad = implode('', explode('/', $assetToLoad, 2));
    }



    // Remove assets folder from URI
    $assetToLoad = str_replace('assets/', '', $assetToLoad);
    



    

    // Serve asset
    $fileToServe = SYS_PATH_ASSETS . $assetToLoad;
    $fileToServe = realpath($fileToServe);


    if (file_exists($fileToServe)) {

        // Serve file
        $fileContent = fopen($fileToServe, 'rb');

        // Send the right headers
        $detectMimeType = isFileTypeAllowed($fileToServe);

        // Send 404 if file type is not allowed
        if ($detectMimeType == false) {
            header('HTTP/1.0 404 Not Found – ' . __FILE__);
            fatalError('404. This asset does not exist.');
        }
        

        // Send headers (cache for 1 day)
        header('Pragma: public');
        header('Cache-Control: max-age=86400');
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
        header('Content-Type: ' . $detectMimeType);

        // Dump the file
        fpassthru($fileContent);
        exit();

    } else {
        header('HTTP/1.0 404 Not Found');
        fatalError('404. This asset does not exist.');
        exit();
    }






    /*

        Functions

    */



    // Detect allowed mime types
    function isFileTypeAllowed($file) {
        $sysSettings = file_get_contents(SYS_PATH_CONFIG . 'allowed-files.json');
        $sysSettings = json_decode($sysSettings, true);

        // Get allowed filetypes
        $allowedMimeTypes = $sysSettings['Allowed Asset File Types'];
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        // Get allowed filetypes
        $allowedMimeTypes = $sysSettings['Allowed Asset File Types'];
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (isset($allowedMimeTypes[$extension])) {
            return $allowedMimeTypes[$extension];
        } else {
            return false;
        }

    }
