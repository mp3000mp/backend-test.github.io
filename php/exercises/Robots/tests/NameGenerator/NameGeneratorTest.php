<?php

declare(strict_types=1);

namespace Tests\Robots;

use App\NameGenerator\NameGenerator;
use PHPUnit\Framework\TestCase;

class NameGeneratorTest extends TestCase
{
    public function testGenRandomAlpha(): void
    {
        $nameGenerator = new NameGenerator();

        // todo better idea to test random ?
        for ($i = 0; $i < 100; ++$i) {
            $r = $nameGenerator->genRandomAlpha(5);
            self::assertMatchesRegularExpression('/^[A-Z]{5}$/', $r);
        }
    }

    public function testGenRandomAlphaWithoutArg(): void
    {
        $nameGenerator = new NameGenerator();

        // todo better idea to test random ?
        for ($i = 0; $i < 100; ++$i) {
            $r = $nameGenerator->genRandomAlpha();
            self::assertMatchesRegularExpression('/^[A-Z]$/', $r);
        }
    }

    public function testGenRandomAlphaNegativeArg(): void
    {
        $nameGenerator = new NameGenerator();
        $r = $nameGenerator->genRandomAlpha(-1);

        self::assertEquals('', $r);
    }

    public function testGenRandomNum(): void
    {
        $nameGenerator = new NameGenerator();

        // todo better idea to test random ?
        for ($i = 0; $i < 100; ++$i) {
            $r = $nameGenerator->genRandomNum(5);
            self::assertMatchesRegularExpression('/^\d{5}$/', $r);
        }
    }

    public function testGenRandomNumWithoutArg(): void
    {
        $nameGenerator = new NameGenerator();

        // todo better idea to test random ?
        for ($i = 0; $i < 100; ++$i) {
            $r = $nameGenerator->genRandomNum();
            self::assertMatchesRegularExpression('/^\d$/', $r);
        }
    }

    public function testGenRandomNumNegativeArg(): void
    {
        $nameGenerator = new NameGenerator();
        $r = $nameGenerator->genRandomNum(-1);

        self::assertEquals('', $r);
    }
}
