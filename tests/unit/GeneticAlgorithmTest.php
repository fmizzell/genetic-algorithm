<?php
use Fmizzell\GeneticAlgorithm\Chromosome\Binary;
use Fmizzell\GeneticAlgorithm\Mutator;
use Fmizzell\GeneticAlgorithm\Breeder;
use Fmizzell\GeneticAlgorithm\SelectionPool;
use Fmizzell\GeneticAlgorithm\FitnessEvaluator;
use \Mockery as m;
use Codeception\Util\Debug as d;


class GeneticAlgorithmTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testChromosome() {
        $chromosome = new Binary(5);

        // Accessing genes.
        $this->assertEquals($chromosome[0], 0);

        // Testing access exceptions.
        $this->tester->expectException(\Exception::class, function() {
            $chromosome = new Binary(2);
            d::debug($chromosome[2]);
        });

        // Testing count.
        $this->assertEquals(count($chromosome), 5);

        // Testing setting a gene.
        $chromosome[2] = 1;
        $this->assertEquals("{$chromosome}", "00100");
        // Testing access exceptions.
        $this->tester->expectException(\Exception::class, function() {
            $chromosome = new Binary(2);
            $chromosome[2] = 0;
        });

        // Testing unsetting a gene.
        unset($chromosome[3]);
        $this->assertEquals("{$chromosome}", "0010");

        // Testing unsetting exceptions.
        $this->tester->expectException(\Exception::class, function() {
            $chromosome = new Binary(2);
            unset($chromosome[2]);
        });
    }

    public function testMutator()
    {
        $pc = m::mock('Fmizzell\GeneticAlgorithm\Util\Random\ProbabilityChecker');
        $pc->shouldReceive('check')->times(6)->andReturn(TRUE, TRUE, FALSE, FALSE,TRUE, TRUE);
        $chromosome = new Binary(6);
        $mutator = new Mutator();
        $mutator->setProbabilityChecker($pc);
        $mutant = $mutator->getMutant($chromosome);
        $this->assertEquals("110011", (string) $mutant);
    }

    public function testBreeder() {
        $chromosome1 = new Binary(5);
        foreach ($chromosome1 as $key => $gene) {
            $chromosome1[$key] = 1;
        }
        $chromosome2 = new Binary(5);
        $breeder = new Breeder();
        $children = $breeder->getChildren($chromosome1, $chromosome2);
    }

    public function testSelectionPool() {
        $population = array();

        // Create chromosomes with predifined fitness.
        $fitnesses = array(5, 3, 2);

        foreach ($fitnesses as $fitness) {
            $counter = 0;
            $chromosome = new Binary(5);
            while ($counter < $fitness) {
                $chromosome->flipGene($counter);
                $counter++;
            }
            $population[] = $chromosome;
        }


        $fitness_evaluator = new FitnessEvaluator();
        $sp = new SelectionPool($population, $fitness_evaluator);
        // Count how many times a chromosome appears in the pool.
        $counts = array_fill(0,3,0);
        foreach ($sp as $chromosome_id) {
            $counts[$chromosome_id]++;
        }

        // Check the each chromosome appears the right number of times.
        foreach ($population as $key => $chromosome) {
            $fitness = $fitness_evaluator->evaluate($chromosome);
            $this->assertTrue(($fitness * 10) == $counts[$key]);
        }

        // Check that getHighestFitness returns the right value.
        $this->assertTrue($sp->getHighestFitness() == 5);
    }

}