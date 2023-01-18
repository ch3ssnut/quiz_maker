<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Questions;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/question/{id}', name: 'app_question_edit')]
    public function index(int $id): Response
    {


        return $this->render('question/index.html.twig', [
            'controller_name' => 'QuestionController',
        ]);
    }

    /**
     * @Route("/question/delete/{id}", name="app_question_delete")
     */
    public function delete(int $id, Request $request, EntityManagerInterface $em): Response
    {
        // Get question entity by id
        $question = $em->getRepository(Questions::class)->find($id);

        // get id of category from question repository and check if user has access to this category
        $catId = $question->getCategory()->getId();
        $currentCategory = $em->getRepository(Categories::class)->findUserByCategoryId($catId, $this->getUser());

        if(!$currentCategory) {
            throw new Exception('You don\'t have access to this question ');
        }

        $em->remove($question);
        $em->flush();


        return $this->redirect($request->headers->get('referer'));
    }
}
