<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mon-compte/reservations')]
class BookingController extends AbstractController
{
    #[Route('', name: 'bookings')]
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findByUser($this->getUser())
        ]);
    }

    #[Route('/{id}', name: 'booking_cancel', methods: ['GET'])]
    public function delete(Booking $booking, BookingRepository $bookingRepository): Response
    {
        if ($booking->isAllowedToCancel()) {
            $booking->setStatus(Booking::STATUS_CANCELLED);
            $bookingRepository->add($booking);
        }

        return $this->redirectToRoute('bookings', [], Response::HTTP_SEE_OTHER);
    }
}
