<?php

namespace App\Controller;

use App\Entity\Quiz;
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

        // dd($quizes);



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

}
