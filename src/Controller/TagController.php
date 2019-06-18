<?php

namespace App\Controller;

use App\Entity\Tag; 
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tag")
 */
class TagController extends AbstractController
{


    /**
     * @Route("/", name="tag_index", methods={"GET"})
     */
    public function index(TagRepository $tagRepository): Response
    {   dump($tagRepository->findAll());
        return $this->render('tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tag_new", methods={"GET","POST"})
     */
    public function new(Request $request, \Swift_Mailer $mailer): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();
            $this->addFlash('success', 'Le tag a été créer');


            $message=(new \Swift_Message('New tag created'))
                ->setTo($_POST['email'])
                ->setBody(
                    $this->renderView('Blog/email.html.twig',
                        [
                            'name' => $_POST['email'],
                            'tag' => $tag,
                        
                        ]
                        ),
                        'text/html'
                    )
                ;
            $mailer->send($message);

            return $this->redirectToRoute('tag_index');
        }

        return $this->render('tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tag_show", methods={"GET"})
     */
    public function show(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
        ]);
    }
    

    /**
     * @Route("/{id}/edit", name="tag_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'The tag is edited');

            return $this->redirectToRoute('tag_index', [
                'id' => $tag->getId(),
            ]);
        }

        return $this->render('tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tag_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tag $tag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tag);
            $entityManager->flush();
            $this->addFlash('danger', "Le tag a été supprimé");
        }

        return $this->redirectToRoute('tag_index');
    }

    /**
     * @Route("/{name}", name="show_bytag")
     */ 
    public function showByTag(Tag $tag)
    {
        $tagArticles = $tag->getArticles();

        return $this->render('tag/show_bytag.html.twig', ['tagArticles' =>  $tagArticles,
                                                          'tag' => $tag
                                                      ]);

    }

   

}
