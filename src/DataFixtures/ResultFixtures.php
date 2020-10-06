<?php

namespace App\DataFixtures;

use App\Entity\CPU;
use App\Entity\Node;
use App\Entity\Result;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResultFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $cpu = $manager->getRepository(CPU::class)->findOneBy(['Name' => 'i5-8600k']);

        for ($i = 0; $i < 20; $i++) {
            $result = new Result();
            $offset = mt_rand(-50, 50)/1000;
            $result
                ->setUser($user)
                ->setCPU($cpu);
            $manager->persist($result);

            for ($n = 0; $n < 7; $n++) {
                $node = new Node();
                $node
                    ->setClock(4.5+($n/10))
                    ->setVoltage(0.14*pow(1.575,$node->getClock())+$offset+rand(-10, 10)/1000)
                    ->setResult($result)
                    ->setVerified(false);
                $manager->persist($node);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
