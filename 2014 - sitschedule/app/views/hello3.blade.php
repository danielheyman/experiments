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
		text = "";
		var dates = {};
		var selected = [];

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
									var id = "";
									var date = [];
									obj[code][title][activity][section][instructor].forEach(function(time) {
										id = code + title.replace("'","") + activity;
										for(var t in time.code) date.push[code[t]];
									});
									if(dates[id] == undefined) dates[id] = [];
									dates[id].push([section, date]);
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
								text += "<div class='meet' id='" + code + title.replace("'","") + activity + "::";
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

		var schedules = [[{}, {}]];

		function removeSchedule(id)
		{
			var newschedule = [];
			for(var s in schedules) {
				s = schedules[s];
				if(s[0][id] != undefined) delete s[0][id];
				for(var key in s[1])
				{
			    		if(s[1][key].indexOf(id) > -1) s[1][key].splice(s[1][key].indexOf(id), 1);
			    		if(s[1][key].length == 0) delete s[1][key];
				}
				newschedule.push(s);
			}
			schedules = [];
			for(var s in newschedule)
			{
				var string = JSON.stringify(newschedule[s]);
				if(string != "[{},{}]")
				{
					var found = false;
					for(var s2 in schedules)
					{
						if(string == JSON.stringify(schedules[s2])) found = true;
					}
					if(!found) schedules.push(newschedule[s]);
				}
			}
			if(schedules.length == 0) schedules.push([{}, {}]);
		}

		function addSchedule(title, id, date)
		{
			var newschedule = [];
			for(var s in schedules) {
				s = schedules[s];
				var found = false;
				for(var key in s[0])
				{
				    	if(s[0][key] == title) found = key;
				}
				if(found)
				{
					newschedule.push($.extend(true, [], s));
					delete s[0][found];
					for(var key in s[1])
					{
				    		if(s[1][key].indexOf(found) > -1) s[1][key].splice(s[1][key].indexOf(found), 1);
				    		if(s[1][key].length == 0) delete s[1][key];
					}
				}
				for(var d in date)
				{
					d = date[d];
					if(s[1][d] == undefined) s[1][d] = [];
					s[1][d].push(id);
				}
				s[0][id] = title;
				newschedule.push(s);
			}
			schedules = [];
			for(var s in newschedule)
			{
				var found = false;
				for(var s2 in schedules)
				{
					if(JSON.stringify(newschedule[s]) == JSON.stringify(schedules[s2])) found = true;
				}
				if(!found) schedules.push(newschedule[s]);
			}
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
				var date = $(this).parent().find(".meet").attr("id").split("::");
				if($(this).parent().find(".meet").length > 1)
				{
					date[1] = "";
					$(this).parent().find(".meet").each(function() {
						if(date[1] != "") date[1] += "-"
						date[1] += $(this).attr("id").split("::")[1];
					});
				}
				date[1] = date[1].split("-");
				if(this.checked && selected.indexOf(id) == -1)
				{
					$(this).parents(".option").find(".global").prop("checked", true);
					selected.push(id);
					addSchedule(date[0], id, date[1]);
					//courses[id] = [date[0], date[1]];
					//sortSchedule();
				}
				else if(!this.checked && selected.indexOf(id) > -1)
				{
					selected.splice(selected.indexOf(id), 1);
					removeSchedule(id);
					//delete courses[id];
					//sortSchedule();
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