<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recette;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hash;

    public function __construct(UserPasswordHasherInterface $hash)
    {
        $this->hash = $hash;
    }

    public function load(ObjectManager $manager): void
    {
        //Ingrédients
        $ingredients = [];
        for ($i=1; $i < 50; $i++) { 
            $ingredient = new Ingredient();
            $ingredient->setName('Ingredient ' . $i)
            ->setPrice(mt_rand(0,100));

            $ingredients[] = $ingredient;
            $manager->persist($ingredient);      
        }

        //Recettes
        for ($j=1; $j < 25; $j++) { 
            $recette = new Recette();
            $recette->setName('Recette ' . $j)
            ->setTime(mt_rand(0,1) == 1 ? mt_rand(1, 60) : null)
            ->setNumberOfPersons(mt_rand(0,1) == 1 ? mt_rand(1, 12) : null)
            ->setDifficulty(mt_rand(0,1) == 1 ? mt_rand(1, 5) : null)
            ->setDescription('lalalalalalalalala'. $j)
            ->setPrice(mt_rand(1, 100))
            ->setIsFavorite(mt_rand(0,1) == 1 ? true : false);

            for ($k=0; $k <mt_rand(5, 15) ; $k++) { 
                $recette->addIngredient($ingredients[mt_rand(0, count($ingredients) -1)]);
            }
            $manager->persist($recette);      
        }

        //Users
        for ($i=0; $i < 10; $i++) { 
            $user = new User();
            $user->setFullName('User ' . $i)
            ->setPseudo('Pseudo'. $i)
            ->setEmail('user'. $i. '@gmail.com')
            ->setRoles(['ROLE_USER']);

            $hashPassword = $this->hash->hashPassword(
                $user,
                'password'
            );

            $user->setPassword($hashPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
