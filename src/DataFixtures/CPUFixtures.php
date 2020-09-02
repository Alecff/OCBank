<?php

namespace App\DataFixtures;

use App\Entity\CPU;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CPUFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cpu = new CPU();
        $cpu
            ->setName('i5-8600k')
            ->setManufacturer('Intel')
            ->setBaseClock(3.6)
            ->setBoostClock(4.3)
            ->setReleaseYear(2017)
            ->setCores(6)
            ->setThreads(6);

        $manager->persist($cpu);

        $cpu2 = new CPU();
        $cpu2
            ->setName('i7-8700k')
            ->setManufacturer('Intel')
            ->setBaseClock(3.8)
            ->setBoostClock(4.5)
            ->setReleaseYear(2017)
            ->setCores(6)
            ->setThreads(12);



        $manager->persist($cpu2);
        $manager->flush();
    }
}
