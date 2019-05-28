<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Article;
use App\Entity\Category;
use Faker;



class ArticleFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
       
    	$article = New Article();
        $article->setTitle('Symfony');
        $article->setContent("C'est cool");
        $manager->persist($article);
        $article->setCategory($this->getReference('categorie_0'));
        $manager->flush();
    }

     public function getDependencies()
    {
     return [CategoryFixtures::class];
    }
}
