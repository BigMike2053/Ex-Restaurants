<?php

namespace App\Controller;

use App\Form\RestaurantsRechercheType;
use App\Form\RestaurantsType;
use App\Repository\RestaurantsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantsController extends AbstractController
{
    /**
     * @Route("/restaurants", name="restaurants")
     */
    public function restaurants(): Response
    {
        return $this->render('restaurants/index.html.twig', []);
    }

    /**
     * @Route("/restaurants/all", name="restaurants_all")
     */
    public function restaurants_all(RestaurantsRepository $RestaurantsRepository, Request $request): Response
    {
        $restaurants=$RestaurantsRepository->findAll();


        $form=$this->createForm(RestaurantsRechercheType::class);

        $form->handleRequest($request);

        $data=$form->getData();

        return $this->render('restaurants/all.html.twig', ['form'=>$form->createView(), 'restaurants'=>$restaurants]);
    }

    /**
     * @Route("/restaurants/apropos", name="restaurants_apropos")
     */
    public function restaurants_apropos(): Response
    {
        return $this->render('restaurants/apropos.html.twig', []);
    }

    /**
     * @Route("/restaurants/add", name="restaurants_add")
     */
    public function restaurants_add(Request $request): Response
    {
        $form = $this ->createForm(RestaurantsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $restaurant = $form->getData();
            $em=$this->getDoctrine()->getManager();
            $em->persist($restaurant);
            $em->flush();

            return $this->redirectToRoute('restaurants_all');

        } else if ($form->isSubmitted() && !$form->isValid()){
            $this->addFlash("danger","Le restaurant n'est pas bien ajouté");
        }

        return $this->render('restaurants/add_restaurant.html.twig', ['form'=>$form->createView()
        ]);
    }
}