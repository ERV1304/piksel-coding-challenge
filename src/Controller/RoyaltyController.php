<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Royalty;
use App\Entity\Studio;

class RoyaltyController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/royaltymanager/royalties", name="app_royalty")
     */
    public function index(): Response
    {
        $royalties = $this->em
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
     * @Route("/royaltymanager/royalty", name="royalty_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $studioId = $request->request->get('studio');
        
        $studio = $this->em->getRepository(Studio::class)->find($studioId);
 
        $royalty = new Royalty();
        $royalty->setStudio($studio);
        $royalty->setPayment($request->request->get('payment'));
 
        $this->em->persist($royalty);
        $this->em->flush();
 
        return $this->json('Created new royalty successfully with id ' . $royalty->getId());
    }

    /**
     * @Route("/royaltymanager/royalty/{studioId}", name="royalty_show", methods={"GET"})
     */
    public function show(string $studioId): Response
    {
        $royalty = $this->em
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
     * @Route("/royaltymanager/royalty/{id}", name="royalty_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $royalty = $this->em->getRepository(Royalty::class)->find($id);
 
        if (!$royalty) {
            return $this->json('No royalty found for id' . $id, 404);
        }
 
        $this->em->remove($royalty);
        $this->em->flush();
 
        return $this->json('Deleted a royalty successfully with id ' . $id);
    }

}
