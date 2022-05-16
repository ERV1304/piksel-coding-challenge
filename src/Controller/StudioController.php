<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Studio;

class StudioController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/studios", name="app_studio")
     */
    public function index(): Response
    {
        $studios = $this->em
            ->getRepository(Studio::class)
            ->findAll();
 
        $data = [];
 
        foreach ($studios as $studio) {
           $data[] = [
               'id' => $studio->getId(),
               'name' => $studio->getName(),
           ];
        }
 
 
        return $this->json($data);
    }

    /**
     * @Route("/studio", name="studio_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->em->getManager();
 
        $studio = new Studio();
        $studio->setName($request->request->get('name'));
 
        $entityManager->persist($studio);
        $entityManager->flush();
 
        return $this->json('Created new studio successfully with id ' . $studio->getId());
    }

    /**
     * @Route("/studio/{id}", name="studio_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $studio = $this->em
            ->getRepository(Studio::class)
            ->find($id);
 
        if (!$studio) {
 
            return $this->json('No studio found for id' . $id, 404);
        }
 
        $data =  [
            'id' => $studio->getId(),
            'name' => $studio->getName(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/studio/{id}", name="studio_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id): Response
    {
        $entityManager = $this->em->getManager();
        $studio = $entityManager->getRepository(Studio::class)->find($id);
 
        if (!$studio) {
            return $this->json('No studio found for id' . $id, 404);
        }
 
        $studio->setName($request->request->get('name'));
        $entityManager->flush();
 
        $data =  [
            'id' => $studio->getId(),
            'name' => $studio->getName(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/studio/{id}", name="studio_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->em->getManager();
        $studio = $entityManager->getRepository(Studio::class)->find($id);
 
        if (!$studio) {
            return $this->json('No studio found for id' . $id, 404);
        }
 
        $entityManager->remove($studio);
        $entityManager->flush();
 
        return $this->json('Deleted a studio successfully with id ' . $id);
    }

}
