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
    #[Route('/show/{id}', name: 'company_show')]
    public function show(
        int $id,
        CompanyRepository $companyRepository,
        CompanyServiceRepository $companyServiceRepository
    ): Response {
        $company = $companyRepository->find($id);

        if (!$company) {
            throw $this->createNotFoundException("L'entreprise n'existe pas.");
        }

        $companyServices = $companyServiceRepository->findBy(['company' => $company]);

        if (!$companyServices) {
            throw $this->createNotFoundException("Aucun service n'est disponible pour cette entreprise.");
        }

        return $this->render('company/show.html.twig', [
            'company' => $company,
            'companyServices' => $companyServices,
        ]);
    }
}