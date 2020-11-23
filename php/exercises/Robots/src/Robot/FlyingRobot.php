<?php

declare(strict_types=1);

namespace App\Robot;

class FlyingRobot extends AbstractRobot
{
    public function getTypePrefix(): string
    {
        return 'FL';
    }

    public function move(): void
    {
        echo 'Flying...';
    }
}
