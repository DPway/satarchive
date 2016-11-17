

<?php 
// Run pause | Отсрочка запуска
sleep(300); // 300 - wait 5 min

$arch15m_way = "satarchive/";
$arch1h_way = "satarchive1h/";

// Timestamp definition | Определен отметки времени
$timearray = getdate(time() - 11100);             // -5 minutes -3 hours  (7500  -2 hours winter time)
$roundMin = round($timearray[minutes] / 5) * 5;

// Image download | Загрузка изображения 
$imgfile = file_get_contents('http://en.sat24.com/image?type=infraPolair&region=eu&timestamp=' . $timearray[year] .
	sprintf("%02d",$timearray[mon]) . sprintf("%02d", $timearray[mday]) . sprintf("%02d", $timearray[hours]) . 
	sprintf("%02d", $roundMin));

// Image saving | Сохранение изображения 
file_put_contents(($arch15m_way . $timearray[year] . '-' . sprintf("%02d",$timearray[mon]) . '-' . 
	sprintf("%02d", $timearray[mday]) . '-' . sprintf("%02d", $timearray[hours]) . '-' . 
	sprintf("%02d", $roundMin) . '.jpg'), $imgfile);
if ($roundMin == 0 || $roundMin == 60) {
	file_put_contents(($arch1h_way . $timearray[year] . '-' . sprintf("%02d",$timearray[mon]) . '-' . 
		sprintf("%02d", $timearray[mday]) . '-' . sprintf("%02d", $timearray[hours]) . '-' . 
		sprintf("%02d", $roundMin) . '.jpg'), $imgfile);
}
// echo(('satarchive/' . $timearray[year] . '-' . sprintf("%02d",$timearray[mon]) . '-' . sprintf("%02d", $timearray[mday]) .
// '-' . sprintf("%02d", $timearray[hours]) . '-' . sprintf("%02d", $roundMin) . '.jpg'));  
//print_r(getdate());
//file_get_contents('http://en.sat24.com/image?type=infraPolair&region=eu&timestamp=201609101610'));


// Files in dirctory | файлы в директории
function filename_reader($dirname) {
	if ($handle = opendir($dirname)) {
		$qf = 0;
		while (false !== ($fname = readdir($handle))) { 
			if ($fname != "." && $fname != "..") { 
				$fnamearray[$qf] = $fname; 
				$qf = $qf + 1;
			}
		}
	}
	sort($fnamearray);
	return quantity_control($dirname, $fnamearray);
}

// Files quantity control | Контроль количества файлов
function quantity_control($dirname, $fnamearray) {
	$qdel = 0;
	if (count($fnamearray) > 55){			// предельное количество кадров анимации
		$qdel = 5;}
	for ($qf = 0; $qf < $qdel; $qf++) {
		unlink($dirname . $fnamearray[$qf]);}
	return(array_slice($fnamearray, $qdel, count($fnamearray)));   // возврат укороченного масива имен
}

// Image size control (not complited download) | Контроль размера изображения (Незавершонной загрузки)
function quality_control($dirname, $fnamearray) {
	$tqf = count($fnamearray);
	for ($qf = $tqf; $qf < $tqf; $qf++) { 
		$filename = $fnamearray[$qf];
		if ($qf > ($tqf-10) && filesize($dirname . $fnamearray[$qf]) < 1000){
			// for last 10 try to dawnload again | для 10 последних пытаемся загрузить снова
			file_put_contents(($dirname . $filename), 
				file_get_contents('http://en.sat24.com/image?type=infraPolair&region=eu&timestamp=' . $filename[0] . 
					$filename[1] . $filename[2] . $filename[3] . $filename[5] . $filename[6] . $filename[8] . 
					$filename[9] . $filename[11] . $filename[12] . $filename[14] . $filename[15]));
		}
		if ($qf < ($tqf-10) && filesize($dirname . $fnamearray[$qf]) < 1000){
			// for other try to dawnload previous | для остальных пытаемся загрузить предыдущую 
			$newmin = sprintf("%02d", (intval($filename[14] . $filename[15]) - 5));
			file_put_contents(($dirname . $filename), 
				file_get_contents('http://en.sat24.com/image?type=infraPolair&region=eu&timestamp=' . $filename[0] . 
					$filename[1] . $filename[2] . $filename[3] . $filename[5] . $filename[6] . $filename[8] . 
					$filename[9] . $filename[11] . $filename[12] . $newmin));
		}
	}
}


quality_control($dirname, filename_reader($arch15m_way));
quality_control($dirname, filename_reader($arch1h_way));





// Writer script 
function writer($archdir, $filearray, $resultfilename) {
$f = fopen($resultfilename, "w");  

fwrite($f,'
	<!DOCTYPE HTML>
<html>
<head>
	<title>Sat24 by METEO-IS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Cache-Control" content="no-store" /> 
	<meta name="author" content="METEO-IS." />
	<meta name="description" content="Weather satellite animation." />
	<link rel="shortcut icon" type="image/x-icon" href="images/ico1.ico">

	<meta property="og:title" content="Sat24 by METEO-IS"/>
	<meta property="og:site_name" content="Sat24 by METEO-IS"/>
	<meta property="og:url" content="http://sat.meteo-is.in.ua/"/>
	<meta property="og:description" content="Actual weather animation"/>
	<meta property="og:image" content="http://sat.meteo-is.in.ua/siteimg/2016-11-03-06-00.jpg"/>

	<link href="https://fonts.googleapis.com/css?family=Cairo:700" rel="stylesheet">	
	<link rel="stylesheet" href="bootstr/bootstrap.min.css">
	<script src="bootstr/jquery-3.1.0.min.js"></script>
	<script src="bootstr/bootstrap.min.js"></script>
	');

fwrite($f,'
	 <style type="text/css">
		body{text-align: center;}
		p{font-size: 16px;}
		.satframe{
			position: absolute;
			opacity: 0.1;}
		#bars{position: absolute; 
			width:95%;
			padding-top: 3%;
			padding-left: 2%;}
		#bars>h3{color: #5F0;
			text-shadow: 1px 1px 5px black;}
		.progress-bar{background-color: #4D1;}
		h1{font-family: "Cairo", sans-serif;
			padding-bottom: 2%;
			color: #679;}
		@media screen and (min-width: 240px) and (max-width: 700px) {
			h1{font-size: 25px;}
		}
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
		<h1> EUMETSAT images animated by METEO-IS.</h1>
		<div class="row">
			<div class="col-md-9" style="padding-left:0;">
	');

for ($qf = 0; $qf < count($filearray); $qf++) {
	fwrite($f, '<img class="satframe img-responsive" src="/' . $archdir . $filearray[$qf] . '" width="845" height="615" onload="loadcount()">
	');
}

fwrite($f,'	
	<div id="bars">
		<h3>Loading...</h3>
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
	<img class="img-responsive" style="opacity: 0.0; padding-bottom: 30px;" src="/' . $archdir . $filearray[1] . '" width="845" height="615">

			</div>
			<div class="col-md-3">
			<p>Frame interval</p>
				<div class="btn-group-vertical">
					<a id="bt2" href="1h.html" class="btn btn-default btn-lg btn-block" role="button">1 hour</a>
					<a id="bt3" href="index.html" class="btn btn-primary btn-lg btn-block" role="button">15 min</a>
					<a id="bt4" href="fivemin.php" class="btn btn-default btn-lg btn-block" role="button">5 min</a>
					<a id="bt5" href="zipper15m.php" class="btn btn-success btn-lg btn-block" role="button" style="margin-top: 10px;">Download .zip</a>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	var pageUrl = window.location.href; 
	if(pageUrl.indexOf("1h.html") + 1){
		document.getElementById("bt3").classList.remove("btn-primary");
		document.getElementById("bt3").classList.add("btn-default");
	 	document.getElementById("bt2").classList.add("btn-primary");
	 	document.getElementById("bt5").href = "zipper1h.php";
	 	}

	var tc = 0;
	function run_timer(){
		tc = tc + 1;
		if ((tc < 30) && (lc < 99)){
			document.getElementById("timebar").style.width = (tc*3.333).toString() + "%"
			document.getElementById("timebar").textContent = (Math.round(30-tc)).toString() + "s";
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


// Delete old reuested .zip files | удаляет старые запрошенные .zip файлы 
function deletter($way_2){
	if ($handle = opendir($way_2)) {
		while (false !== ($filename = readdir($handle))) {
			if ($filename != "." && $filename != "..") { 
				$fileTimeV = filemtime($way_2 . '/' . $filename);
				if ($fileTimeV < time() - 3600){   // oldest then 1hour | старее чем час
					unlink($way_2 . '/' . $filename);
				}
			}
		}
	}
}

writer($arch15m_way, filename_reader($arch15m_way), "index.html");
if ($roundMin == 0 || $roundMin == 60) {
	writer($arch1h_way, filename_reader($arch1h_way), "1h.html");}

deletter("zips15");
deletter("zips1h");

?>


<!-- <p>Done!</p> -->