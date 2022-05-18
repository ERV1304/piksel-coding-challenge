<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Episode;
use App\Entity\Rightsowner;

class EpisodeController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/royaltymanager/episodes", name="app_episode")
     */
    public function index(): Response
    {
        $episodes = $this->em
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
     * @Route("/royaltymanager/episode", name="episode_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $episode = new Episode();
        $episode->setId($request->request->get('id'));
        $episode->setName($request->request->get('name'));
 
        $this->em->persist($episode);
        $this->em->flush();
 
        return $this->json('Created new episode successfully with id ' . $episode->getId());
    }

    /**
     * @Route("/royaltymanager/episode/{id}", name="episode_show", methods={"GET"})
     */
    public function show(string $id): Response
    {
        $episode = $this->em
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
     * @Route("/royaltymanager/episodesOwned/{studioId}", name="episodeOwned_show", methods={"GET"})
     */
    public function showOwned(string $studioId): Response
    {
        $episodesOwned = $this->em
            ->getRepository(Rightsowner::class)
            ->findEpisodesOwnedByStudio($studioId);
 
        if (!$episodesOwned || !is_array($episodesOwned) || count($episodesOwned) < 1) {
 
            return $this->json('No episodes owned for studio id ' . $studioId, 404);
        }
 
        foreach ($episodesOwned as $episodeOwned) {
            $episode = $this->em
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
     * @Route("/royaltymanager/episode/{id}", name="episode_edit", methods={"PUT"})
     */
    public function edit(Request $request, string $id): Response
    {
        $episode = $this->em->getRepository(Episode::class)->find($id);
 
        if (!$episode) {
            return $this->json('No episode found for id' . $id, 404);
        }
 
        $episode->setName($request->request->get('name'));
        $this->em->flush();
 
        $data =  [
            'id' => $episode->getId(),
            'name' => $episode->getName(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/royaltymanager/episode/{id}", name="episode_delete", methods={"DELETE"})
     */
    public function delete(string $id): Response
    {
        $episode = $this->em->getRepository(Episode::class)->find($id);
 
        if (!$episode) {
            return $this->json('No episode found for id' . $id, 404);
        }
 
        $this->em->remove($episode);
        $this->em->flush();
 
        return $this->json('Deleted a episode successfully with id ' . $id);
    }

}
