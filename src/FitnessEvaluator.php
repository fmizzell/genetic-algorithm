<?php
/**
 * Created by PhpStorm.
 * User: fmizzell
 * Date: 5/5/16
 * Time: 9:08 PM
 */

namespace Fmizzell\GeneticAlgorithm;


class FitnessEvaluator {
  public function evaluate($chromosome) {
    $fitness = 0;
    foreach ($chromosome->getIterator() as $gene) {
      $fitness += $gene;
    }
    return $fitness;
  }
}