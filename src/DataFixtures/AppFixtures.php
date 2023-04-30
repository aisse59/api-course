<?php

namespace App\DataFixtures;

use DateTime;
use DateTimeImmutable;


use App\Entity\Invoice;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $startDate = new DateTime('-6 months');
        $endDate = new DateTime('now');
        $chrono = 1;

        for($u = 0; $u < 10; $u++) {
            $user = new User();
            $user->setFirstName($faker->firstName())
                 -> setLastName($faker->lastName) 
                 ->setEmail($faker->email)
                 ->setPassword("password");

            $manager->persist($user);    

            for ($c = 0; $c < mt_rand(5,20); $c++) {
                $customer = new Customer();
                $customer->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName)
                    ->setCompany($faker->company)
                    ->setEmail($faker->email);
    
                $manager->persist($customer);
    
                for ($i = 0; $i < mt_rand(3, 10); $i++) {
                    $invoice = new Invoice();
                    $invoice->setAmout($faker->randomFloat(2, 250, 5000))
                        ->setSentAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween($startDate, $endDate)))
                        ->setStatus($faker->randomElement(['SENT', 'PAID', 'CANCELLED']))
                        ->setCustomer($customer)
                        ->setChrono($chrono);
    
                    $chrono++;    
    
                    $manager->persist($invoice);
                }
            }    
        }

        

        $manager->flush();
    }
}
