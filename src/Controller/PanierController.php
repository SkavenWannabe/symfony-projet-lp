<?php

namespace App\Controller;
use App\Repository\UsagerRepository;
use App\Service\PanierService;
use App\Service\UsagerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController {
    public function index(PanierService $panierService) {
        $panier = $panierService->getContenu();
        $total = $panierService->getTotal();
        return $this->render( 'Panier/index.html.twig',
                                    ['panier' => $panier,
                                    'total' => $total]);
    }

    public function ajouter(PanierService $panierService, int $idProduit, int $quantite){
        $panierService->ajouterArticle($idProduit, $quantite);
        return $this->redirectToRoute('panier');
    }

    public function retirer(PanierService $panierService, int $idProduit, int $quantite){
        $panierService->enleverArticle($idProduit, $quantite);
        return $this->redirectToRoute('panier');
    }

    public function supprimer(PanierService $panierService, int $idProduit){
        $panierService->supprimerArticle($idProduit);
        return $this->redirectToRoute('panier');
    }

    public function validation(PanierService $panierService, EntityManagerInterface $entityManager) {

        $commande = $panierService->panierToCommande($this->getUser(), $entityManager);

        return $this->render('Panier/commande.html.twig', ['commande' => $commande]);
    }
}