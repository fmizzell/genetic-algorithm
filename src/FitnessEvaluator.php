<?php
namespace Fmizzell\GeneticAlgorithm;
use Fmizzell\GeneticAlgorithm\Chromosome\Chromosome;

class FitnessEvaluator {
  public function evaluate(Chromosome $chromosome) {
    $fitness = 0;
    foreach ($chromosome->getIterator() as $gene) {
      $fitness += $gene;
    }
    return $fitness;
  }

}
