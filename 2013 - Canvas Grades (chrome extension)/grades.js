$(document).ready(function()
{
	setInterval(function()
	{
		var total = 0;
		var totalPossible = 0;

		$(".student_assignment.group_total").each(function() { 
			possible = $(this).find(".points_possible").html().replace("% of Final","") / 100;
			totalPossible += possible;
			points = $(this).find(".score_teaser").html().split(" / ");
			points = points[0] / points[1] * possible * 100;
			total += points;
			//points = Math.round(points * 100) / 100;
			//text = "<td style='font-weight: normal; font-size: 0.8em;' class='total'>" + points + "</td>";
			//$(this).append(text);
		});

		if (totalPossible != 0)
		{
			total = Math.round(total / totalPossible * 1000) / 1000;
			if($(".final_total").size() == 0) $("#content").append("<div class='final_total' style='text-align:center; font-weight:bold;'>Final Grade: " + total + "%</div>");
			$(".final_total").html("Final Grade: " + total + "%");
		}
	}, 1000);
});