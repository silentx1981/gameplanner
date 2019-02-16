<?php

namespace GamePlanner;

use RuntimeException;

class Planner
{
	private $loopCounter = 10;
	private $default = [
		"1" => -1,
		"2" => -1,
	];

	public function generate($countTeams = 3, $switchFirstSecond = false)
	{
		if ($countTeams < 3 || $countTeams > 10)
			throw new RuntimeException("Only Support from 3 to 10 Teams");

		$result = $this->generateCombinations($countTeams);
		$result = $this->reorder($result);
		$result = $this->balance($result);
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

	private function reorder($data)
	{
		$counter = 0;
		$counterFix = 0;
		$result = [];

		while($counter < $this->loopCounter) {

			$values = [];
			$countData = count($data);
			for ($i = 0; $i < $countData; $i++) {
				$value = $result[count($result) - 1] ?? null;
				if ($value === null) {
					$result[] = $data[$i];
					$counterFix = 0;
					continue;
				} else if ($value[1] !== $data[$i][1] && $value[2] !== $data[$i][1] && $value[1] !== $data[$i][2] && $value[2] !== $data[$i][2]) {
					$result[] = $data[$i];
					$counterFix = 0;
					continue;
				} else if ($counterFix > 3) {
					$result[] = $data[$i];
					$counterFix = 0;
					continue;
				}
				$values[] = $data[$i];
			}

			$data = $values;

			$counter++;
			$counterFix++;
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