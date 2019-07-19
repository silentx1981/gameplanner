<?php

namespace GamePlanner;

require __DIR__."/../vendor/autoload.php";

$teams = [
	"Team 1",
	"Team 2",
	"Team 3",
	"Team 4",
	"Team 5",
];

$planner = new Planner();
$plan = $planner->generate(5);

foreach ($plan as $game) {
	echo $teams[$game[1]] . " - ".$teams[$game[2]]."<br>";
}

