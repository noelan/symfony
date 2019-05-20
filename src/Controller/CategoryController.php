<?php
// src/Controller/CategoryController.php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryController extends AbstractController
{
	/**
	*@Route("/category/new", name="category_new")
	*/
	public function add(Request $request, ObjectManager $manager)
	{
		$category = new Category();	
		$form = $this->createForm(CategoryType::class, $category);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getdata();
			$manager->persist($data);
 			$manager->flush();
		}

		return $this->render('/Blog/add_category.html.twig', [
			'formCategory' => $form->createView()
		]);
	}

	public function delete(Request $request, ObjectManager $manager)
	{
			echo "hey";
	}
}