<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

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
        $manager->flush();
    }
}
