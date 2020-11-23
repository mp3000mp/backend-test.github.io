<?php

declare(strict_types=1);

namespace App\Robot;

class WalkingRobot extends AbstractRobot
{
    public function getTypePrefix(): string
    {
        return 'WK';
    }

    public function move(): void
    {
        echo 'Walking...';
    }
}
