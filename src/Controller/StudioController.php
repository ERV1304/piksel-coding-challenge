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
     * @Route("/royaltymanager/studios", name="app_studio")
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
     * @Route("/royaltymanager/studio", name="studio_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
 
        $studio = new Studio();
        $studio->setId($request->request->get('id'));
        $studio->setName($request->request->get('name'));
 
        $this->em->persist($studio);
        $this->em->flush();
 
        return $this->json('Created new studio successfully with id ' . $studio->getId());
    }

    /**
     * @Route("/royaltymanager/studio/{id}", name="studio_show", methods={"GET"})
     */
    public function show(string $id): Response
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
     * @Route("/royaltymanager/studio/{id}", name="studio_edit", methods={"PUT"})
     */
    public function edit(Request $request, string $id): Response
    {
        $studio = $this->em->getRepository(Studio::class)->find($id);
 
        if (!$studio) {
            return $this->json('No studio found for id' . $id, 404);
        }
 
        $studio->setName($request->request->get('name'));
        $this->em->flush();
 
        $data =  [
            'id' => $studio->getId(),
            'name' => $studio->getName(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/royaltymanager/studio/{id}", name="studio_delete", methods={"DELETE"})
     */
    public function delete(string $id): Response
    {
        $studio = $this->em->getRepository(Studio::class)->find($id);
 
        if (!$studio) {
            return $this->json('No studio found for id' . $id, 404);
        }
 
        $this->em->remove($studio);
        $this->em->flush();
 
        return $this->json('Deleted a studio successfully with id ' . $id);
    }

}
