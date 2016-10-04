

<?php 
sleep(300); // Wait 5 min

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
            $filenamea[$qf] = $filename;  		// создание масива имен файлов
            $qf = $qf + 1;
            if ($qf > 55){               		// предельное количество кадров
            	$qdel = 5;
            }
            if (filesize('satarchive/' . $filename) < 1000){		// обработка пропущеного(не скачавшегося) кадра
				file_put_contents(('satarchive/' . $filename), 
					file_get_contents('http://en.sat24.com/image?type=infraPolair&region=eu&timestamp=' . $filename[0] . 
						$filename[1] . $filename[2] . $filename[3] . $filename[5] . $filename[6] . $filename[8] . 
						$filename[9] . $filename[11] . $filename[12] . $filename[14] . $filename[15]));
            }
        } 
    }
    closedir($handle); 
    sort($filenamea);
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
	<title>Sat24 by METEO-IS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="author" content="Denіs Pishniak" />
	<meta name="description" content="Meteo weather satellite animation" />
	<link rel="shortcut icon" type="image/x-icon" href="images/ico1.ico">

	<link rel="stylesheet" href="bootstr/bootstrap.min.css">
  	<script src="bootstr/jquery-3.1.0.min.js"></script>
  	<script src="bootstr/bootstrap.min.js"></script>
	');

	for ($qf = ($qdel+2); $qf < count($filearray); $qf++) {
	fwrite($f, '<link rel="prefetch" href="/satarchive/' . $filearray[$qf] . '">
	');
	}
fwrite($f,'
	 <style type="text/css">
	 	body{text-align: center;}
	 	.satframe{
	 		position: absolute;
	 		opacity: 0.1;}
	 	#bars{position: absolute; 
	 		width:95%;
	 		padding-top: 5%;
	 		padding-left: 2%;}
	 	.progress-bar{background-color: #4D1;}
	 </style>
	 <script type="text/javascript">
		var lc = 0;
		function loadcount(){
			lc = lc + 100/(' . count($filearray) . '-1);
			document.getElementById("loadingbar").style.width = lc.toString() + "%"
			document.getElementById("loadingbar").textContent = (Math.round(lc)).toString() + "%";
		}
	</script>
</head>
<body>
	<div class="container"> 
		<h2> EUMETSAT images animated by METEO-IS.</h2>
		<div class="row">
  			<div class="col-sm-9" style="padding-left:0;">
	');

for ($qf = ($qdel+1); $qf < count($filearray); $qf++) {
	fwrite($f, '<img class="satframe img-responsive" src="/satarchive/' . $filearray[$qf] . '" width="845" height="615" onload="loadcount()">
	');
}

fwrite($f,'	
	<div id="bars">
		<div class="progress" style="margin:1%;">
			<div id="loadingbar" class="progress-bar progress-bar-striped active" role="progressbar"
				  	aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
			</div>
		</div>
		<div class="progress" style="margin:1%;">
			<div id="timebar" class="progress-bar progress-bar-striped active" role="progressbar"
				  	aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
			</div>
		</div>
	</div>
	<img class="img-responsive" style="opacity: 0.0; padding-bottom: 30px;" src="/satarchive/' . $filearray[1] . '" width="845" height="615">

			</div>
			<div class="col-sm-3">
				<div class="btn-group-vertical">
					<a href="#" class="btn btn-primary btn-lg btn-block" role="button">15 min</a>
					<a href="fivemin.php" class="btn btn-default btn-lg btn-block" role="button">5 min</a>
					<a href="zipper.php" class="btn btn-success btn-lg btn-block" role="button" style="margin-top: 10px;">Download</a>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	var tc = 0;
	function run_timer(){
		tc = tc + 1;
		if ((tc < 30) && (lc < 99)){
			document.getElementById("timebar").style.width = (tc*3.333).toString() + "%"
			document.getElementById("timebar").textContent = (Math.round(tc)).toString() + "s";
			var timerId2 = setTimeout(run_timer, 1000);
		}
		else{
			swichcont();
			document.getElementById("bars").style.display = "none";
		}
	}
	run_timer();
		
	var countim = 0;
	function swichcont(){
	  var satmap = document.getElementsByClassName("satframe");
      if (countim <= '); 
fwrite($f, count($filearray)-2);
fwrite($f,'){
      	if (countim >= 1){
				satmap[countim-1].style.opacity = 0;
      	}
      	else{
				satmap['); 
fwrite($f, count($filearray)-2);
fwrite($f,'].style.opacity = 0;
      	}
			satmap[countim].style.opacity = 1;
			countim++;
			timerId = setTimeout(swichcont, 100);
	    }
	    else {
	    	countim = 0;
	    	timerId = setTimeout(swichcont, 2000);
	    }

	    
	}

	</script>

</body>


	');
fclose($f); 
} 

function delitter($way_2){
if ($handle = opendir($way_2)) {
    while (false !== ($filename = readdir($handle))) {
        if ($filename != "." && $filename != "..") { 
            $fileTimeV = filemtime($way_2 . '/' . $filename);
            if ($fileTimeV < time() - 3600){
				unlink($way_2 . '/' . $filename);
            }
        }
    }
}
}
delitter("zips15");

?>


<!-- <p>Done!</p> -->