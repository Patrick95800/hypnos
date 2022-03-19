<?php

namespace App\Controller;

use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(HotelRepository $hotelRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'hotels' => $hotelRepository->findAll()
        ]);
    }
}
