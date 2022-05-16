<?php

namespace App\Controller;

use App\Entity\Rightsowner;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Royalty;
use App\Entity\Viewing;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payments", name="app_payment")
     */
    public function index(): Response
    {
        $royalties = $this->getDoctrine()
            ->getRepository(Royalty::class)
            ->findAll();

        $data = [];

        foreach ($royalties as $royalty) {
            $episodesOwned = $this->getDoctrine()
            ->getRepository(Rightsowner::class)
            ->findEpisodesOwnedByStudio($royalty->getStudio()->getId());

            foreach ($episodesOwned as $episode) {
                $viewings = $this->getDoctrine()
                ->getRepository(Viewing::class)
                ->findViewingsByEpisode($episode->getId());

                $numViewings = count($viewings);

                $data[] = [
                    'studioId' => $royalty->getStudio()->getId(),
                    'studio' => $royalty->getStudio()->getName(),
                    'viewings' => $numViewings,
                    'payment' => ($royalty->getPayment())*$numViewings,
                ];
                
            }

        }
 
        return $this->json($data);
    }


    /**
     * @Route("/payment/{studioId}", name="payment_show", methods={"GET"})
     */
    public function show(int $studioId): Response
    {
        $royalty = $this->getDoctrine()
            ->getRepository(Royalty::class)
            ->findRoyaltyByStudio($studioId);
 
        if (!$royalty) {
 
            return $this->json('No royalty found for studio id ' . $studioId, 404);
        }

        $episodesOwned = $this->getDoctrine()
        ->getRepository(Rightsowner::class)
        ->findEpisodesOwnedByStudio($royalty->getStudio()->getId());
 
        foreach ($episodesOwned as $episode) {
            $viewings = $this->getDoctrine()
            ->getRepository(Viewing::class)
            ->findViewingsByEpisode($episode->getId());

            $numViewings = count($viewings);

            $data[] = [
                'studioId' => $royalty->getStudio()->getId(),
                'studio' => $royalty->getStudio()->getName(),
                'viewings' => $numViewings,
                'payment' => ($royalty->getPayment())*$numViewings,
            ];
            
        }
         
        return $this->json($data);
    }


}
