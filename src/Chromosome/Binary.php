<?php
namespace Fmizzell\GeneticAlgorithm\Chromosome;

class Binary extends Chromosome {

  public function __construct($size) {
    parent::__construct($size);
    $this->genes = array_fill(0, $size, 0);
  }

  public function flipGene($key) {
    $new_value = 0;
    if ($this->genes[$key] == 0) {
      $new_value = 1;
    }
    $this->genes[$key] = $new_value;
  }
}

