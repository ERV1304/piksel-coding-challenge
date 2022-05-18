<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Customer;

class CustomerController extends AbstractController
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/royaltymanager/customers", name="app_customer")
     */
    public function index(): Response
    {
        $customers = $this->em
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
     * @Route("/royaltymanager/customer", name="customer_new", methods={"POST"})
     */
    public function new(Request $request): Response
    { 
        $customer = new Customer();
        $customer->setDescription($request->request->get('description'));
 
        $this->em->persist($customer);
        $this->em->flush();
 
        return $this->json('Created new customer successfully with id ' . $customer->getId());
    }

    /**
     * @Route("/royaltymanager/customer/{id}", name="customer_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $customer = $this->em
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
     * @Route("/royaltymanager/customer/{id}", name="customer_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id): Response
    {
        $customer = $this->em->getRepository(Customer::class)->find($id);
 
        if (!$customer) {
            return $this->json('No customer found for id' . $id, 404);
        }
 
        $customer->setDescription($request->request->get('description'));
        $this->em->flush();
 
        $data =  [
            'id' => $customer->getId(),
            'description' => $customer->getDescription(),
        ];
         
        return $this->json($data);
    }

    /**
     * @Route("/royaltymanager/customer/{id}", name="customer_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $customer = $this->em->getRepository(Customer::class)->find($id);
 
        if (!$customer) {
            return $this->json('No customer found for id' . $id, 404);
        }
 
        $this->em->remove($customer);
        $this->em->flush();
 
        return $this->json('Deleted a customer successfully with id ' . $id);
    }

}
