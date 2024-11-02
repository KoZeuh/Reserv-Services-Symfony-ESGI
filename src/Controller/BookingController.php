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

#[Route('/booking')]
class BookingController extends AbstractController
{
    public function __construct(private CompanyServiceRepository $companyServiceRepository, private EntityManagerInterface $entityManager)
    {}

    #[Route('/new/{companyServiceId}', name: 'booking_new')]
    public function show(int $companyServiceId, Request $request, BookingService $bookingService): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $companyService = $this->companyServiceRepository->find($companyServiceId);

        $booking = new Booking();

        $booking->setCompanyService($companyService);
        $booking->setUser($user);

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

                $this->entityManager->persist($booking);
                $this->entityManager->flush();

                $this->addFlash('success', 'Votre réservation chez ' . $companyService->getCompany()->getName() . ' le ' . $booking->getDate()->format('d/m/Y H:i') . ' a bien été prise en compte !');

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
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('login');
        }

        return $this->render('booking/list.html.twig');
    }
}
