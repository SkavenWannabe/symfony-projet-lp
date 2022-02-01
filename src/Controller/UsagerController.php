<?php

namespace App\Controller;

use App\Entity\Usager;
use App\Form\UsagerType;
use App\Repository\UsagerRepository;
use App\Service\UsagerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsagerController extends AbstractController
{
    public function index(UsagerRepository $usagerRepository, int $id, UsagerService $usagerService): Response
    {
        if($id == -1) {
            $id = $usagerService->getIdSession();
        }

        return $this->render('usager/index.html.twig', [
            'usager' => $usagerRepository->find($id),
        ]);
    }

    public function new(Request $request, EntityManagerInterface $entityManager, UsagerService $usagerService): Response
    {
        $usager = new Usager();
        $form = $this->createForm(UsagerType::class, $usager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($usager);
            $entityManager->flush();

            $usagerService->setIdSession($usager->getId());
            return $this->redirectToRoute('usager_index', ['id' => $usager->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usager/new.html.twig', [
            'usager' => $usager,
            'form' => $form,
        ]);
    }
}
