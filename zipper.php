<?php
$zip = new ZipArchive;
if ($zip->open('test.zip', ZipArchive::CREATE) === TRUE) {

	if ($handle = opendir('satarchive/')) {
		$qf = 0;
	    while (false !== ($filename = readdir($handle))) {
   	        if ($filename != "." && $filename != "..") { 
	            $qf = $qf + 1;
	    		$zip->addFile('satarchive/' . $filename);
    		}
    	}
    	echo($qf);
    }
    $zip->close();
    echo 'ok';
} 
else {
    echo 'error add to zip archive';
}

?>