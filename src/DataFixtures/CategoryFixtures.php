<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Article;
use App\Service\Slugify;
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
    		for ($i=0; $i <10 ; $i++) { 
    			$slugify = New Slugify();
	            $article = New Article();
	            $article->setTitle($faker->title);
	            $article->setContent($faker->text);
	            $article->setSlug($slugify->generate($article->getTitle()));
	            $manager->persist($article);
	           
        }
    		
    	}
    	$manager->flush();
    }
}
