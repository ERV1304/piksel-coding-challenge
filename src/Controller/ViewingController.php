<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Viewing;
use App\Entity\Episode;
use App\Entity\Customer;

class ViewingController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/viewings", name="app_viewing")
     */
    public function index(): Response
    {
        $viewings = $this->em
            ->getRepository(Viewing::class)
            ->findAll();
 
        $data = [];
 
        foreach ($viewings as $viewing) {
           $data[] = [
               'id' => $viewing->getId(),
               'customer' => $viewing->getCustomer()->getDescription(),
               'episode' => $viewing->getEpisode()->getName(),
               'date' => $viewing->getDate(),
           ];
        }
 
 
        return $this->json($data);
    }

    /**
     * @Route("/reset", name="viewing_reset", methods={"POST"})
     */
    public function reset(): Response
    {
        $viewings = $this->em
            ->getRepository(Viewing::class)
            ->findAll();
 
        foreach ($viewings as $viewing) {
           $this->delete($viewing->getId());
        }
 
        return $this->json(null,202);
    }

    /**
     * @Route("/viewing", name="viewing_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->em->getManager();

        $episodeId = $request->request->get('episode');
        $episode = $entityManager->getRepository(Episode::class)->find($episodeId);
 
        if (!$episode) {
            return $this->json('No episode found for id' . $episodeId, 404);
        }

        $customerId = $request->request->get('customer');
        $customer = $entityManager->getRepository(Customer::class)->find($customerId);
 
        $viewing = new Viewing();
        $viewing->setEpisode($episode);
        $viewing->setCustomer($customer);
        $viewing->setDate(new \DateTime());
 
        $entityManager->persist($viewing);
        $entityManager->flush();
 
        return $this->json('Created new viewing successfully with id ' . $viewing->getId(), 202);
    }

    /**
     * @Route("/viewings/{episodeId}", name="viewings_show", methods={"GET"})
     */
    public function showViewings(int $episodeId): Response
    {
        $viewings = $this->em
            ->getRepository(Viewing::class)
            ->findViewingsByEpisode($episodeId);

        if (!$viewings || !is_array($viewings) || count($viewings) < 1) {
 
            return $this->json('No viewings for episode id ' . $episodeId, 404);
        }

        foreach ($viewings as $viewing) {
            $episode = $this->em
            ->getRepository(Episode::class)
            ->find($viewing->getEpisode());

            $data[] = [
                'id' => $episode->getId(),
                'name' => $episode->getName(),
            ];
         }
         
        return $this->json($data);
    }

    /**
     * @Route("/viewing/{id}", name="viewing", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->em->getManager();
        $viewing = $entityManager->getRepository(Viewing::class)->find($id);
 
        if (!$viewing) {
            return $this->json('No viewing found for id' . $id, 404);
        }
 
        $entityManager->remove($viewing);
        $entityManager->flush();
 
        return $this->json('Deleted a viewing successfully with id ' . $id);
    }
}
