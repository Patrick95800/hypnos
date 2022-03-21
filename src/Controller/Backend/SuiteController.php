<?php

namespace App\Controller\Backend;

use App\Entity\Image;
use App\Entity\Suite;
use App\Form\SuiteType;
use App\Repository\ImageRepository;
use App\Repository\SuiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/suites')]
class SuiteController extends AbstractController
{
    #[Route('/', name: 'backend_suites', methods: ['GET'])]
    public function index(SuiteRepository $suiteRepository): Response
    {
        return $this->render('backend/suite/index.html.twig', [
            'suites' => $suiteRepository->findAllForManager($this->getUser()),
        ]);
    }

    #[Route('/nouveau', name: 'backend_suite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SuiteRepository $suiteRepository, ImageRepository $imageRepository): Response
    {
        $suite = new Suite();
        $form = $this->createForm(SuiteType::class, $suite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle featured image
            $featuredImage = $form->get('featuredImage')->getData();
            if (null !== $featuredImage) {
                $fileName = md5(uniqid()) . '.' . $featuredImage->guessExtension();
                $featuredImage->move($this->getParameter('images_directory'), $fileName);

                $image = new Image();
                $image->setName($fileName);
                $imageRepository->add($image);

                $suite->setFeaturedImage($image);
            }

            // Handle images
            $uploadedImages = $form->get('images')->getData();
            if (null !== $uploadedImages) {
                foreach ($uploadedImages as $uploadedImage) {
                    $fileName = md5(uniqid()) . '.' . $uploadedImage->guessExtension();
                    $uploadedImage->move($this->getParameter('images_directory'), $fileName);

                    $image = new Image();
                    $image->setName($fileName);
                    $imageRepository->add($image);

                    $suite->addImage($image);
                }
            }

            $suiteRepository->add($suite);
            return $this->redirectToRoute('backend_suites', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/suite/new.html.twig', [
            'suite' => $suite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'backend_suite_show', methods: ['GET'])]
    public function show(Suite $suite): Response
    {
        if ($suite->getHotel()->getOwner() != $this->getUser()) {
            return $this->redirectToRoute('backend_suites', [], Response::HTTP_FORBIDDEN);
        }

        return $this->render('backend/suite/show.html.twig', [
            'suite' => $suite,
        ]);
    }

    #[Route('/{id}/edition', name: 'backend_suite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Suite $suite, SuiteRepository $suiteRepository, ImageRepository $imageRepository): Response
    {
        if ($suite->getHotel()->getOwner() != $this->getUser()) {
            return $this->redirectToRoute('backend_suites', [], Response::HTTP_FORBIDDEN);
        }

        $form = $this->createForm(SuiteType::class, $suite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle featured image
            $featuredImage = $form->get('featuredImage')->getData();
            if (null !== $featuredImage) {
                $fileName = md5(uniqid()) . '.' . $featuredImage->guessExtension();
                $featuredImage->move($this->getParameter('images_directory'), $fileName);

                $image = new Image();
                $image->setName($fileName);
                $imageRepository->add($image);

                $suite->setFeaturedImage($image);
            }

            // Handle images
            $uploadedImages = $form->get('images')->getData();
            if (null !== $uploadedImages) {
                foreach ($uploadedImages as $uploadedImage) {
                    $fileName = md5(uniqid()) . '.' . $uploadedImage->guessExtension();
                    $uploadedImage->move($this->getParameter('images_directory'), $fileName);

                    $image = new Image();
                    $image->setName($fileName);
                    $imageRepository->add($image);

                    $suite->addImage($image);
                }
            }

            $suiteRepository->add($suite);
            return $this->redirectToRoute('backend_suites', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/suite/edit.html.twig', [
            'suite' => $suite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'backend_suite_delete', methods: ['POST'])]
    public function delete(Request $request, Suite $suite, SuiteRepository $suiteRepository): Response
    {
        if ($suite->getHotel()->getOwner() != $this->getUser()) {
            return $this->redirectToRoute('backend_suites', [], Response::HTTP_FORBIDDEN);
        }

        if ($this->isCsrfTokenValid('delete'.$suite->getId(), $request->request->get('_token'))) {
            $suiteRepository->remove($suite);
        }

        return $this->redirectToRoute('backend_suites', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/image/{imageId}', name: 'backend_suite_delete_image', methods: ['DELETE'])]
    public function deleteImage(
        Request $request,
        SuiteRepository $suiteRepository,
        ImageRepository $imageRepository,
        int $id,
        int $imageId
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $suite = $suiteRepository->find($id);
        $image = $imageRepository->find($imageId);

        if ($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])) {
            unlink($this->getParameter('images_directory').'/'.$image->getName());

            if (null !== $suite->getFeaturedImage() && $suite->getFeaturedImage()->getId() === $image->getId()) {
                $suite->setFeaturedImage(null);
            } else {
                $suite->removeImage($image);
            }

            $imageRepository->remove($image);

            return new JsonResponse(['success' => true]);
        } else {
            return new JsonResponse(['success' => false], 400);
        }
    }
}
