<?php
/**
 * Created by PhpStorm.
 * User: fmizzell
 * Date: 5/5/16
 * Time: 8:55 PM
 */

namespace Fmizzell\GeneticAlgorithm;


use Traversable;

class SelectionPool implements \IteratorAggregate {
  private $fitnessEvaluator;
  private $population;
  private $chromosomesFitness = array();

  private $populationFitness;
  private $highestFitness;

  private $pool = array();

  public function __construct($population, $fitness_evaluator) {
    $this->population = $population;
    $this->fitnessEvaluator = $fitness_evaluator;
    $this->populatePool();
  }

  public function getNextChromosome() {
    $random_integer = rand(0, count($this->pool) - 1);
    return $this->population[$this->pool[$random_integer]];
  }

  public function getHighestFitness() {
    return $this->highestFitness;
  }

  private function populatePool() {
    $population_fitness = $this->calculatePopulationFitness();

    foreach ($this->chromosomesFitness as $key => $fitness) {
      // Handle the case where the fitness or the population fitness is 0.
      if ($fitness == 0 || $population_fitness == 0) {
        $this->pool += array_fill(count($this->pool), 1, $key);
      }
      else {
        $fitness_percent_to_total = round(($fitness * 100) / $population_fitness);
        $this->pool += array_fill(count($this->pool), $fitness_percent_to_total, $key);
      }
    }
  }

  private function calculatePopulationFitness() {
    $total_fitness = 0;
    $highest_fitness = 0;

    foreach ($this->population as $key => $chromosome) {
      $fitness = $this->fitnessEvaluator->evaluate($chromosome);

      $highest_fitness = ($fitness > $highest_fitness) ? $fitness : $highest_fitness;

      $this->chromosomesFitness[$key] = $fitness;

      $total_fitness += $fitness;
    }
    $this->populationFitness = $total_fitness;
    $this->highestFitness = $highest_fitness;

    return $total_fitness;
  }

  public function getIterator() {
    return new \ArrayIterator(($this->pool));
  }
}