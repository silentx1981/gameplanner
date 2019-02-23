<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class PlannerTest extends TestCase
{
	public function testCountGames()
	{
		$planner = new GamePlanner\Planner();
		for ($i = 3; $i < 10; $i++)
			$this->assertCount($i*($i - 1)/2, $planner->generate($i));
	}


	public function testGeneratedTeams()
	{
		$planner = new \GamePlanner\Planner();
		$this->assertEquals(0, $planner->generate(3)[0][1]);
		$this->assertEquals(0, $planner->generate(4)[2][2]);
		$this->assertEquals(1, $planner->generate(5)[5][1]);
		$this->assertEquals(0, $planner->generate(6)[9][2]);
		$this->assertEquals(0, $planner->generate(7)[12][2]);
		$this->assertEquals(6, $planner->generate(8)[18][1]);
		$this->assertEquals(7, $planner->generate(9)[27][2]);
	}

	public function testRuntimeExceptions()
	{
		$planner = new \GamePlanner\Planner();
		$this->expectException(RuntimeException::class);
		$planner->generate(0);
		$planner->generate(1);
		$planner->generate(2);
		$planner->generate(11);
		$planner->generate(1014);
	}
}