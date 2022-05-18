<?php

namespace App\Controller;

use App\Entity\Rightsowner;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Royalty;
use App\Entity\Viewing;

class PaymentController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_index")
     */
    public function entryPoint(): Response
    {
        return $this->index();
    }

    /**
     * @Route("/royaltymanager/payments", name="app_payment")
     */
    public function index(): Response
    {
        $royalties = $this->em
            ->getRepository(Royalty::class)
            ->findAll();

        $data = [];

        foreach ($royalties as $royalty) {
            
            $episodesOwned = $this->em
            ->getRepository(Rightsowner::class)
            ->findEpisodesOwnedByStudio($royalty->getStudio()->getId());
            $numViewings = 0;
            foreach ($episodesOwned as $episode) {
                $viewings = $this->em
                ->getRepository(Viewing::class)
                ->findViewingsByEpisode($episode->getEpisode()->getId());

                $numViewings += count($viewings);
                
            }
            $data[] = [
                'studioId' => $royalty->getStudio()->getId(),
                'studio' => $royalty->getStudio()->getName(),
                'viewings' => $numViewings,
                'payment' => ($royalty->getPayment())*$numViewings,
            ];

        }
 
        return $this->json($data);
    }


    /**
     * @Route("/royaltymanager/payment/{studioId}", name="payment_show", methods={"GET"})
     */
    public function show(string $studioId): Response
    {
        $royalty = $this->em
            ->getRepository(Royalty::class)
            ->findRoyaltyByStudio($studioId);
 
        if (!$royalty) {
 
            return $this->json('No royalty found for studio id ' . $studioId, 404);
        }

        $episodesOwned = $this->em
        ->getRepository(Rightsowner::class)
        ->findEpisodesOwnedByStudio($royalty->getStudio()->getId());
 
        foreach ($episodesOwned as $episode) {
            $viewings = $this->em
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
