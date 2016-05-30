<?php
namespace Fmizzell\GeneticAlgorithm;
use Fmizzell\GeneticAlgorithm\Util\Random\ProbabilityChecker;

class Mutator {
  private $probabilityChecker;

  public function __construct() {
    $this->probabilityChecker = new ProbabilityChecker();
  }

  public function setProbabilityChecker(ProbabilityChecker $checker) {
    $this->probabilityChecker = $checker;
  }

  public function getMutant($chromosome) {
    // Flip genes randomly.
    $chromosome = clone $chromosome;
    foreach ($chromosome->getIterator() as $key => $gene) {
      if ($this->probabilityChecker->check(50)) {
        $chromosome->flipGene($key);
      }
    }
    return $chromosome;
  }

  public function execute($chromosome) {
    return $this->getMutant($chromosome);
  }

}
