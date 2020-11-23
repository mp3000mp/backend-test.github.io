<?php

declare(strict_types=1);

class Luhn
{
    public function isValid(string $str): bool
    {
        $str = str_replace(' ', '', $str);
        if (1 === preg_match('/^\d{2,}$/', $str)) {
            // from https://en.wikipedia.org/wiki/Luhn_algorithm#PHP_implementation
            $length = strlen($str);
            $sum = (int) $str[$length - 1];
            $parity = $length % 2;
            for ($index = 0; $index < $length - 1; ++$index) {
                $digit = (int) $str[$index];
                if ($index % 2 === $parity) {
                    $digit *= 2;
                }
                if ($digit > 9) {
                    $digit -= 9;
                }
                $sum += $digit;
            }

            return 0 === $sum % 10;
        }

        return false;
    }
}

// test
$data = [
    '4539 1488 0343 6467' => true,
    '8273 1232 7352 0569' => false,
    '5-9' => false,
    '$59' => false,
    'a59' => false,
];

$c = new Luhn();
foreach ($data as $input => $expected) {
    if ($c->isValid($input) !== $expected) {
        echo "Error: $input";
        exit();
    }
}
echo 'Luhn OK';
