<?php

namespace Laravel\Prompts;

class StepBuilder
{
    protected array $steps = [];
    protected array $responses = [];

    public function add($step, $revert = null, $key = null)
    {
        if ($revert === null) {
            $revert = fn () => null;
        }
        $this->steps[] = [$step, $revert, $key];
        return $this;
    }

    public function run()
    {

        $previousRevert = null;
        $steps = [];

        //play the array
        foreach ($this->steps as  [$step, $revert, $key]) {
            $steps[] = [$step, $previousRevert, $key];
            $previousRevert = $revert;
        }

        $index = 0; // Initialize index

        while ($index <= count($steps)) {
            [$step, $revert, $key] = $steps[$index];
            $shouldRevert = false;
            Prompt::$revertClosure = $revert ? function () use (&$shouldRevert) {
                $shouldRevert = true;
            } : null;
            $this->responses[$key ?? $index] = $step($this->responses);

            if ($shouldRevert) {
                $revert($this->responses);
                $index--;
            } else {
                $index++;
            }
        }

        return $this->responses;
    }
};
