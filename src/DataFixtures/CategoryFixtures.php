<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Article;
use App\Entity\User;
use App\Entity\Tag;
use App\Service\Slugify;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;





class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
	const CATEGORIES = ['PHP', 'JS', 'Java', 'Python', 'C'];

    public function load(ObjectManager $manager)
    {
    	$faker  =  Faker\Factory::create('fr_FR');

    		for ($i=0; $i <1000 ; $i++) { 
                $category = new Category();
                $category->setName("cat" . $i);
                $manager->persist($category);
    			$slugify = New Slugify();
	            $article = New Article();
                $tag = new Tag();
                $tag->setName("tag " . $i);
                $manager->persist($tag);
	            $article->setTitle($faker->title);
	            $article->setContent($faker->title);
	            $article->setCategory($category);
                $article->addTag($tag);
	            $article->setSlug($slugify->generate($article->getTitle()));
                $article->setAuthor($this->getReference('author'));
	            $manager->persist($article);
	           
        }
    		
    	
    	$manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }

}
