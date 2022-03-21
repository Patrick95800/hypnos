<?php

namespace App\Controller\Backend;

use App\Repository\HotelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class HomeController extends AbstractController
{
    #[Route('', name: 'backend_home')]
    public function index(): Response
    {
        return $this->render('backend/home/index.html.twig');
    }
}
