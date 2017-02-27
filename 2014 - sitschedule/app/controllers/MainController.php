<?php

class MainController extends Controller {
	public function generate($id)
	{
		$selected = str_replace("0s", " ", $id);
		$selected = explode("0d", $selected);

		$xmlstring = file_get_contents(public_path() . "/test.xml");
		$courses = simplexml_load_string($xmlstring);
		$dates = [];
		foreach($courses->Course as $course)
		{
			$section = (string)$course->attributes()->Section;
			$title = (string)$course->attributes()->Title;
			if(in_array($section, $selected))
			{
				$date = [];
				$id = "";
				foreach($course->Meeting as $meet)
				{
					$activity = (string)$meet->attributes()->Activity;
					$start = (string)$meet->attributes()->StartTime;
					$start = (int)explode(":", $start)[0];
					$startc = $start + 5;

					$end = (string)$meet->attributes()->EndTime;
					$end = explode(":", $end);
					if(count($end) > 1)
					{
						if((int)$end[1] != 0) $end = (int)$end[0] + 1;
						else $end = (int)$end[0];
					}
					$endc = $end + 5;

					$days = (string)$meet->attributes()->Day;
					foreach(str_split($days) as $day)
					{
						$dayc = $day;

						$code = [];
						if(is_int($endc))
						{
							for($x = $startc; $x < $endc; $x++)
							{
								$code[] = $dayc . $x;
							}
						}
						$date = array_merge($date, $code);
					}

					$sec = explode(" ", $course->attributes()->Section)[0];
					if(strlen($sec) > 4) $sec = substr($sec, 0, 4);
					$id = $sec . str_replace("'","", $title) . $activity;
				}
				if(!isset($dates[$id])) $dates[$id] = [];
				$dates[$id][] = [$section, array_unique($date)];
			}
		}
		$schedules = $this->checker($dates);
		foreach($courses->Course as $course)
		{
			$section = (string)$course->attributes()->Section;
			$title = (string)$course->attributes()->Title;
			$inst = (string)$course->attributes()->Instructor1;
			$room = "";
			foreach($course->Meeting as $meet)
			{
				$room = (string)$meet->attributes()->Building . " " . (string)$meet->attributes()->Room;
			}
			foreach($schedules as $key => $schedule)
			{
				foreach($schedule as $key2 => $value)
				{
					if($value == $section)
					{
						$schedules[$key][$key2] = $section . "<br>" . $title . "<br>" . $inst . "<br>" . $room;
					}
				}
			}
		}
		foreach($schedules as $key => $schedule)
		{
			$found = [];
			$colorfound = [];
			$colors = ["f69988", "f48fb1", "ce93d8", "9575cd", "7986cb", "91a7ff", "4fc3f7", "4dd0e1", "4db6ac", "42bd41", "aed581", "dce775", "ffb74d", "ff8a65"];
			$count = 0;
			foreach($schedule as $key2 => $value)
			{
				if($value)
				{
					$location = $value . $key2[0];
					if(isset($found[$location]))
					{
						$schedules[$key][$key2] = [$colorfound[$value], "-"];
					}
					else
					{
						if(isset($colorfound[$value])) $color = $colorfound[$value];
						else
						{
							$color = $colors[$count];
							$count++;
						}
						$found[$location] = true;
						$colorfound[$value] = $color;
						$schedules[$key][$key2] = [$color, $schedules[$key][$key2]];
					}
				}
			}
		}

		$url = url("/") . "#" . implode("-", $selected);

		return View::make("generate")->with("schedules", $schedules)->with("url", $url);
	}

	public function checker($list)
	{
		$calendar = [];
		foreach(["M", "T", "W", "R", "F"] as $day)
		{
			for($x = 0; $x <= 24; $x++)
			{
				$calendar[$day . $x] = false;
			}
		}
		$this->schedules = 0;
		return $this->checkHelper($list, $calendar);
	}

	public $schedules;
	
	public function checkHelper($list, $calendar)
	{
		if(count($list) == 0)
		{
			$this->schedules++;
			return [$calendar];
		}
		$combination = [];
		$next = $list;
		unset($next[array_keys($next)[0]]);
		foreach(array_values($list)[0] as $value)
		{
			if($this->schedules < 30)
			{
				$attempt = $calendar;
				$skip = false;
				foreach($value[1] as $day)
				{
					if($attempt[$day]) $skip = true;
					$attempt[$day] = $value[0];
				}
				if (!$skip) $combination = array_merge($combination, $this->checkHelper($next, $attempt));
			}
		}
		return $combination;
	}

	public function home()
	{
		$list = [
			"BME" => "Biomedical Engineering",
			"BIA" => "Business Intelligence and Analytics",
			"BT" => "Business and Technology",
			"CHE" => "Chemical Engineering",
			"CH" => "Chemistry",
			"CE" => "Civil Engineering",
			"CAL" => "College of Arts & Letters",
			"CPE" => "Computer Engineering",
			"CS" => "Computer Science",
			"CM" => "Construction Management",
			"D" => "Dean's Offices",
			"EE" => "Electrical Engineering",
			"EM" => "Engineering Management",
			"ES" => "Enterprise Systems",
			"EN" => "Environmental Engineering",
			"EMT" => "Executive Management of Technology",
			"FE" => "Financial Engineering",
			"H" => "Honor Program",
			"HAR" => "Humanities/Art",
			"HHS" => "Humanities/History",
			"HLI" => "Humanities/Literature",
			"HMU" => "Humanities/Music",
			"HPL" => "Humanities/Philosophy",
			"HST" => "Humanities/Science and Technology Studies",
			"HSS" => "Humanities/Social Sciences",
			"MIS" => "Information Systems",
			"IPD" => "Integrated Product Development",
			"E" => "Interdepartmental Engineering",
			"LFR" => "Language/French",
			"LSP" => "Language/Spanish",
			"MGT" => "Management",
			"MT" => "Materials Engineering",
			"MA" => "Mathematics",
			"ME" => "Mechanical Engineering",
			"NANO" => "Nanotechnology",
			"NE" => "Naval Engineering",
			"NIS" => "Networked Information Systems",
			"OE" => "Ocean Engineering",
			"PME" => "Pharmaceutical Manufacturing",
			"PE" => "Physical Education",
			"PEP" => "Physics & Engineering Physics",
			"PAE" => "Product Architecture and Engineering",
			"COMM" => "Professional Communications",
			"PRV" => "Provost",
			"QF" => "Quantitative Finance",
			"REG" => "Registrar",
			"SEF" => "Science & Engineering Found. for Edu.",
			"SOC" => "Service Oriented Computing",
			"SSW" => "Software Engineering",
			"SYS" => "Systems Engineering",
			"SES" => "Systems Engineering Security",
			"TG" => "Technogenesis",
			"TM" => "Telecommunications Management",
			"ELC" => "English Language Proficiency"
		];
		//$xmlstring = file_get_contents("http://web.stevens.edu/scheduler/core/core.php?cmd=getxml&term=2015S");
		$xmlstring = file_get_contents(public_path() . "/test.xml");
		$courses = simplexml_load_string($xmlstring);
		$sections = [];
		$options = [];
		foreach($courses->Course as $course)
		{
			$section = explode(" ", $course->attributes()->Section)[0];
			if(strlen($section) > 4) $section = substr($section, 0, 4);
			if(!array_key_exists($section, $sections))
			{
				$sections[$section] = $list[$section];
			}
			$title = (string)$course->attributes()->Title;
			$inst = (string)$course->attributes()->Instructor1;
			$option = (string)$course->attributes()->Section;
			$activity = "";
			$failed = false;
			$results = [];
			foreach($course->Meeting as $meet)
			{
				$activity = (string)$meet->attributes()->Activity;

				$start = (string)$meet->attributes()->StartTime;
				if($start != "")
				{
					$start = (int)explode(":", $start)[0] + 5;
					$startc = $start;
					if($start < 12) $start .= " AM";
					else if($start == 12) $start .= " PM";
					else if($start == 24) $start = "12 AM";
					else if($start > 12) $start = ($start - 12) . " PM";

					$end = (string)$meet->attributes()->EndTime;
					$end = explode(":", $end);
					if(count($end) > 1)
					{
						if((int)$end[1] != 0) $end = (int)$end[0] + 1;
						else $end = (int)$end[0];
						$end += 5;
					}
					$endc = $end;
					if($end < 12) $end .= " AM";
					else if($end == 12) $end .= " PM";
					else if($end == 24) $end = "12 AM";
					else if($end > 12) $end = ((int)$end - 12) . " PM";


					$days = (string)$meet->attributes()->Day;
					foreach(str_split($days) as $day)
					{
						$dayc = $day;
						if($day == "M") $day = "Mon";
						if($day == "T") $day = "Tue";
						if($day == "W") $day = "Wed";
						if($day == "R") $day = "Thu";
						if($day == "F") $day = "Fri";

						if($dayc == "M") $dayc = 100;
						if($dayc == "T") $dayc = 200;
						if($dayc == "W") $dayc = 300;
						if($dayc == "R") $dayc = 400;
						if($dayc == "F") $dayc = 500;

						$code = [];
						if(is_int($endc))
						{
							for($x = $startc; $x < $endc; $x++)
							{
								$code[] = $dayc + $x;
							}
						}
						$val = ["day" => $day, "start" => $start, "end" => $end, "code" => $code];
						if(!in_array($val, $results))
						{
							if (count($code) < 5) $results[] =  $val;
							else $failed = true;
						}
					}
				}
			}
			if(count($results) != 0 && !$failed) $options[$section][$title][$activity][$option][$inst] = $results;
		}
		$sections1 = array_slice($sections, 0, count($sections) / 2);
		$sections2 = array_slice($sections, count($sections) / 2);
		return View::make("home")->with("sections1", $sections1)->with("sections2", $sections2)->with("courses", $options);
	}
}