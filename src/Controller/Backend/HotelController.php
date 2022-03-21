<?php

namespace App\Controller\Backend;

use App\Entity\Hotel;
use App\Form\HotelType;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/etablissements')]
class HotelController extends AbstractController
{
    #[Route('/', name: 'backend_hotels', methods: ['GET'])]
    public function index(HotelRepository $hotelRepository): Response
    {
        return $this->render('backend/hotel/index.html.twig', [
            'hotels' => $hotelRepository->findAll(),
        ]);
    }

    #[Route('/nouveau', name: 'backend_hotel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HotelRepository $hotelRepository): Response
    {
        $hotel = new Hotel();
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hotelRepository->add($hotel);
            return $this->redirectToRoute('backend_hotels', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/hotel/new.html.twig', [
            'hotel' => $hotel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'backend_hotel_show', methods: ['GET'])]
    public function show(Hotel $hotel): Response
    {
        return $this->render('backend/hotel/show.html.twig', [
            'hotel' => $hotel,
        ]);
    }

    #[Route('/{id}/edition', name: 'backend_hotel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hotel $hotel, HotelRepository $hotelRepository): Response
    {
        $form = $this->createForm(HotelType::class, $hotel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hotelRepository->add($hotel);
            return $this->redirectToRoute('backend_hotels', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/hotel/edit.html.twig', [
            'hotel' => $hotel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'backend_hotel_delete', methods: ['POST'])]
    public function delete(Request $request, Hotel $hotel, HotelRepository $hotelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hotel->getId(), $request->request->get('_token'))) {
            $hotelRepository->remove($hotel);
        }

        return $this->redirectToRoute('backend_hotels', [], Response::HTTP_SEE_OTHER);
    }
}
