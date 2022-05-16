<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Rightsowner;
use App\Entity\Episode;
use App\Entity\Studio;

class RightsownerController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/rightsowners", name="app_rightsowner")
     */
    public function index(): Response
    {
        $rightsowners = $this->em
            ->getRepository(Rightsowner::class)
            ->findAll();
 
        $data = [];
 
        foreach ($rightsowners as $rightsowner) {
           $data[] = [
               'id' => $rightsowner->getId(),
               'episode' => $rightsowner->getEpisode()->getName(),
               'studio' => $rightsowner->getStudio()->getName(),
           ];
        }
 
 
        return $this->json($data);
    }

    /**
     * @Route("/rightsowner", name="rightsowner_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->em->getManager();

        $episodeId = $request->request->get('episode');
        $episode = $entityManager->getRepository(Episode::class)->find($episodeId);
 
        if (!$episode) {
            return $this->json('No episode found for id' . $episodeId, 404);
        }

        $studioId = $request->request->get('studio');
        
        $studio = $entityManager->getRepository(Studio::class)->find($studioId);

        if (!$studio) {
            return $this->json('No studio found for id' . $studioId, 404);
        }
 
        $rightsowner = new Rightsowner();
        $rightsowner->setEpisode($episode);
        $rightsowner->setStudio($studio);
 
        $entityManager->persist($rightsowner);
        $entityManager->flush();
 
        return $this->json('Created new rightsowner successfully with id ' . $rightsowner->getId());
    }

    /**
     * @Route("/rightsowner/{id}", name="rightsowner_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $rightsowner = $this->em
            ->getRepository(Rightsowner::class)
            ->find($id);
 
        if (!$rightsowner) {
 
            return $this->json('No rightsowner found for id' . $id, 404);
        }
 
        $data =  [
            'id' => $rightsowner->getId(),
            'episode' => $rightsowner->getEpisode()->getName(),
            'studio' => $rightsowner->getStudio()->getName(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/rightsowner/{id}", name="rightsowner_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id): Response
    {
        $entityManager = $this->em->getManager();
        $rightsowner = $entityManager->getRepository(Rightsowner::class)->find($id);
 
        if (!$rightsowner) {
            return $this->json('No rightsowner found for id' . $id, 404);
        }

        $entityManager = $this->em->getManager();

        $episodeId = $request->request->get('episode');
        $episode = $entityManager->getRepository(Episode::class)->find($episodeId);
 
        if (!$episode) {
            return $this->json('No episode found for id' . $episodeId, 404);
        }

        $studioId = $request->request->get('studio');
        
        $studio = $entityManager->getRepository(Studio::class)->find($studioId);

        if (!$studio) {
            return $this->json('No studio found for id' . $studioId, 404);
        }
 
        $rightsowner->setEpisode($episodeId);
        $rightsowner->setStudio($studioId);
        $entityManager->flush();
 
        $data =  [
            'id' => $rightsowner->getId(),
            'episode' => $rightsowner->getEpisode()->getName(),
            'studio' => $rightsowner->getStudio()->getName(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/rightsowner/{id}", name="rightsowner_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->em->getManager();
        $rightsowner = $entityManager->getRepository(Rightsowner::class)->find($id);
 
        if (!$rightsowner) {
            return $this->json('No rightsowner found for id' . $id, 404);
        }
 
        $entityManager->remove($rightsowner);
        $entityManager->flush();
 
        return $this->json('Deleted a rightsowner successfully with id ' . $id);
    }

}
