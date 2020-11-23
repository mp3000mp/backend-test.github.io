<?php

declare(strict_types=1);

namespace App\Robot;

use App\NameGenerator\NameGenerator;

class RobotFactory
{
    /**
     * @var RobotInterface[]
     */
    private $registry = [];

    /**
     * @var NameGenerator
     */
    private $nameGenerator;

    public function __construct(NameGenerator $nameGenerator)
    {
        $this->nameGenerator = $nameGenerator;
    }

    /**
     * @throws RobotFactoryException
     */
    public function create(string $className): RobotInterface
    {
        if (class_exists($className)) {
            if (in_array(RobotInterface::class, class_implements($className), true)) {
                $robot = new $className();
                $this->setRobotName($robot);

                return $robot;
            }
            throw new RobotFactoryException("Error: class '$className' does not implement RobotInterface.");
        }
        throw new RobotFactoryException("Error: class '$className' not found.");
    }

    public function reset(RobotInterface $robot): void
    {
        $this->setRobotName($robot);
    }

    private function setRobotName(RobotInterface $robot): void
    {
        $name = $this->generateName($robot);
        while (!$this->checkNameUnique($name)) {
            $name = $this->generateName($robot);
        }
        $robot->setName($name);
        $this->registry[] = $robot;
    }

    private function checkNameUnique(string $name): bool
    {
        foreach ($this->registry as $robot) {
            if ($name === $robot->getName()) {
                return false;
            }
        }

        return true;
    }

    /**
     * pattern = {TYPE}{DIGITS}{ALPHAS} with
     *   {TYPE} = 2 upper case depending to robot type
     *   {DIGITS} = 3 random digits
     *   {ALPHAS} = 2 random alphas.
     */
    private function generateName(RobotInterface $robot): string
    {
        $name = $robot->getTypePrefix();
        $name .= $this->nameGenerator->genRandomNum(3);
        $name .= $this->nameGenerator->genRandomAlpha(2);

        return $name;
    }

    /**
     * @return string[]
     */
    public function getAllNames(): array
    {
        return array_map(function ($robot) {
            return $robot->getName();
        }, $this->registry);
    }

    public function resetAll(): void
    {
        foreach ($this->registry as $robot) {
            $this->setRobotName($robot);
        }
    }
}
