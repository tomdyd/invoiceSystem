<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\InvoiceDataFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, Request $request, EntityManagerInterface $entityManager): Response
    {
        $person = new Person;
        $form = $this->createForm(InvoiceDataFormType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $email = $person->getEmail();
            $existingPerson = $entityManager->getRepository(Person::class)->findOneBy(['email' => $email]);

            $name = ucfirst(strtolower($person->getName()));
            $surname = ucfirst(strtolower($person->getSurname()));
            $person->setName($name);
            $person->setSurname($surname);

            $session->set('person', $person);

            if (!$existingPerson) {
                $entityManager->persist($person);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_generate_pdf');
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $session->get('cart'),
            'quantity' => $session->get('quantity'),
            'form' => $form
        ]);
    }
}
