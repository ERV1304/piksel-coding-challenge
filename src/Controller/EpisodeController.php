<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Episode;
use App\Entity\Rightsowner;

class EpisodeController extends AbstractController
{
    /**
     * @Route("/episodes", name="app_episode")
     */
    public function index(): Response
    {
        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findAll();
 
        $data = [];
 
        foreach ($episodes as $episode) {
           $data[] = [
               'id' => $episode->getId(),
               'name' => $episode->getName(),
           ];
        }
 
 
        return $this->json($data);
    }

    /**
     * @Route("/episode", name="episode_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
 
        $episode = new Episode();
        $episode->setName($request->request->get('name'));
 
        $entityManager->persist($episode);
        $entityManager->flush();
 
        return $this->json('Created new episode successfully with id ' . $episode->getId());
    }

    /**
     * @Route("/episode/{id}", name="episode_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $episode = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->find($id);
 
        if (!$episode) {
 
            return $this->json('No episode found for id' . $id, 404);
        }
 
        $data =  [
            'id' => $episode->getId(),
            'name' => $episode->getName(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/episodesOwned/{studioId}", name="episodeOwned_show", methods={"GET"})
     */
    public function showOwned(int $studioId): Response
    {
        $episodesOwned = $this->getDoctrine()
            ->getRepository(Rightsowner::class)
            ->findEpisodesOwnedByStudio($studioId);
 
        if (!$episodesOwned || !is_array($episodesOwned) || count($episodesOwned) < 1) {
 
            return $this->json('No episodes owned for studio id ' . $studioId, 404);
        }
 
        foreach ($episodesOwned as $episodeOwned) {
            $episode = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->find($episodeOwned->getId());

            $data[] = [
                'id' => $episode->getId(),
                'name' => $episode->getName(),
            ];
         }
         
        return $this->json($data);
    }

    /**
     * @Route("/episode/{id}", name="episode_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $episode = $entityManager->getRepository(Episode::class)->find($id);
 
        if (!$episode) {
            return $this->json('No episode found for id' . $id, 404);
        }
 
        $episode->setName($request->request->get('name'));
        $entityManager->flush();
 
        $data =  [
            'id' => $episode->getId(),
            'name' => $episode->getName(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/episode/{id}", name="episode_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $episode = $entityManager->getRepository(Episode::class)->find($id);
 
        if (!$episode) {
            return $this->json('No episode found for id' . $id, 404);
        }
 
        $entityManager->remove($episode);
        $entityManager->flush();
 
        return $this->json('Deleted a episode successfully with id ' . $id);
    }

}
