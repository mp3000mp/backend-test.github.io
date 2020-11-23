<?php

declare(strict_types=1);

namespace App\Robot;

abstract class AbstractRobot implements RobotInterface
{
    /**
     * @var string
     */
    protected $name;

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @internal let the factory handle this
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    abstract public function move(): void;
}
