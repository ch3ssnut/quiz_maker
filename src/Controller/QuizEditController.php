<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Quiz;
use App\Form\CategoriesType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizEditController extends AbstractController
{
    #[Route('/quiz/edit/{id}', name: 'app_quiz_edit')]
    public function index(int $id, EntityManagerInterface $em, Request $request): Response
    {

        $quiz = $em->getRepository(Quiz::class)->findOneBy([
            'id' => $id,
        ]);

        // if current user isn't an owner throw an error


        if($this->getUser() === !$quiz->getOwner()) {
            throw new Exception('You don\'t have access to this quiz');
        }

        $categories = $em->getRepository(Categories::class)->findBy([
            'quiz' => $quiz,
        ]);

        // add new category

        $category = new Categories();
        $category->setQuiz($quiz);

        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
        }


        return $this->render('quiz_edit/index.html.twig', [
            'quiz' => $quiz,
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }
}
