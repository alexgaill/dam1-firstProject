<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * Generateur de fausses données via Faker
     *
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

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
        $this->createCategories($manager);
        // $this->createPosts($manager);

        $manager->flush();
    }

    /**
     * Crée des données pour la table category et les persist
     *
     * @param ObjectManager $manager
     * @return void
     */
    private function createCategories (ObjectManager $manager)
    {
        for ($i=1; $i < 11; $i++) { 
            $category = new Category;
            $category->setName($this->faker->sentence(4, true));

            $manager->persist($category);

            $this->createPosts($manager, $category);

        }
    }

    /**
     * Crée des données pour la table post et les persist
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function createPosts (ObjectManager $manager, Category $category)
    {
        for ($i=0; $i < 10; $i++) { 
            $post = new Post;
            $post->setTitle($this->faker->sentence(6, true))
                ->setDescription($this->faker->paragraphs(5, true))
                ->setCreatedAt($this->faker->dateTime())
                ->setCategory($category)
                ;
            
            $manager->persist($post);
        }
    }
}
