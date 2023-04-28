<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Quiz;
use App\Form\CategoriesType;
use App\Form\QuizType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    #[Route('/quiz', name: 'app_quiz')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {

        // rendered all quizes
        $quizes = $this->getUser()->getQuiz();



        // form to add quiz
        $quiz = new Quiz();
        $quiz->setOwner($this->getUser());

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
        }

        return $this->render('quiz/index.html.twig', [
            'form' => $form->createView(),
            'quizes' => $quizes,
        ]);

    }

    #[Route('/quiz/delete/{id}', name: 'app_quiz_delete')]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $quiz = $em->getRepository(Quiz::class)->findOneBy([
            'owner' => $this->getUser(),
            'id' => $id,
        ]);

        if (!$quiz) {
            throw new Exception('Invalid id');
        }

        $em->remove($quiz);
        $em->flush();

        return $this->redirectToRoute('app_quiz');
    }


    #[Route('/quiz/edit/{id}', name: 'app_quiz_edit')]
    public function edit(int $id, EntityManagerInterface $em, Request $request): Response
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
            return $this->redirect($request->getUri());
        }

        // dd($categories[0]->getQuestions());


        return $this->render('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }


}
