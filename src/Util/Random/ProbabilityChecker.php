<?php
namespace Fmizzell\GeneticAlgorithm\Util\Random;


class ProbabilityChecker {
  public function check($probability) {
    $random = rand(0,100);
    return ($random >=  $probability) ? TRUE : FALSE;
  }
}