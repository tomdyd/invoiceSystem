<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class AddToCartCartController extends AbstractController
{
    #[Route('/add-to-cart/{id}', name: 'add_to_cart', defaults: ['id' => 0])]
    public function index(Book $book, SessionInterface $session, Request $request): Response
    {
        $cart = $session->get('cart', []);
        $quantityArray = $session->get('quantity', []);

        $quantity = $request->request->get('quantity');

        $cart[$book->getId()] = $book;

        if(isset($quantityArray[$book->getId()]))
        {
            $quantityArray[$book->getId()] += $quantity;
        }
        else{
            $quantityArray[$book->getId()] = $quantity;
        }

        $session->set('quantity', $quantityArray);
        $session->set('cart', $cart);
        $session->getFlashBag()->add('success', 'Produkt został dodany do koszyka.');

        return $this->render('product/index.html.twig', [
            'cart' => $session->get('cart'),
            'quantity' => $session->get('quantity'),
            'products' => $session->get('products'),
        ]);
    }
}
