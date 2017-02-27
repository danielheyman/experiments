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
		.search{
			margin-top: 20px;
		}
		.course{
			width:100%;
			padding: 5px 10px;
		}
		.course:hover{
			cursor: pointer;
			background: #f5f5f5;
		}
		.course:hover .code{

			color: #e84e40;
		}
		.course .code{
			font-weight: bold;
			display: inline-block;
			width: 70px;
			font-size: 18px;
		}
		.course .name{
			display: inline-block;
			margin-left: 20px;
		}
		.footer {
		        	height: 60px;
		        	background: #f5f5f5;
		        	margin-top: 20px;
      		}
	      	.container .credit {
		        margin: 20px 0;
	      	}
	      	.courseType{
	      		display: none;
	      	}
	      	.courseType .option{
	      		padding-bottom: 20px;
	      		margin-bottom: 20px;
	      		border-bottom: 1px solid #ddd;
	      	}
	      	.courseType .title{
	      		font-weight: bold;
	      		display: inline-block;
	      	}
	      	.courseType .activity{
	      		background: #e84e40;
	      		color: #fff;
	      		padding: 5px;
	      		display: inline-block;
	      		border-radius: 5px;
	      		margin-left: 5px;
	      	}
	      	.courseType .section{
	      		margin-top:10px;
	      		margin-left: 30px;
	      		padding: 5px;
	      		height:40px;
	      		line-height: 30px;
	      	}
	      	.courseType .instructor{
	      		color: #e84e40;
	      		display: inline-block;
	      	}
	      	.courseType .meet{
	      		background: #5677fc;
	      		color: #fff;
	      		padding: 5px 10px;
	      		display: inline-block;
	      		border-radius: 5px;
	      		margin-left: 5px;
	      		float: right;
	      		line-height: 20px;
	      	}
		.courseType .section:hover{
			cursor: pointer;
			background: #f5f5f5;
		}
		.courseType .section:hover .code{

			color: #e84e40;
		}
		.courseType input[type=checkbox]{
			margin-right: 10px;
		}
		.courseType .disabled{
			display: none;
	      		color: #e84e40;
	      		background: #ff9800;
	      		color: #fff;
	      		padding: 5px 10px;
	      		border-radius: 5px;
	      		margin-left: 20px;
	      		line-height: 20px;
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
					 		<button type="button" class="btn btn-default active" id="choose">Choose Courses</button>
					 		<button type="button" class="btn btn-default" id="generate">Generate Schedule</button>
				 		</div>
				 	</center>
				</div>
		  	</div>
	  	</div>

	  	<div class="container search">
	  		<div class="row">
	  			<div class="col-md-6 col-md-offset-3">
		  			<form role="form">
	  					<div class="form-group">
	   	 					<input type="email" class="form-control" placeholder="Search for a course...">
	  					</div>
	  				</form>
  				</div>
	  		</div>
	  	</div>

	  	<div class="courses">
		  	<div class="container">
		  		<div class="row">
		  			<div class="col-md-6">
			  			@foreach($sections1 as $key => $value)
							<div class="course">
								<div class="code">{{ $key }}</div>
								<div class="name">{{ $value }}</div>
							</div>
						@endforeach
	  				</div>
		  			<div class="col-md-6">
			  			@foreach($sections2 as $key => $value)
							<div class="course">
								<div class="code">{{ $key }}</div>
								<div class="name">{{ $value }}</div>
							</div>
						@endforeach
	  				</div>
		  		</div>
		  	</div>
	  	</div>

	  	<div class="courseType">
	  		<div class="container">
		  		<div class="row">
		  			<center>
		  				<button type="button" class="btn btn-default back">Go back</button>
		  			</center>
	  			</div>
	  			<div class="row change">
	  				Hey
	  			</div>
	  		</div>
	  	</div>

	  	<div class="footer">
		      	<div class="container">
		        		<p class="muted credit">&copy; 2014 Your Secret Developer</p>
		      	</div>
		</div>
		
		<script type="text/javascript">
		var obj = <?php echo json_encode($courses); ?>;
		var selected = [];
		var courses = {};

		selected = window.location.hash.replace("#", "").split("-");
		if(selected.length != 0 && selected[0] == "") selected = [];
		if(selected.length != 0)
		{
			for (var code in obj) {
				for (var title in obj[code]) {
					for(var activity in obj[code][title])
					{
						for(var section in obj[code][title][activity])
						{
							if(selected.indexOf(section) > -1)
							{
								for(var instructor in obj[code][title][activity][section])
								{
									var date = [];
									obj[code][title][activity][section][instructor].forEach(function(time) {
										id = code + title.replace("'","") + activity;
										for(var t in time.code) date.push[code[t]];
									});

									var course = section.split(" ");
									course = course[0] + course[1][0] + course[1][1] + course[1][2];
									if(courses[course] == undefined) courses[course] = {};
									courses[course][section] = date;
								}
							}
						}
					}
				}
			}
		}
		window.location.hash = "";

		function changeScheduleCount()
		{
			if(selected.length == 0) $("#generate").prop("disabled",true);
			else $("#generate").prop("disabled",false);
			$("#generate").html("Generate Schedule ( " + selected.length + " Selected )");
		}
		changeScheduleCount();

		$("#generate").click(function() {
			var hash = "";
			for(var select in selected)
			{
				if(hash != "") hash += "0d";
				hash += selected[select];
			}
			window.location = "{{url('generate')}}/" + hash.replace(/[ ]/g, "0s");
		});

		$(".back").click(function() {
			$(".courseType").fadeOut(200, function() {
				$(".courses").fadeIn(200);
			});
		});

		$(".course").click(function() {
			var code = $(this).find(".code").html();
			var text = "";
			for (var title in obj[code]) {
				for(var activity in obj[code][title])
				{
					text += '<div class="option"><input type="checkbox" class="global"><div class="title">' + title + ' </div>';
					text += "<div class='activity'>" + activity + "</div>";
					for(var section in obj[code][title][activity])
					{
						for(var instructor in obj[code][title][activity][section])
						{
							text += "<div class='section'><input id='" +  section + "' type='checkbox' class='local'>" + section + ": <div class='instructor'>" + instructor + "</div><div class='disabled'>Conflicting Classes</div>";
							obj[code][title][activity][section][instructor].forEach(function(time) {
								text += "<div class='meet' id='";
								for(var t in time.code) text += time.code[t] + "-";
								text = text.substring(0, text.length - 1);
								text += "'>" + time.day + " " + time.start + " - " + time.end + "</div>";
							});
							text += "</div>";
						}
					}
					text += "</div>";
				}
			}
			$(".courseType .change").html(text);


			init();
		});


		function checkPossible(courses, schedule)
		{
			if(Object.keys(courses).length == 0) return true;
			var next = jQuery.extend(true, {}, courses);
			delete next[Object.keys(courses)[0]];
			course = courses[Object.keys(courses)[0]];
			for(var meet in course)
			{
				meet = course[meet];
				var newschedule = [];
				for(s in schedule) newschedule[s] = schedule[s];
				var success = true;
				for(var date in meet)
				{
					date = meet[date];
					if(newschedule.indexOf(date) > -1) success = false;
					else newschedule.push(date);
				}
				if(success && checkPossible(next, newschedule)) return true;
			}
			return false;
		}

		function removeSchedule(course, id)
		{
			delete courses[course][id];
			if(Object.keys(courses[course]).length == 0) delete courses[course];
		}

		function addSchedule(course, id, date)
		{
			if(courses[course] == undefined) courses[course] = {};
			courses[course][id] = date;
			return checkPossible(courses, []);
		}

		function init()
		{
			$(".courseType .global").click(function() {
				var checked = this.checked;
				$(this).parent().find(".local").each(function() {
					if(!$(this).is(":disabled"))
					{
						$(this).prop('checked', checked);
						$(this).trigger("change");
					}
				});
			});

			$(".section").click(function(e) {
				if($(e.target).attr("class") != "local")
				{
					$(this).find(".local").each(function() {
						if(!$(this).is(":disabled"))
						{
							$(this).prop('checked', !this.checked);
							$(this).trigger("change");
						}
					});
				}
			});

			$(".courseType .local").change(function() {
				var id = $(this).attr("id");
				var date = $(this).parent().find(".meet").attr("id");
				var course = id.split(" ");
				course = course[0] + course[1][0] + course[1][1] + course[1][2];

				if($(this).parent().find(".meet").length > 1)
				{
					date = "";
					$(this).parent().find(".meet").each(function() {
						if(date != "") date += "-"
						date += $(this).attr("id");
					});
				}
				date = date.split("-");

				if(this.checked && selected.indexOf(id) == -1)
				{
					$(this).parents(".option").find(".global").prop("checked", true);
					selected.push(id);
					if(!addSchedule(course, id, date))
					{
						$(this).prop('checked', false);
						$(this).attr("disabled", true);
						$(this).parent().find(".disabled").css("display", "inline-block");

						selected.splice(selected.indexOf(id), 1);
						removeSchedule(course, id);
					}
				}
				else if(!this.checked && selected.indexOf(id) > -1)
				{
					selected.splice(selected.indexOf(id), 1);
					removeSchedule(course, id);
					$("input[type=checkbox]").attr("disabled", false);
					$(".disabled").css("display", "none");
				}
				changeScheduleCount();
			});

			for (var id in selected) {
				$("[id='" + selected[id] + "']").parents(".option").find(".global").prop("checked", true);
				$("[id='" + selected[id] + "']").prop("checked", true);
			}
			
			$(".courses").fadeOut(200, function() {
				$(".courseType").fadeIn(200);
			});
		}
		</script>
	</body>
</html>