<?php

namespace App\DataFixtures;

use DateTime;
use DateTimeImmutable;


use App\Entity\Invoice;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * L'encodeur de mots de passe
     *
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $startDate = new DateTime('-6 months');
        $endDate = new DateTime('now');
        
        for($u = 0; $u < 10; $u++) {
            $user = new User();
            
            $chrono = 1;

            $hash = $this->hasher->hashPassword($user,"password");

            $user->setFirstName($faker->firstName())
                 -> setLastName($faker->lastName) 
                 ->setEmail($faker->email)
                 ->setPassword($hash);

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

    /**
     * Get l'encodeur de mots de passe
     *
     * @return  UserPasswordHasherInterface
     */ 
    public function getHasher()
    {
        return $this->hasher;
    }
}
