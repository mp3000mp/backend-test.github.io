<?php

declare(strict_types=1);

namespace Tests\Robots;

use App\NameGenerator\NameGenerator;
use App\Robot\FlyingRobot;
use App\Robot\RobotFactory;
use App\Robot\RobotFactoryException;
use App\Robot\WalkingRobot;
use PHPUnit\Framework\TestCase;

class RobotFactoryTest extends TestCase
{
    private function getFactory(bool $expectedOnce = true): RobotFactory
    {
        $nameGeneratorMock = $this->getMockBuilder(NameGenerator::class)
            ->getMock();
        if ($expectedOnce) {
            $nameGeneratorMock->expects(self::once())
                ->method('genRandomAlpha')
                ->willReturn('AA');
            $nameGeneratorMock->expects(self::once())
                ->method('genRandomNum')
                ->willReturn('000');
        }

        return new RobotFactory($nameGeneratorMock);
    }

    private function generateNameGeneratorMockForTwice(): NameGenerator
    {
        $nameGeneratorMock = $this->getMockBuilder(NameGenerator::class)
            ->getMock();
        $nameGeneratorMock->expects(self::exactly(2))
            ->method('genRandomAlpha')
            ->willReturn('AA', 'BB');
        $nameGeneratorMock->expects(self::exactly(2))
            ->method('genRandomNum')
            ->willReturn('000', '111');

        return $nameGeneratorMock;
    }

    public function namePatternProvider(): array
    {
        return [
            'Flying robot' => [FlyingRobot::class, 'FL'],
            'Walking robot' => [WalkingRobot::class, 'WK'],
        ];
    }

    public function createProvider(): array
    {
        return [
            'Flying robot' => [FlyingRobot::class, 'Flying...'],
            'Walking robot' => [WalkingRobot::class, 'Walking...'],
        ];
    }

    /**
     * @dataProvider createProvider
     */
    public function testCreate(string $className, string $expected): void
    {
        $factory = $this->getFactory();
        $this->expectOutputString($expected);
        $robot = $factory->create($className);
        $robot->move();

        self::assertEquals($robot->getTypePrefix(), substr($robot->getName(), 0, 2));
    }

    public function testCreateBadClass(): void
    {
        $this->expectException(RobotFactoryException::class);

        $factory = $this->getFactory(false);
        $robot = $factory->create('SwimmingRobot');
    }

    public function testCreateBadInterface(): void
    {
        $this->expectException(RobotFactoryException::class);

        $factory = $this->getFactory(false);
        $robot = $factory->create(RobotFactoryException::class);
    }

    /**
     * @dataProvider namePatternProvider
     */
    public function testNamePattern(string $className, string $typeCode): void
    {
        $factory = $this->getFactory();
        $robot = $factory->create($className);

        self::assertMatchesRegularExpression('/^'.$typeCode.'\d{3}[A-Z]{2}$/', $robot->getName());
    }

    public function testReset(): void
    {
        $nameGeneratorMock = $this->generateNameGeneratorMockForTwice();

        $factory = new RobotFactory($nameGeneratorMock);
        $robot = $factory->create(FlyingRobot::class);

        $before = $robot->getName();

        $factory->reset($robot);
        self::assertNotEquals($before, $robot->getName());
    }

    public function testNameCollision(): void
    {
        /**
         * NameGenerator will generate twice the same thing, then another thing
         * this way, if we ask it to generate three times, it's ok (create=1, reset=1collision+1valid name).
         */
        $nameGeneratorMock = $this->getMockBuilder(NameGenerator::class)
            ->getMock();
        $nameGeneratorMock->expects(self::exactly(3))
            ->method('genRandomAlpha')
            ->willReturn('AA', 'AA', 'BB');
        $nameGeneratorMock->expects(self::exactly(3))
            ->method('genRandomNum')
            ->willReturn('000', '000', '111');

        $factory = new RobotFactory($nameGeneratorMock);
        $robot = $factory->create(FlyingRobot::class);

        $before = $robot->getName();

        $factory->reset($robot);
        self::assertNotEquals($before, $robot->getName());
    }

    public function testGetAllNames(): void
    {
        $nameGeneratorMock = $this->generateNameGeneratorMockForTwice();
        $factory = new RobotFactory($nameGeneratorMock);

        $factory->create(FlyingRobot::class);
        $factory->create(WalkingRobot::class);

        $arr = $factory->getAllNames();

        self::assertEquals(['FL000AA', 'WK111BB'], $arr);
    }

    public function testResetAll(): void
    {
        $nameGeneratorMock = $this->getMockBuilder(NameGenerator::class)
            ->getMock();
        $nameGeneratorMock->expects(self::exactly(4))
            ->method('genRandomAlpha')
            ->willReturn('AA', 'BB', 'CC', 'DD');
        $nameGeneratorMock->expects(self::exactly(4))
            ->method('genRandomNum')
            ->willReturn('000', '111', '222', '333');

        $factory = new RobotFactory($nameGeneratorMock);

        $robot1 = $factory->create(FlyingRobot::class);
        $robot2 = $factory->create(WalkingRobot::class);

        $factory->resetAll();

        self::assertEquals('FL222CC', $robot1->getName());
        self::assertEquals('WK333DD', $robot2->getName());
    }
}
