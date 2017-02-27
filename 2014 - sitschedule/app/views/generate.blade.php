<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
		<script src="http://www.brisksurf.com/jquery-latest.js" type="text/javascript"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
		<title>SIT Schedule</title>
		<style type="text/css">
		.header{
			background: #f5f5f5;
		}
		.footer {
		        	height: 60px;
		        	background: #f5f5f5;
		        	margin-top: 20px;
      		}
	      	.container .credit {
		        margin: 20px 0;
	      	}
	      	.schedules{
	      		margin-top:20px;
	      	}
	      	.schedules .selected{
	      		background: #72d572;
	      		color: #fff;
	      	}
		.page{
			margin-top: 20px;
		}
	      	#previous{
	      		margin-right:30px;
	      	}
	      	#next{
	      		margin-left:30px;
	      	}
	      	.page .number{
	      		display: inline;
	      	}
		</style>
	</head>
	<body>
		<div class="header">
		  	<div class="container">
				<div class="page-header">
				  	<h1>SIT Schedule</h1>
				  	<br>
				 	<center>
				 		<div class="btn-group">
					 		<button type="button" class="btn btn-default" id="choose">Choose Courses</button>
					 		<button type="button" class="btn btn-default active" id="generate">Generate Schedule</button>
				 		</div>
				 	</center>
				</div>
		  	</div>
	  	</div>

	  	<div class="container page">
	  		<div class="row">
				<center>
					<button type="button" class="btn btn-default" id="previous">Previous</button>
					<div class="number">Schedule 1</div>
					<button type="button" class="btn btn-default" id="next">Next</button>
				</center>
	  		</div>
	  	</div>

	  	<?php $count = 1; ?>
		@foreach($schedules as $schedule)
	  	<div class="schedules schedule{{$count}}">
		  	<div class="container">
		  		<div class="row">
		  			<div class="col-md-12">
		  				<table class="table table-bordered">
		  					<tr>
		  						<td></td>
		  						<td>Monday</td>
		  						<td>Tuesday</td>
		  						<td>Wednesday</td>
		  						<td>Thursday</td>
		  						<td>Friday</td>
		  					</tr>
		  					@for($x = 5; $x < 22; $x++)
		  						<tr>
		  							<td>{{ ($x > 12) ? $x - 12 : $x }} {{ ($x >= 12) ? "PM" : "AM" }}</td>
			  						@foreach(["M", "T", "W", "R", "F"] as $y)
			  							<td style="{{ ($schedule[$y . $x]) ? 'background: #' . $schedule[$y . $x][0] : "" }}" class="{{ ($schedule[$y . $x]) ? "selected" : "" }}">
			  								{{ ($schedule[$y . $x] && $schedule[$y . $x][1] != "-") ? $schedule[$y . $x][1] : "" }}
			  							</td>
									@endforeach
								</tr>
							@endfor
						</table>
	  				</div>
		  		</div>
		  	</div>
	  	</div>
	  	<?php $count++; ?>
		@endforeach

	  	<div class="footer">
		      	<div class="container">
		        		<p class="muted credit">&copy; 2014 Your Secret Developer</p>
		      	</div>
		</div>
		<script type="text/javascript">
		var schedule = 1;
		var total = {{ $count}} - 1;
		function init()
		{
			if(schedule == 1) $("#previous").prop("disabled",true);
			else $("#previous").prop("disabled",false);
			if(schedule == total) $("#next").prop("disabled",true);
			else $("#next").prop("disabled",false);

			$(".page .number").html("Schedule " + schedule + " / " + total);
			$(".schedules").hide();
			$(".schedule" + schedule).show();
		}
		$("#next").click(function() {
			schedule += 1;
			init();
		});
		$("#previous").click(function() {
			schedule -= 1;
			init();
		});
		init();

		$("#choose").click(function() {
			window.location = '{{$url}}';
		});
		</script>
	</body>
</html>







