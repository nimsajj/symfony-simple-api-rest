<?php

namespace App\Controller;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends AbstractController
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Route("/api/customers", name="add_customer", methods={"POST"})
     */
    public function addCustomer(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['firstName']) || empty($data['lastName']) || empty($data['phoneNumber'])) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $newCustomer = new Customer();

        $newCustomer
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setPhoneNumber($data['phoneNumber']);

        $customerCreated = $this->customerRepository->saveCustomer($newCustomer);

        return $this->json($customerCreated, Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/customers/{id}", name="get_one_customer", methods={"GET"})
     */
    public function getCustomer(Customer $customer): Response
    {
        return $this->json($customer, Response::HTTP_OK);
    }

    /**
     * @Route("/api/customers", name="get_customer", methods={"GET"})
     */
    public function getAllCustomer()
    {
        $customers = $this->customerRepository->findAll();


        return $this->json($customers, Response::HTTP_OK);
    }

    /**
     * @Route("/api/customers/{id}", name="update_customer", methods={"PUT"})
     */
    public function updateCustomer(Customer $customer, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        empty($data['firstName']) ? true : $customer->setFirstName($data['firstName']);
        empty($data['lastName']) ? true : $customer->setLastName($data['lastName']);
        empty($data['phoneNumber']) ? true : $customer->setPhoneNumber($data['phoneNumber']);

        $updatedCustomer = $this->customerRepository->saveCustomer($customer);

        return $this->json($updatedCustomer, Response::HTTP_OK);
    }

    /**
     * @Route("/api/customers/{id}", name="delete_customer", methods={"DELETE"})
     */
    public function deleteCustomer(Customer $customer): Response
    {
        $this->customerRepository->removeCustomer($customer);

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
