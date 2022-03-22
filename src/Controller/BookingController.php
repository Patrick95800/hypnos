<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Hotel;
use App\Entity\Suite;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use App\Repository\HotelRepository;
use App\Repository\SuiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    #[Route('/reservation', name: 'booking_new')]
    public function new(
        Request $request,
        HotelRepository $hotelRepository,
        SuiteRepository $suiteRepository,
        BookingRepository $bookingRepository
    ): Response
    {
        $booking = new Booking();

        if ($request->query->has('hotel_id') && null !== $hotelId = $request->query->get('hotel_id')) {
            $hotel = $hotelRepository->find($hotelId);

            if ($hotel instanceof Hotel) {
                $booking->setHotel($hotel);
            }
        }

        if ($request->query->has('suite_id') && null !== $suiteId = $request->query->get('suite_id')) {
            $suite = $suiteRepository->find($suiteId);

            if ($suite instanceof Suite) {
                $booking->setHotel($suite->getHotel());
                $booking->setSuite($suite);
            }
        }

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingRepository->add($booking);

            $request->getSession()->set('current_booking', $booking->getId());

            return $this->redirectToRoute('booking_payment', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    #[Route('/reservation/paiement', name: 'booking_payment')]
    public function payment(Request $request, BookingRepository $bookingRepository): Response
    {
        $booking = $bookingRepository->find($request->getSession()->get('current_booking', 0));

        if (!$booking instanceof Booking) {
            return $this->redirectToRoute('booking_new', [], Response::HTTP_SEE_OTHER);
        }

        $booking->setUser($this->getUser());

        if ($request->getMethod() == 'POST') {
            $booking->setStatus(Booking::STATUS_ACCEPTED);
            $bookingRepository->add($booking);

            return $this->redirectToRoute('booking_confirmation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/payment.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/reservation/confirmation', name: 'booking_confirmation')]
    public function confirmation(Request $request, BookingRepository $bookingRepository): Response
    {
        $booking = $bookingRepository->find($request->getSession()->get('current_booking', 0));

        if (!$booking instanceof Booking) {
            return $this->redirectToRoute('booking_new', [], Response::HTTP_SEE_OTHER);
        }

        $request->getSession()->remove('current_booking');

        return $this->renderForm('booking/confirmation.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/mon-compte/reservations', name: 'bookings')]
    public function history(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/history.html.twig', [
            'bookings' => $bookingRepository->findByUser($this->getUser())
        ]);
    }

    #[Route('/mon-compte/reservations/{id}/annulation', name: 'booking_cancel', methods: ['GET'])]
    public function cancel(Booking $booking, BookingRepository $bookingRepository): Response
    {
        if ($booking->isAllowedToCancel()) {
            $booking->setStatus(Booking::STATUS_CANCELLED);
            $bookingRepository->add($booking);
        }

        return $this->redirectToRoute('bookings', [], Response::HTTP_SEE_OTHER);
    }
}
