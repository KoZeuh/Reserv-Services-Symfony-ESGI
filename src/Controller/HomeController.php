<?php

namespace App\Controller;

use App\Repository\CompanyRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private CompanyRepository $companyRepository)
    {}

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $companies = $this->companyRepository->findAll();

        return $this->render('home/index.html.twig', [
            'companies' => $companies,
        ]);
    }
}
