<?php

// Поиск последнего файла
function last_name($way_1){
if ($handle = opendir($way_1)) {
    $fileTimeMax = 0;
    while (false !== ($filename = readdir($handle))) {
        if ($filename != "." && $filename != "..") { 
            $fileTimeV = filemtime($way_1 . $filename);
            if ($fileTimeMax < $fileTimeV){
                $fileTimeMax = $fileTimeV;
                $fileTimeMaxName = $filename;
            }
        }
    }
$nameLength = strlen($fileTimeMaxName);
$zipName = substr($fileTimeMaxName, 0, ($nameLength-4)) . ".zip";
return $zipName;
//echo($zipName);
}
}


// добавление в архив
function zipper($zipName){
if (file_exists($zipName) == false) {
    $zip = new ZipArchive;
    if ($zip->open($zipName, ZipArchive::CREATE) === TRUE) {
    	if ($handle = opendir('satarchive/')) {
    		$qf = 0;
    	    while (false !== ($filename = readdir($handle))) {
       	        if ($filename != "." && $filename != "..") { 
    	            $qf = $qf + 1;
    	    		$zip->addFile('satarchive/' . $filename);
        		}
        	}
        }
        $zip->close();
        //echo 'ok1';
    } 
    else {
        echo 'error add to zip archive';
    }
}

header ("Content-Type: application/octet-stream");
header ("Accept-Ranges: bytes");
header ("Content-Length: ".filesize($zipName));
header ("Content-Disposition: attachment; filename=".$zipName);  
readfile($zipName);
}

// вызов функций
zipper("zips15/" . last_name("satarchive/"));





?>