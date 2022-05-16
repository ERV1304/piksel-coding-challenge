<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Royalty;
use App\Entity\Studio;

class RoyaltyController extends AbstractController
{
    /**
     * @Route("/royalties", name="app_royalty")
     */
    public function index(): Response
    {
        $royalties = $this->getDoctrine()
            ->getRepository(Royalty::class)
            ->findAll();
 
        $data = [];
 
        foreach ($royalties as $royalty) {
           $data[] = [
               'id' => $royalty->getId(),
               'studio' => $royalty->getStudio()->getName(),
               'payment' => $royalty->getPayment(),
           ];
        }
 
 
        return $this->json($data);
    }

    /**
     * @Route("/royalty", name="royalty_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $studioId = $request->request->get('studio');
        
        $studio = $entityManager->getRepository(Studio::class)->find($studioId);
 
        $royalty = new Royalty();
        $royalty->setStudio($studio);
        $royalty->setPayment($request->request->get('payment'));
 
        $entityManager->persist($royalty);
        $entityManager->flush();
 
        return $this->json('Created new royalty successfully with id ' . $royalty->getId());
    }

    /**
     * @Route("/royalty/{studioId}", name="royalty_show", methods={"GET"})
     */
    public function show(int $studioId): Response
    {
        $royalty = $this->getDoctrine()
            ->getRepository(Royalty::class)
            ->findRoyaltyByStudio($studioId);
 
        if (!$royalty) {
 
            return $this->json('No royalty found for studio id ' . $studioId, 404);
        }
 
        $data =  [
            'id' => $royalty->getId(),
            'studio' => $royalty->getStudio()->getName(),
            'payment' => $royalty->getPayment(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/royalty/{id}", name="royalty_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $royalty = $entityManager->getRepository(Royalty::class)->find($id);
 
        if (!$royalty) {
            return $this->json('No royalty found for id' . $id, 404);
        }
 
        $entityManager->remove($royalty);
        $entityManager->flush();
 
        return $this->json('Deleted a royalty successfully with id ' . $id);
    }

}
