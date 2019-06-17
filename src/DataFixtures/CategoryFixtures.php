<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Article;
use App\Entity\User;
use App\Service\Slugify;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;





class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
	const CATEGORIES = ['PHP', 'JS', 'Java', 'Python', 'C'];

    public function load(ObjectManager $manager)
    {
    	$faker  =  Faker\Factory::create('fr_FR');
    	foreach (self::CATEGORIES as $key => $catName) {
    		$category = new Category();
    		$category->setName($catName);
            $compteur = 1;

            

    		$manager->persist($category);
    		for ($i=0; $i <10 ; $i++) { 
    			$slugify = New Slugify();
	            $article = New Article();
	            $article->setTitle($faker->title);
	            $article->setContent($faker->title);
	            $article->setCategory($category);
	            $article->setSlug($slugify->generate($article->getTitle()));
                if ($compteur % 2 == 0) {
                    $article->setAuthor($this->getReference('author'));
                }else{
                    $article->setAuthor($this->getReference('admin'));
                }
                $compteur++;
	            $manager->persist($article);
	           
        }
    		
    	}
    	$manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }

}
