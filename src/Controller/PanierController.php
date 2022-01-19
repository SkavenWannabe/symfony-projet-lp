<?php

namespace App\Controller;
use App\Service\PanierService;
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
        $panierService->ajouterProduit($idProduit, $quantite);
        return $this->redirectToRoute('panier');
    }

    public function retirer(PanierService $panierService, int $idProduit, int $quantite){
        $panierService->enleverProduit($idProduit, $quantite);
        return $this->redirectToRoute('panier');
    }

    public function supprimer(PanierService $panierService, int $idProduit){
        $panierService->supprimerProduit($idProduit);
        return $this->redirectToRoute('panier');
    }
}