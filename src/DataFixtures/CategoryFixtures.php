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
    	for ($i = 1; $i <= 1000; $i++) {
       $category = new Category();
       $category->setName("category " . $i);
       $manager->persist($category);

       $tag = new Tag();
       $tag->setName("tag " . $i);
       $manager->persist($tag);

       $article = new Article();
       $article->setTitle("article " . $i);
       $article->setSlug($this->slugify>generate($article->getTitle()));
       $article->setContent("article " . $i . " content");
       $article->setCategory($category);
       $article->addTag($tag);
       $manager->persist($article);
        }

        $manager->flush();
    }
}
