<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Questions;
use App\Form\QuestionType;
use App\Service\ImageResize;
use App\Service\ImageSaver;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('category/{catId}/question/{qId}', name: 'app_question_edit')]
    public function index(int $catId, int $qId, EntityManagerInterface $em, Request $request, ImageSaver $imageSaver, ImageResize $imageResize): Response
    {

        // check if user has permissions
        $category = $em->getRepository(Categories::class)->findUserByCategoryId($catId, $this->getUser())[0];
        if(!$category) {
            throw new Exception('Access denied');
        }

        $question = $em->getRepository(Questions::class)->find($qId);

        $form = $this->createForm(QuestionType::class, $question);
        $form->get('content')->setData($question->getContent());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if ($form->get('image')->getData()) {
                $path = $this->getParameter('image_directory') . '/' . $question->getImage();
                $fs = new Filesystem();
                $fs->remove($path);
                $question->setImage($imageSaver->saveImage($form));
                $imageResize->resizeImage($question->getImage());
            }
            $em->persist($form->getData());
            $em->flush();
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('question/edit.html.twig', [
            'form' => $form->createView(),
            'anwsers'=> $question->getAnwsers(),
            'image' => $question->getImage(),
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
