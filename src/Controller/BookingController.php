<?php

namespace App\Controller;

use App\Entity\Booking;

use App\Repository\CompanyServiceRepository;

use App\Form\BookingType;
use App\Service\BookingService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/booking')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class BookingController extends AbstractController
{
    #[Route('/new/{companyServiceId}', name: 'booking_new')]
    public function show(
        int $companyServiceId,
        Request $request,
        BookingService $bookingService,
        CompanyServiceRepository $companyServiceRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $companyService = $companyServiceRepository->find($companyServiceId);

        if (!$companyService) {
            throw $this->createNotFoundException("Le service de l'entreprise n'existe pas.");
        }

        $booking = new Booking();

        $booking->setCompanyService($companyService);
        $booking->setUser($this->getUser());

        $availableSlots = $bookingService->getAvailableDatesAndTimesByServiceCompany($companyService);

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $selectedDate = $request->request->get('selectedDate');

            if (isset($selectedDate)) {
                if ($bookingService->doesBookingExist($companyService, new \DateTime($selectedDate))) {
                    $this->addFlash('error', 'Ce créneau est déjà réservé');

                    return $this->redirectToRoute('booking_new', ['companyServiceId' => $companyServiceId]);
                }

                $booking->setDate(new \DateTime($selectedDate));

                $entityManager->persist($booking);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    sprintf(
                        'Votre réservation chez %s le %s a bien été prise en compte !',
                        $companyService->getCompany()->getName(),
                        $booking->getDate()->format('d/m/Y H:i')
                    )
                );

                return $this->redirectToRoute('home');
            }
        }

        return $this->render('booking/new.html.twig', [
            'form' => $form,
            'companyService' => $companyService,
            'availableSlots' => $availableSlots
        ]);
    }    

    #[Route('/my/list', name: 'booking_my_list')]
    public function list(): Response
    {
        return $this->render('booking/list.html.twig');
    }
}
