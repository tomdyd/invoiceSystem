<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class RemoveFromCartController extends AbstractController
{
    #[Route('/remove-from-cart/{id}', name: 'app_remove_from_cart')]
    public function index(SessionInterface $session, $id): Response
    {
        $cartArray = $session->get('cart');
        $quantity = $session->get('quantity');

        unset($cartArray[$id]);
        unset($quantity[$id]);

        $session->set('cart', $cartArray);
        $session->set('quantity', $quantity);

        return $this->redirect('/cart');
    }
}
