<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/category/delete/{id}', name: 'app_category_delete')]
    public function delete(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $category = $em->getRepository(Categories::class)->findUserByCategoryId($id, $this->getUser())[0];

        if(!$category) {
            throw new Exception('Access denied');
        }

        $em->remove($category);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

}
