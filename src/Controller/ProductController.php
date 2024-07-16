<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;

class ProductController extends AbstractController
{
    #[Route('/', name: 'product_list')]
    public function index(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $productRepository = $entityManager->getRepository(Book::class);
        $products = $productRepository->findAll();

        $session->set('products', $products);

        return $this->render('product/index.html.twig', [
            'products' => $session->get('products'),
        ]);
    }
}
