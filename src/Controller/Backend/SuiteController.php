<?php

namespace App\Controller\Backend;

use App\Entity\Suite;
use App\Form\SuiteType;
use App\Repository\SuiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/suites')]
class SuiteController extends AbstractController
{
    #[Route('/', name: 'backend_suite_index', methods: ['GET'])]
    public function index(SuiteRepository $suiteRepository): Response
    {
        return $this->render('backend/suite/index.html.twig', [
            'suites' => $suiteRepository->findAllForManager($this->getUser()),
        ]);
    }

    #[Route('/nouveau', name: 'backend_suite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SuiteRepository $suiteRepository): Response
    {
        $suite = new Suite();
        $form = $this->createForm(SuiteType::class, $suite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $suiteRepository->add($suite);
            return $this->redirectToRoute('backend_suite_index', [], Response::HTTP_SEE_OTHER);
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
            return $this->redirectToRoute('backend_suite_index', [], Response::HTTP_FORBIDDEN);
        }

        return $this->render('backend/suite/show.html.twig', [
            'suite' => $suite,
        ]);
    }

    #[Route('/{id}/edition', name: 'backend_suite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Suite $suite, SuiteRepository $suiteRepository): Response
    {
        if ($suite->getHotel()->getOwner() != $this->getUser()) {
            return $this->redirectToRoute('backend_suite_index', [], Response::HTTP_FORBIDDEN);
        }

        $form = $this->createForm(SuiteType::class, $suite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $suiteRepository->add($suite);
            return $this->redirectToRoute('backend_suite_index', [], Response::HTTP_SEE_OTHER);
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
            return $this->redirectToRoute('backend_suite_index', [], Response::HTTP_FORBIDDEN);
        }

        if ($this->isCsrfTokenValid('delete'.$suite->getId(), $request->request->get('_token'))) {
            $suiteRepository->remove($suite);
        }

        return $this->redirectToRoute('backend_suite_index', [], Response::HTTP_SEE_OTHER);
    }
}
