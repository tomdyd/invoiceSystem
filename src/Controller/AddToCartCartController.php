<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class AddToCartCartController extends AbstractController
{
    #[Route('/add-to-cart/{id}', name: 'add_to_cart', defaults: ['id' => 0])]
    public function index(Book $book, SessionInterface $session): Response
    {
        $cart = $session->get('cart', []);

        $cart[$book->getId()] = $book;

        $session->set('cart', $cart);
        $session->getFlashBag()->add('success', 'Produkt został dodany do koszyka.');

        return $this->render('product/index.html.twig', [
            'cart' => $session->get('cart'),
            'products' => $session->get('products'),
        ]);
    }
}
