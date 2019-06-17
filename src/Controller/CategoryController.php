<?php
// src/Controller/CategoryController.php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Entity\Article;
use App\Form\CategoryType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CategoryController extends AbstractController
{
	/**
	*@Route("/category/new", name="category_new")
	* @IsGranted("ROLE_ADMIN")
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
 			return $this->redirectToRoute('blog_index');
		}

		return $this->render('/Blog/add_category.html.twig', [
			'formCategory' => $form->createView()
		]);
	}


	/**
	 * @Route("/category/delete/{id}", name="category_delete")
	 */
	public function delete(Category $category = null,Article $articles = null, ObjectManager $manager)
	{
			$articles = $category->getArticles();
			foreach ($articles as $article) {
				$category->removeArticle($article);
			}		

			$manager->remove($category);
			$manager->flush();
			
			return $this->redirectToRoute('blog_index');
	}
}