<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Article;
use Faker;




class CategoryFixtures extends Fixture
{
	const CATEGORIES = ['PHP', 'JS', 'Java', 'Python', 'C'];

    public function load(ObjectManager $manager)
    {
    	$faker  =  Faker\Factory::create('fr_FR');
    	foreach (self::CATEGORIES as $key => $catName) {
    		$category = new Category();
    		$category->setName($catName);
    		$manager->persist($category);
    		$this->addReference('categorie_' . $key, $category);
    		for ($i=0; $i <10 ; $i++) { 
	            $article = New Article();
	            $article->setTitle($faker->name);
	            $article->setContent($faker->text);
	            $manager->persist($article);
	            $article->setCategory($this->getReference('categorie_' . $key, $category));
	           
        }
    		
    	}
    	$manager->flush();
    }
}
