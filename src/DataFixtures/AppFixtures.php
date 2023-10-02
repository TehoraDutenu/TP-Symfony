<?php

namespace App\DataFixtures;

use App\Entity\Owner;
use Faker\Factory;
use App\Entity\Rent;
use App\Entity\User;
use Faker\Generator;
use App\Entity\TypeRent;
use App\Entity\UserInfo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private Generator $faker;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadTypeRent($manager);
        $this->loadRent($manager);
        $this->loadUserInfo($manager);
        $this->loadUser($manager);

        $manager->flush();
    }

    public function loadTypeRent(ObjectManager $manager): void
    {
        $typeRentArray = [
            ['key' => 1, 'label' => 'Mobile-home 3prs', 'imagePath' => '/images/mh3.jpg', 'price' => 50],
            ['key' => 2, 'label' => 'Mobile-home 4prs', 'imagePath' => '/images/mh4.jpg', 'price' => 54],
            ['key' => 3, 'label' => 'Mobile-home 5prs', 'imagePath' => 'images/mh5.jpg', 'price' => 57],
            ['key' => 4, 'label' => 'Mobile-home 6-8prs', 'imagePath' => '/images/mh68.jpg', 'price' => 64],
            ['key' => 5, 'label' => 'Caravane 2 places', 'imagePath' => '/images/car2.jpg', 'price' => 45],
            ['key' => 6, 'label' => 'Caravane 4 places', 'imagePath' => '/images/car4.jpg', 'price' => 48],
            ['key' => 7, 'label' => 'Caravane 6 places', 'imagePath' => '/images/car6.jpg', 'price' => 54],
            ['key' => 8, 'label' => 'Emplacement 8m²', 'imagePath' => '/images/em8.jpg', 'price' => 12],
            ['key' => 9, 'label' => 'Emplacement 12m²', 'imagePath' => '/images/em12.jpg', 'price' => 14]
        ];

        foreach ($typeRentArray as $typeRentData) {
            $typeRent = new TypeRent();
            $typeRent->setLabel($typeRentData['label'])
                ->setImagePath($typeRentData['imagePath'])
                ->setPrice($typeRentData['price']);

            $manager->persist($typeRent);
            $this->addReference('typeRent_' . $typeRentData['key'], $typeRent);
        }
    }

    public function loadRent(ObjectManager $manager): void
    {
        // - Récupérer les 9 types de location
        $typeRents = [];
        for ($i = 1; $i <= 9; $i++) {
            $typeRents[] = $this->getReference('typeRent_' . $i);
        }

        // - Créer 10 locations pour chaque type
        foreach ($typeRents as $typeRent) {
            for ($i = 1; $i <= 10; $i++) {
                $rent = new Rent();
                $label = $typeRent->getLabel() . ' ' . $i;
                $rent->setLabel($label)
                    ->setTypeRent($typeRent)
                    ->setHasOwner(true);

                $manager->persist($rent);
                $this->setReference('rent_' . (count($typeRents) * 10 + $i), $rent);
            }
        }
    }


    public function loadUserInfo(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 135; $i++) {
            $userInfo = new UserInfo();
            $userInfo->setFirstname($this->faker->firstName)
                ->setLastname($this->faker->lastName)
                ->setAddress($this->faker->streetAddress)
                ->setZipCode($this->faker->postcode)
                ->setCity($this->faker->city)
                ->setCountry($this->faker->country)
                ->setPhone($this->faker->phoneNumber);

            $manager->persist($userInfo);
            $this->addReference('userInfo_' . $i, $userInfo);
        }
    }

    public function loadUser(ObjectManager $manager): void
    {
        // - Load l'user Admin
        $user = new User();
        $user->setEmail('admin@admin.com')
            ->setPassword($this->encoder->hashPassword($user, 'admin'))
            ->setRoles(['ROLE_ADMIN'])
            ->setUserInfo($this->getReference('userInfo_1'));

        $manager->persist($user);
        $this->addReference('user_1', $user);

        // - Load l'user Office
        $user = new User();
        $user->setEmail('office@office.com')
            ->setPassword($this->encoder->hashPassword($user, 'office'))
            ->setRoles(['ROLE_OFFICE'])
            ->setUserInfo($this->getReference('userInfo_2'));

        $manager->persist($user);
        $this->addReference('user_2', $user);

        // - Load l'user Proprietaire
        for ($i = 3; $i <= 33; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email)
                ->setPassword($this->encoder->hashPassword($user, 'proprietaire'))
                ->setRoles(['ROLE_PROPRIETAIRE'])
                ->setUserInfo($this->getReference('userInfo_' . $i));

            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        // - Load l'user Locataire
        for ($i = 34; $i <= 100; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email)
                ->setPassword($this->encoder->hashPassword($user, 'locataire'))
                ->setRoles(['ROLE_LOCATAIRE'])
                ->setUserInfo($this->getReference('userInfo_' . $i));

            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }
    }
}
