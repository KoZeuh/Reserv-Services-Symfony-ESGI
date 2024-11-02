<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\ServiceType;
use App\Entity\Company;
use App\Entity\CompanyService;

use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $serviceTypes = ['Coiffure', 'Barbier', 'SPA'];
        $serviceTypesObj = [];

        foreach ($serviceTypes as $serviceTypeName) {
            $serviceType = new ServiceType();
            $serviceType->setName($serviceTypeName);
            $manager->persist($serviceType);
            $serviceTypesObj[] = $serviceType;
        }

        for ($i = 0; $i < 4; $i++) {
            $company = new Company();
            $company->setName($faker->company);
            $company->setCity($faker->city);
            $company->setAdress($faker->address);
            $company->setCode($faker->postcode);
            $manager->persist($company);

            $companyService = new CompanyService();
            $companyService->setServiceType($serviceTypesObj[array_rand($serviceTypesObj)]);
            $companyService->setCompany($company);
            $companyService->setTitle($faker->sentence);
            $companyService->setDescription($faker->paragraph);
            $companyService->setDuration($faker->numberBetween(10, 30));
            $companyService->setPrice($faker->randomFloat(2, 10, 100));

            $manager->persist($companyService);
        }

        $manager->flush();
    }
}