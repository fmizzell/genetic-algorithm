<?php
include "vendor/autoload.php";
use Fmizzell\GeneticAlgorithm\Chromosome\Binary;
use Fmizzell\GeneticAlgorithm\FitnessEvaluator;
use Fmizzell\GeneticAlgorithm\Mutator;
use Fmizzell\GeneticAlgorithm\Breeder;
use Fmizzell\GeneticAlgorithm\Evolution;

$population = array();
for ($i = 0; $i < 5; $i++) {
    $population[] = new Binary(5);
}

$evolution = new Evolution($population, new FitnessEvaluator());
$evolution->setMaximunNumberOfGenerations(1000);
$evolution->setStopCriteria(5);
$evolution->addOperator(new Mutator(), 1, 10);
$evolution->addOperator(new Breeder(), 2, 90);
$evolution->evolve();


