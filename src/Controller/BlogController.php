<?php
// src/Controller/BlogController.php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Article;
use App\Service\Slugify;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Tag;

class BlogController extends AbstractController
{


    /**
     * @Route("/", name="blog_index")
    */
    public function index()
    {
    	$categories = $this->getDoctrine()
 						   ->getRepository(Category::class)
 						   ->findAll();
        return $this->render('/Blog/index.html.twig', ['categories' => $categories
			]);
    }

    /**
     * @Route("/blog/category/{name}", name="blog_category")
     */
    public function showByCategory(Category $category, Article $article)
    {
    	// $category = $this->getDoctrine()
 				// 		 ->getRepository(Category::class)
 				// 		 ->findOneBy(['name' => mb_strtolower($Category)]);
 		$articles = $category->getArticles();

          

 		return $this->render('/Blog/category.html.twig', ['articles' => $articles,
 														  'category' =>$category,

                                                       
			]);
    }

    /**
     * @Route("/blog/new", name="blog_new")
     * @Route("/blog/edit/{id}", name="blog_edit")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager, Slugify $slugify)
    {

    	if(!$article) {
    	$article = new Article();
    	}

    	$categories = $this->getDoctrine()
    					   ->getRepository(Category::class)
    					   ->findAll();
    	$form = $this->createFormBuilder($article)
    				 ->add('title')
    				 ->add('content')
    				 ->add('category',ChoiceType::class, array(
    				 	'choices' => $categories,
    				 	'choice_label' => "name"
    				 ))
                     ->add('tags', EntityType::class, [
                        'class' => Tag::class,
                        'choice_label' => 'name',
                        'multiple' => true,
                        'expanded' => true,
                        'by_reference' => false,

                        ])
    				 ->getForm();

 	   	$form->handleRequest($request);
 	   	if ($form->isSubmitted() && $form->isValid()) 
 	   	{
 	   		$data = $form->getData();
            $article->setSlug($slugify->generate($article->getTitle()));
 			$manager->persist($data);
 			$manager->flush();	

 		return $this->redirectToRoute('show_one', ['id' => $article->getId()	]);
 	   	}

 		return $this->render('/Blog/create_article.html.twig', [
 				'formArticle' => $form->createView(),
 				// editMode 
 				'editMode' => $article->getId()
 		]);
    }

    /**
     * @Route("/blog/show_one/{id}", name="show_one")
     */
    public function showOne(Article $article)
    {   
    	return $this->render('/Blog/show_one.html.twig', ['article' => $article]);
    }
}