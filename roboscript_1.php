

<?php 

$timearray = getdate(time() - 11100);             // -5 minutes -3 hours  (7500  -2 hours winter time)
$roundMin = round($timearray[minutes] / 5) * 5;

// echo(('satarchive/' . $timearray[year] . '-' . sprintf("%02d",$timearray[mon]) . '-' . sprintf("%02d", $timearray[mday]) .
// '-' . sprintf("%02d", $timearray[hours]) . '-' . sprintf("%02d", $roundMin) . '.jpg'));  
//print_r(getdate());


file_put_contents(('satarchive/' . $timearray[year] . '-' . sprintf("%02d",$timearray[mon]) . '-' . 
	sprintf("%02d", $timearray[mday]) . '-' . sprintf("%02d", $timearray[hours]) . '-' . 
	sprintf("%02d", $roundMin) . '.jpg'), 
	file_get_contents('http://en.sat24.com/image?type=infraPolair&region=eu&timestamp=' . $timearray[year] .
	sprintf("%02d",$timearray[mon]) . sprintf("%02d", $timearray[mday]) . sprintf("%02d", $timearray[hours]) . 
	sprintf("%02d", $roundMin)));

//file_get_contents('http://en.sat24.com/image?type=infraPolair&region=eu&timestamp=201609101610'));

if ($handle = opendir('satarchive/')) {
	$qf = 0;
	$qdel = 0;
    while (false !== ($filename = readdir($handle))) { 
        if ($filename != "." && $filename != "..") { 
            //echo "$filename\n";
            $filenamea[$qf] = $filename;
            $qf = $qf + 1;
            if ($qf > 35){              // quantity of frame images
            	$qdel = 5;
            }
        } 
    }
    closedir($handle); 
    for ($qf = 0; $qf < $qdel; $qf++) {
		unlink('satarchive/' . $filenamea[$qf]);
	}
	writer($filenamea);
}




// Writer script 
function writer($filearray) {
$f = fopen("index.html", "w");  

fwrite($f,'
	<!DOCTYPE HTML>
<html>
<head>
	<title>METEO-IS Place</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="author" content="Denіs Pishniak" />
	<meta name="description" content="Демонстрационная и тестовая площадка для web проектов студии МЕТЕО-ИС" />
	<link rel="shortcut icon" type="image/x-icon" href="images/ico1.ico">

	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	');

	for ($qf = ($qdel+2); $qf < count($filearray); $qf++) {
	fwrite($f, '<link rel="prefetch" href="/satarchive/' . $filearray[$qf] . '">
	');
	}
fwrite($f,'
</head>
<body>
	<div class="container"> 
		<img id="satmap" src="/satarchive/');
$a = count($filearray) - 1;
fwrite($f, $filearray[$a]);
fwrite($f,'" class="img-responsive" alt="Sat img (c) Sat24.com / Eumetsat / Met Office" width="845" height="615">

	</div>

	<script type="text/javascript">
	var satimg = ["');		

fwrite($f, $filearray[$qdel+1]);
for ($qf = ($qdel+2); $qf < count($filearray); $qf++) {
	fwrite($f, '", "');
	fwrite($f, $filearray[$qf]);
}

fwrite($f,' "];
	var countim = 1;
	function swichcont(){
      	if (countim < (satimg.length-1)){
			var imUrl = "/satarchive/" + satimg[countim];
			document.getElementById("satmap").src = imUrl;
			countim++;
			timerId = setTimeout(swichcont, 100);
	    }
	    else {
	    	var imUrl = "/satarchive/" + satimg[countim];
			document.getElementById("satmap").src = imUrl;
	    	countim = 1;
	    	timerId = setTimeout(swichcont, 2000);
	    }
	    
	}
	var timerId = setTimeout(swichcont, 0);

	</script>

</body>


	');
fclose($f); 
} 

?>


<!-- <p>Done!</p> -->