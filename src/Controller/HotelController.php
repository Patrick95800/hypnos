<?php

namespace App\Controller;

use App\Entity\Hotel;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    #[Route('/hotel/{slug}', name: 'hotel')]
    public function index(EntityManagerInterface $em, string $slug): Response
    {
        $hotel = $em->getRepository(Hotel::class)->findOneBySlug($slug);

        return $this->render('hotel/index.html.twig', [
            'hotel' => $hotel,
        ]);
    }
}
