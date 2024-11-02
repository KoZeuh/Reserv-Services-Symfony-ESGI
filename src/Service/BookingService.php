<?php

namespace App\Service;

use App\Entity\CompanyService;
use App\Repository\BookingRepository;

class BookingService
{
    public function __construct(private BookingRepository $bookingRepository)
    {}

    public function doesBookingExist(CompanyService $companyService, \DateTime $date): bool
    {
        return $this->bookingRepository->findOneBy(['companyService' => $companyService, 'date' => $date]) !== null;
    }

    public function getAvailableDatesAndTimesByServiceCompany(CompanyService $companyService): array
    {
        $existingBookings = $this->bookingRepository->findBy(['companyService' => $companyService]);

        $availableSlots = [];
        $bookedTimes = [];


        $startDate = new \DateTime('now');
        $endDate = (clone $startDate)->modify('+7 days');
    
        foreach ($existingBookings as $booking) {
            $bookedTimes[] = $booking->getDate()->format('Y-m-d H:i');
        }
    
        while ($startDate <= $endDate) {
            $dateString = $startDate->format('Y-m-d');
            $availableSlots[$dateString] = [];
    
            $startTime = new \DateTime($dateString . ' 09:00');
            $endTime = new \DateTime($dateString . ' 17:00');
            $interval = new \DateInterval('PT30M');
    
            while ($startTime < $endTime) {
                $timeSlot = $startTime->format('Y-m-d H:i');

                if (!in_array($timeSlot, $bookedTimes)) {
                    $availableSlots[$dateString][$timeSlot] = $timeSlot;
                }
                
                $startTime->add($interval);
            }
    
            $startDate->modify('+1 day');
        }
    
        return $availableSlots;
    }
    
}