<?php

namespace GamePlanner;

use RuntimeException;

class Planner
{
	private $default = [
		"1" => -1,
		"2" => -1,
	];

	public function generate($countTeams = 3, $switchFirstSecond = false)
	{
		if ($countTeams < 3 || $countTeams > 10)
			throw new RuntimeException("Only Support from 3 to 10 Teams");

		$class = "GamePlanner\\Mapping\\Map$countTeams";
		if (class_exists($class)) {
			$instance = new $class();
			$result = $instance->getMap();
		} else {
			$result = $this->generateCombinations($countTeams);
			$result = $this->reorder($result, $countTeams);
			$result = $this->balance($result);
		}

		if ($switchFirstSecond)
			$result = $this->switchFirstSecond($result);
		return $result;
	}

	private function generateCombinations($countTeams)
	{
		$combinations = [];
		$result = [];

		for ($i = 0; $i < $countTeams; $i++) {
			for ($j = 0; $j < $countTeams; $j++) {
				if (!in_array("$i.$j", $combinations) && $i !== $j) {
					$result[] = [
						1 => $i,
						2 => $j
					];
					$combinations[] = "$i.$j";
					$combinations[] = "$j.$i";
				}
			}
		}

		return $result;
	}

	private function reorder($data, $countTeams)
	{
		$teamsEmpty = [];
		for ($i = 0; $i < $countTeams; $i++)
			$teamsEmpty[$i] = 0;

		$result = [];
		$countMatches = count($data);
		for ($i = 0; $i < $countMatches; $i++) {
			$teams = $teamsEmpty;

			// Count pause for every team
			foreach ($result as $value)
				foreach ($teams as $tkey => $team)
					if ($tkey === $value[1] || $tkey === $value[2])
						$teams[$tkey] = 0;
					else
						$teams[$tkey]++;

			// Rate every game
			$rate = [];
			foreach ($data as $key => $value)
				$rate[$key] = $teams[$value[1]] + $teams[$value[2]];

			// Get the match with the most pause
			$maxPos = array_keys($rate, max($rate))[0];
			$result[] = $data[$maxPos];
			unset($data[$maxPos]);
			$data = array_values($data);

		}

		return $result;
	}

	private function balance($data)
	{
		$result = [];
		$fs = []; // first/second
		foreach ($data as $value) {
			$first = $value[1];
			$second = $value[2];
			if (!isset($fs[$first]))
				$fs[$first] = $this->default;
			if (!isset($fs[$second]))
				$fs[$second] = $this->default;

			$firstTeam = $fs[$first];
			if ($firstTeam[1] > $firstTeam[2]) {
				$first = $value[2];
				$second = $value[1];
			}

			$result[] = [
				1 => $first,
				2 => $second,
			];

			$fs[$first][1] += 1;
			$fs[$second][2] += 2;

		}

		return $result;
	}

	private function switchFirstSecond($data)
	{
		$result = [];
		foreach ($data as $value)
			$result[] = [
				1 => $value[2],
				2 => $value[1],
			];

		return $result;
	}
}