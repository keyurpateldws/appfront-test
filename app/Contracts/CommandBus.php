<?php

namespace App\Contracts;

interface CommandBus
{
    public function dispatch(object $command): void;
} 