<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * Les fixtures vont nous permettre de faire des tests fonctionnels.
     * Un test fonctionnel est un test qui test les pages avec leur affichage 
     * ou l'envoie de données à travers un formulaire par exemple.
     * 
     * Les fixtures vont nous servir à enregistrer de fausses données en BDD pour tester nos pages par la suite.
     * 
     * Pour installer la dépendance fixtures: composer require --dev orm-fixtures
     * Fixtures n'est utilisé qu'en dev d'où le --dev dans la commande.
     * Généralement on va coupler fixtures avec l'utilisation de la dépendance Fakerphp.
     * 
     * Pour utiliser Fakerphp on lance la commande suivante: composer require fakerphp/faker --dev
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i < 11; $i++) { 
            $category = new Category;
            $category->setName("Categorie n°".$i);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
