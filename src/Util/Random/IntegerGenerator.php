<?php
namespace Fmizzell\GeneticAlgorithm\Util\Random;


class IntegerGenerator {
    private $min;
    private $max;

    public function __construct($min, $max) {
        $this->min = $min;
        $this->max = $max;
    }

    public function generate() {
        return rand($this->min, $this->max);
    }

}