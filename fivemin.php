<!DOCTYPE HTML>
<html>
<head>
	<title>Sat24 by METEO-IS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Cache-Control" content="no-store" />
	<meta name="author" content="METEO-IS." />
	<meta name="description" content="Meteo weather satellite animation with 5 min frame interval" />
	<link rel="shortcut icon" type="image/x-icon" href="siteimg/ico1.ico">

	<meta property="og:title" content="Sat24 by METEO-IS"/>
	<meta property="og:site_name" content="Sat24 by METEO-IS"/>
	<meta property="og:url" content="http://sat.meteo-is.in.ua/"/>
	<meta property="og:description" content="Actual weather animation"/>
	<meta property="og:image" content="http://sat.meteo-is.in.ua/siteimg/2016-11-03-06-00.jpg"/>

	<link href="https://fonts.googleapis.com/css?family=Cairo:700" rel="stylesheet">	
	<link rel="stylesheet" href="bootstr/bootstrap.min.css">
  	<script src="bootstr/jquery-3.1.0.min.js"></script>
  	<script src="bootstr/bootstrap.min.js"></script>


<?php 
for ($qf = 0; $qf < 36; $qf++) {
    $timearray = getdate(time() -10800 + 300*$qf - 10800);
    $roundMin = round($timearray[minutes] / 5) * 5;
    $urlarray[$qf] = ('http://en.sat24.com/image?type=infraPolair&region=eu&timestamp=' . $timearray[year] .
		sprintf("%02d",$timearray[mon]) . sprintf("%02d", $timearray[mday]) . sprintf("%02d", $timearray[hours]) . 
		sprintf("%02d", $roundMin));
     $namearray[$qf] = ($timearray[year] .'-'. sprintf("%02d",$timearray[mon]) .'-'. 
     	sprintf("%02d", $timearray[mday]) .'-'. sprintf("%02d", $timearray[hours]) .'-'. 
		sprintf("%02d", $roundMin) . '.jpg');
	}

for ($qf = 0; $qf < count($urlarray); $qf++) {
	echo('<link rel="prefetch" href="' . $urlarray[$qf] . '">
	');
}
?>

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
		h1{font-family: 'Cairo', sans-serif;
			padding-bottom: 2%;
			color: #679;}
		@media screen and (min-width: 240px) and (max-width: 700px) {
			h1{font-size: 25px;}
		}
	</style>
	<script type="text/javascript">
		var lc = 0;
		function loadcount(){
			lc = lc + 100/ <?php echo(count($urlarray)) ?> ;
			document.getElementById("loadingbar").style.width = lc.toString() + "%"
			document.getElementById("loadingbar").textContent = (Math.round(lc)).toString() + "%";
		}
	</script>
</head>
<body>
<div class="container">
	<h1> EUMETSAT images animated by METEO-IS.</h1>
	<div class="row">
  		<div class="col-sm-9">
	
<?php
for ($qf = 0; $qf < count($urlarray); $qf++) {
	echo('<img class="satframe img-responsive" src="' . $urlarray[$qf] . '" width="845" height="615" onload="loadcount()">
	');
}
?>
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
<?php
echo('<img class="img-responsive" style="opacity: 0.0; padding-bottom: 30px;" src="' . $urlarray[1] . '" width="845" height="615">');
?>	
		</div>
			<div class="col-sm-3">
				<p>Frame interval</p>
				<div class="btn-group-vertical">
					<a id="bt2" href="1h.html" class="btn btn-default btn-lg btn-block" role="button">1 hour</a>
					<a href="index.html" class="btn btn-default btn-lg btn-block" role="button">15 min</a>
					<a href="#" class="btn btn-primary btn-lg btn-block" role="button">5 min</a>
<!-- 					<a href="zipper5m.php" class="btn btn-success btn-lg btn-block" role="button" style="margin-top: 10px;">Download .zip</a>
 -->					<!-- <button type="button" class="btn btn-success btn-lg btn-block" onclick="zipperjs()">Download .zip</button> -->
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	var tc = 0;
	function run_timer(){
		tc = tc + 1;
		if ((tc < 30) && (lc < 99)){
			document.getElementById("timebar").style.width = (tc*3.33).toString() + "%"
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
      if (countim <= <?php echo(count($urlarray)-2); ?> ){
      	if (countim >= 1){
				satmap[countim-1].style.opacity = 0;
      	}
      	else{
				satmap[ <?php echo(count($urlarray)-2); ?> ].style.opacity = 0;
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
</html>





