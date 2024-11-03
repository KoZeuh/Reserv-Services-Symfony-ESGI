<?php

namespace App\Controller;

use App\Repository\CompanyRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        CompanyRepository $companyRepository
    ): Response {
        $companies = $companyRepository->findAll();

        return $this->render('home/index.html.twig', [
            'companies' => $companies,
        ]);
    }
}
