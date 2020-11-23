<?php

declare(strict_types=1);

namespace App\Robot;

interface RobotInterface
{
    public function getTypePrefix(): string;

    public function getName(): string;

    public function setName(string $name): void;

    public function move(): void;
}
