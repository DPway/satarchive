<!DOCTYPE HTML>
<html>
<head>
	<title>Sat24 by METEO-IS</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="author" content="Denіs Pishniak" />
	<meta name="description" content="Meteo weather satellite animation" />
	<link rel="shortcut icon" type="image/x-icon" href="images/ico1.ico">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<?php 
for ($qf = 0; $qf < 36; $qf++) {
    	$timearray = getdate(time() -10800 + 300*$qf - 10800);
    	$roundMin = round($timearray[minutes] / 5) * 5;
    	$namearray[$qf] = ('http://en.sat24.com/image?type=infraPolair&region=eu&timestamp=' . $timearray[year] .
	sprintf("%02d",$timearray[mon]) . sprintf("%02d", $timearray[mday]) . sprintf("%02d", $timearray[hours]) . 
	sprintf("%02d", $roundMin));
	}

for ($qf = 0; $qf < count($namearray); $qf++) {
	echo('<link rel="prefetch" href="' . $namearray[$qf] . '">
	');
}
?>

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
			lc = lc + 100/ <?php echo(count($namearray)) ?> ;
			document.getElementById("loadingbar").style.width = lc.toString() + "%"
			document.getElementById("loadingbar").textContent = (Math.round(lc)).toString() + "%";
		}
	</script>
</head>
<body>
<div class="container">
	<h2> EUMETSAT images animated by METEO-IS.</h2>
	<div class="row">
  		<div class="col-sm-9">
	
<?php
for ($qf = 0; $qf < count($namearray); $qf++) {
	echo('<img class="satframe img-responsive" src="' . $namearray[$qf] . '" width="845" height="615" onload="loadcount()">
	');
}
?>
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
<?php
echo('<img class="img-responsive" style="opacity: 0.0; padding-bottom: 30px;" src="' . $namearray[1] . '" width="845" height="615">');
?>	
		</div>
			<div class="col-sm-3">
				<div class="btn-group-vertical">
					<a href="index.html" class="btn btn-default btn-lg btn-block" role="button">15 min</a>
					<a href="#" class="btn btn-primary btn-lg btn-block" role="button">5 min</a>
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
      if (countim <= <?php echo(count($namearray)-2); ?> ){
      	if (countim >= 1){
				satmap[countim-1].style.opacity = 0;
      	}
      	else{
				satmap[ <?php echo(count($namearray)-2); ?> ].style.opacity = 0;
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

