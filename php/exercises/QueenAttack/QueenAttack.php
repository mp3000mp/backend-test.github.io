<?php

declare(strict_types=1);

class QueenAttack
{
    /**
     * @throws InvalidArgumentException
     */
    public function placeQueen(int $i, int $j): bool
    {
        if ($i < 0 || $i > 7
            || $j < 0 || $j > 7) {
            throw new InvalidArgumentException('Queen cannot be placed on the board : {'.$i.','.$j.'}');
        }

        return true;
    }

    /**
     * @param int[] $white Coordinates of the white Queen
     * @param int[] $black Coordinates of the black Queen
     *
     * @throws InvalidArgumentException
     */
    public function canAttack(array $white, array $black): bool
    {
        // todo check if white queen and black queen don't have the same coordinates ?
        if ($this->placeQueen(...$white) && $this->placeQueen(...$black)) {
            return $white[0] === $black[0]
                || $white[1] === $black[1]
                || $white[0] - $black[0] === $white[1] - $black[1];
        }

        return false;
    }
}

// test
$data = [
    [[1, 1], [3, 3], true],
    [[1, 4], [1, 3], true],
    [[4, 1], [3, 1], true],
    [[-2, 5], [2, 6], 'err'],
    [[1, 5], [2, 4], false],
];

$c = new QueenAttack();
foreach ($data as $arr) {
    try {
        if ($c->canAttack($arr[0], $arr[1]) !== $arr[2]) {
            echo 'Error: ';
            var_dump($arr);
            exit();
        }
    } catch (InvalidArgumentException $e) {
        if ('err' !== $arr[2]) {
            echo 'Error: ';
            var_dump($arr);
            exit();
        }
    }
}
echo 'Queen OK';
