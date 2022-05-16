<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Customer;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customers", name="app_customer")
     */
    public function index(): Response
    {
        $customers = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->findAll();
 
        $data = [];
 
        foreach ($customers as $customer) {
           $data[] = [
               'id' => $customer->getId(),
               'description' => $customer->getDescription(),
           ];
        }
 
 
        return $this->json($data);
    }

    /**
     * @Route("/customer", name="customer_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
 
        $customer = new Customer();
        $customer->setDescription($request->request->get('description'));
 
        $entityManager->persist($customer);
        $entityManager->flush();
 
        return $this->json('Created new customer successfully with id ' . $customer->getId());
    }

    /**
     * @Route("/customer/{id}", name="customer_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($id);
 
        if (!$customer) {
 
            return $this->json('No customer found for id' . $id, 404);
        }
 
        $data =  [
            'id' => $customer->getId(),
            'description' => $customer->getDescription(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/customer/{id}", name="customer_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $customer = $entityManager->getRepository(Customer::class)->find($id);
 
        if (!$customer) {
            return $this->json('No customer found for id' . $id, 404);
        }
 
        $customer->setDescription($request->request->get('description'));
        $entityManager->flush();
 
        $data =  [
            'id' => $customer->getId(),
            'description' => $customer->getDescription(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/customer/{id}", name="customer_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $customer = $entityManager->getRepository(Customer::class)->find($id);
 
        if (!$customer) {
            return $this->json('No customer found for id' . $id, 404);
        }
 
        $entityManager->remove($customer);
        $entityManager->flush();
 
        return $this->json('Deleted a customer successfully with id ' . $id);
    }

}
