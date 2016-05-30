<?php
namespace Fmizzell\GeneticAlgorithm;

class Evolution {

    /**
     * How many generation to evolve a population.
     */
    private $maximumNumberOfGenerations = 10;

    /**
     * A level of fitness at which we will stop evolving.
     */
    private $stopCriteria;

    /**
     * The highest fitness that has been seen so far.
     */
    private $highestFitness = 0;

    private $populations;

    private $currentPopulation;
    private $fitnessEvaluator;
    private $operators = array();

    public function __construct($population, $fitness_evaluator) {
        $this->currentPopulation = $population;
        $this->fitnessEvaluator = $fitness_evaluator;
    }

    public function setMaximunNumberOfGenerations($max) {
        $this->maximumNumberOfGenerations = $max;
    }

    public function setStopCriteria($stop) {
        $this->stopCriteria = $stop;
    }

    public function addOperator($operator, $number_of_inputs, $probability) {
        $this->operators[] = array(
            'operator' => $operator,
            'number_of_inputs' => $number_of_inputs,
            'probability' => $probability
        );
    }

    public function evolve() {
        $generation = 0;

        while ($this->highestFitness < $this->stopCriteria &&
            $generation < $this->maximumNumberOfGenerations) {

            $this->populations[$generation] = $this->currentPopulation;

            $selection_pool = new SelectionPool($this->currentPopulation, $this->fitnessEvaluator);
            $this->highestFitness = $selection_pool->getHighestFitness();

            // Lets have a check in case that the first population provides the highest fitness.
            if ($this->highestFitness >= $this->stopCriteria) {
                break;
            }

            $this->currentPopulation = $this->getPopulationFromOperators($selection_pool);

            $generation++;
        }
    }

    private function getPopulationFromOperators(SelectionPool $selection_pool) {
        $population = array();

        $number_of_operators = count($this->operators);

        $operations_counter = 0;

        $pc = new Util\Random\ProbabilityChecker();

        // We want a population at least as big as the current population.
        while (count($population) < count($this->currentPopulation)) {
            $current_operator = $this->operators[$operations_counter%$number_of_operators];

            if ($pc->check($current_operator['probability'])) {
                $inputs = array();
                for ($i = 0; $i < $current_operator['number_of_inputs']; $i++) {
                    $inputs[$i] = $selection_pool->getNextChromosome();
                }

                $output = call_user_func_array(array($current_operator['operator'], 'execute'), $inputs);

                if (is_array($output)) {
                    $population = array_merge($population, $output);
                } else {
                    $population[] = $output;
                }
            }

            $operations_counter++;
        }
        return $population;
    }

}
