<?php

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

steps()
    ->add(fn () => intro('Welcome To Laravel'), revert: false)
    ->add(fn () => text(
        label: 'Where should we create your project?',
        placeholder: 'E.g. ./laravel',
        validate: fn ($value) => match (true) {
            !$value => 'Please enter a path',
            $value[0] !== '.' => 'Please enter a relative path',
            default => null,
        },
    ))
    ->add(function () {
        $install = confirm(label: "Install Dependencies ?");
        if ($install) {
            spin(fn () => sleep(3), "Installing weee....");
        }
    })
    ->run();
