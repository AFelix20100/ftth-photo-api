<?php

namespace App\DataFixtures;

use App\Entity\Operation;
use App\Entity\Command;
use App\Entity\Photo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        // Create 3 Commands
        $commands = [];
        for ($i = 0; $i < 3; $i++) {
            $command = new Command();
            $command->setDescription($faker->sentence);
            $manager->persist($command);
            $commands[] = $command;
        }

        // Create 3 Operations
        $operations = [];
        for ($i = 0; $i < 3; $i++) {
            $operation = new Operation();
            $operation->setDescription($faker->sentence);
            $manager->persist($operation);
            $operations[] = $operation;
        }

        // Create 6 Photos
        for ($i = 0; $i < 6; $i++) {
            $photo = new Photo();
            $photo->setImageName(sprintf('photo_%d.jpg', $i + 1));

            // Randomly assign a Photo to a Command or Operation
            if (rand(0, 1) === 0) {
                $photo->setCommand($commands[array_rand($commands)]);
            } else {
                $photo->setOperation($operations[array_rand($operations)]);
            }

            $manager->persist($photo);
        }

        $manager->flush();
    }

    private function generateUniqueCode(int $length = 10): string
    {
        return substr(bin2hex(random_bytes($length / 2)), 0, $length);
    }
}
