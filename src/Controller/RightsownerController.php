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
     * @Route("/royaltymanager/rightsowners", name="app_rightsowner")
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
     * @Route("/royaltymanager/rightsowner", name="rightsowner_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {

        $episodeId = $request->request->get('episode');
        $episode = $this->em->getRepository(Episode::class)->find($episodeId);
 
        if (!$episode) {
            return $this->json('No episode found for id' . $episodeId, 404);
        }

        $studioId = $request->request->get('studio');
        
        $studio = $this->em->getRepository(Studio::class)->find($studioId);

        if (!$studio) {
            return $this->json('No studio found for id' . $studioId, 404);
        }
 
        $rightsowner = new Rightsowner();
        $rightsowner->setEpisode($episode);
        $rightsowner->setStudio($studio);
 
        $this->em->persist($rightsowner);
        $this->em->flush();
 
        return $this->json('Created new rightsowner successfully with id ' . $rightsowner->getId());
    }

    /**
     * @Route("/royaltymanager/rightsowner/{id}", name="rightsowner_show", methods={"GET"})
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
     * @Route("/royaltymanager/rightsowner/{id}", name="rightsowner_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id): Response
    {
        $rightsowner = $this->em->getRepository(Rightsowner::class)->find($id);
 
        if (!$rightsowner) {
            return $this->json('No rightsowner found for id' . $id, 404);
        }

        $this->em = $this->em->getManager();

        $episodeId = $request->request->get('episode');
        $episode = $this->em->getRepository(Episode::class)->find($episodeId);
 
        if (!$episode) {
            return $this->json('No episode found for id' . $episodeId, 404);
        }

        $studioId = $request->request->get('studio');
        
        $studio = $this->em->getRepository(Studio::class)->find($studioId);

        if (!$studio) {
            return $this->json('No studio found for id' . $studioId, 404);
        }
 
        $rightsowner->setEpisode($episodeId);
        $rightsowner->setStudio($studioId);
        $this->em->flush();
 
        $data =  [
            'id' => $rightsowner->getId(),
            'episode' => $rightsowner->getEpisode()->getName(),
            'studio' => $rightsowner->getStudio()->getName(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/royaltymanager/rightsowner/{id}", name="rightsowner_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $rightsowner = $this->em->getRepository(Rightsowner::class)->find($id);
 
        if (!$rightsowner) {
            return $this->json('No rightsowner found for id' . $id, 404);
        }
 
        $this->em->remove($rightsowner);
        $this->em->flush();
 
        return $this->json('Deleted a rightsowner successfully with id ' . $id);
    }

}
