<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Questions;
use App\Entity\Quiz;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/category/{id}', name: 'app_category')]
    public function index(int $id, Request $request): Response
    {

        // check if user has permissions
        $category = $this->em->getRepository(Categories::class)->findUserByCategoryId($id, $this->getUser())[0];
        if(!$category) {
            throw new Exception('Access denied');
        }

        $currentCategory = $this->em->getRepository(Categories::class)->find($id);
        
        //form to add questions
        $question = new Questions();
        $question
            ->setCategory($currentCategory)
            ->setIsCompleted(false);
        
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
            $this->em->persist($form->getData());
            $this->em->flush();
            return $this->redirect($request->getUri());
        }

        //get all questions to render
        $questions = $this->em->getRepository(Questions::class)->findBy([
            'category' => $currentCategory,
        ]);
        
        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
            'questions' => $questions,
            'category' => $currentCategory,
        ]);
    }

    #[Route('/category/delete/{id}', name: 'app_category_delete')]
    public function delete(int $id, Request $request): Response
    {
        $category = $this->em->getRepository(Categories::class)->findUserByCategoryId($id, $this->getUser())[0];

        if(!$category) {
            throw new Exception('Access denied');
        }

        $this->em->remove($category);
        $this->em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

}
