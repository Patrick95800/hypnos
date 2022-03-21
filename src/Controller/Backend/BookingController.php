<?php

namespace App\Controller\Backend;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/reservations')]
class BookingController extends AbstractController
{
    #[Route('/', name: 'backend_bookings', methods: ['GET'])]
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('backend/booking/index.html.twig', [
            'bookings' => $bookingRepository->findAllForManager($this->getUser()),
        ]);
    }

    #[Route('/nouveau', name: 'backend_booking_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookingRepository $bookingRepository): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingRepository->add($booking);
            return $this->redirectToRoute('backend_bookings', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'backend_booking_show', methods: ['GET'])]
    public function show(Booking $booking): Response
    {
        return $this->render('backend/booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    #[Route('/{id}/edition', name: 'backend_booking_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Booking $booking, BookingRepository $bookingRepository): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingRepository->add($booking);
            return $this->redirectToRoute('backend_bookings', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'backend_booking_delete', methods: ['POST'])]
    public function delete(Request $request, Booking $booking, BookingRepository $bookingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $bookingRepository->remove($booking);
        }

        return $this->redirectToRoute('backend_bookings', [], Response::HTTP_SEE_OTHER);
    }
}
