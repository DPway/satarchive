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
	 	.satframe{
	 		position: absolute;
	 		opacity: 0.1;
	 	}
	 	body{text-align: center;}
	</style>
</head>
<body>
<div class="container">
	<h2> Eumetsat images animated by METEO-IS.</h2>
	<div class="row">
  		<div class="col-sm-9">
	
<?php
for ($qf = 0; $qf < count($namearray); $qf++) {
	echo('<img class="satframe img-responsive" src="' . $namearray[$qf] . '" width="845" height="615">
	');
}
?>

<img class="img-responsive" style="opacity: 0.0; padding-bottom: 30px;" src="' . $namearray[1] . '" width="845" height="615">
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
	var timerId = setTimeout(swichcont, 10000);

	</script>

</body>');

