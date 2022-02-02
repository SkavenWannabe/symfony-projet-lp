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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsagerController extends AbstractController
{
    public function index(UsagerRepository $usagerRepository, UsagerService $usagerService): Response
    {

        return $this->render('usager/index.html.twig', [
            'usager' => $this->getUser(),
        ]);
    }

    public function new(Request $request, EntityManagerInterface $entityManager, UsagerService $usagerService, UserPasswordHasherInterface $passwordHasher): Response
    {
        $usager = new Usager();
        $form = $this->createForm(UsagerType::class, $usager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encoder le mot de passe qui est en clair pour l’instant
            $hashedPassword = $passwordHasher->hashPassword($usager, $usager->getPassword());
            $usager->setPassword($hashedPassword);
            // Définir le rôle de l’usager qui va être créé
            $usager->setRoles(["ROLE_CLIENT"]);

            $entityManager->persist($usager);
            $entityManager->flush();

            $usagerService->setIdSession($usager->getId());
            return $this->redirectToRoute('usager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usager/new.html.twig', [
            'usager' => $usager,
            'form' => $form,
        ]);
    }
}
