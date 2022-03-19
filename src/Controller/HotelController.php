<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    #[Route('/hotel/{slug}', name: 'hotel')]
    public function index(HotelRepository $hotelRepository, string $slug): Response
    {
        return $this->render('hotel/index.html.twig', [
            'hotel' => $hotelRepository->findOneBySlug($slug),
        ]);
    }
}
