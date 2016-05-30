<?php

namespace Fmizzell\GeneticAlgorithm;
use Fmizzell\GeneticAlgorithm\Util\Random\IntegerGenerator;


class Breeder {
  private $randomIntegerGenerator;

  public function __construct() {

  }

  public function setRandomIntegerGenerator(IntegerGenerator $generator) {
    $this->randomIntegerGenerator = $generator;
  }

  public function getChildren($chromosome1, $chromosome2) {
    if (!isset($this->randomIntegerGenerator)) {
      $this->randomIntegerGenerator = new IntegerGenerator(0, count($chromosome1) - 1);
    }

    $point = $this->randomIntegerGenerator->generate();

    $pieces1 = $this->split($chromosome1, $point);
    $pieces2 = $this->split($chromosome2, $point);

    $chromosomes = array();
    $chromosomes[] = $this->getNewChromosome($pieces1[0], $pieces2[1], $chromosome1);
    $chromosomes[] = $this->getNewChromosome($pieces2[0], $pieces1[1], $chromosome1);
    return $chromosomes;
  }

  private function split($chromosome, $point) {
    $pieces[0] = array();
    $pieces[1] = array();
    foreach ($chromosome as $key => $gene) {
      $index = ($key < $point) ? 0 : 1;
      $pieces[$index][] = $gene;
    }
    return $pieces;
  }

  private function getNewChromosome($pieces1, $pieces2, $chromosome_example) {
    $chromosome = clone $chromosome_example;
    $genes = array_merge($pieces1, $pieces2);
    foreach ($genes as $key => $gene) {
      $chromosome[$key] = $gene;
    }
    return $chromosome;
  }

  public function execute($chromosome1, $chromosome2) {
    return $this->getChildren($chromosome1, $chromosome2);
  }
}