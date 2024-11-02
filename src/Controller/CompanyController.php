<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Repository\CompanyServiceRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/company')]
class CompanyController extends AbstractController
{
    public function __construct(private CompanyRepository $companyRepository, private CompanyServiceRepository $companyServiceRepository)
    {}

    #[Route('/show/{id}', name: 'company_show')]
    public function show(int $id): Response
    {
        $company = $this->companyRepository->find($id);
        $companyServices = $this->companyServiceRepository->findBy(['company' => $company]);

        return $this->render('company/show.html.twig', [
            'company' => $company,
            'companyServices' => $companyServices,
        ]);
    }
}