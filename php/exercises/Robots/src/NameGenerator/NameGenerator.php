<?php

declare(strict_types=1);

namespace App\NameGenerator;

class NameGenerator
{
    public function genRandomAlpha(int $charLength = 1): string
    {
        $r = '';
        for ($i = 0; $i < $charLength; ++$i) {
            $r .= chr(random_int(ord('A'), ord('Z')));
        }

        return $r;
    }

    public function genRandomNum(int $charLength = 1): string
    {
        $r = '';
        for ($i = 0; $i < $charLength; ++$i) {
            $r .= random_int(0, 9);
        }

        return $r;
    }
}
