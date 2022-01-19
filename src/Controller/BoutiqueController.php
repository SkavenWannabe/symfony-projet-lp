<?php
namespace App\Controller;
use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BoutiqueController extends AbstractController {

    public function index() {
        $categories = $this->getDoctrine()->getManager()->getRepository(Categorie::class)->findAll();

        return $this->render('categorie.html.twig',
            ['categories' => $categories,]
        );
    }

    public function rayon(int $idCategorie) {
        $produits = $this->getDoctrine()->getManager()->getRepository(Produit::class)->findBy(array('idCategorie' => $idCategorie));

        return $this->render('rayon.html.twig',
            ['produits' => $produits,]
        );
    }

    public function search() {
        $search = '';
        if(isset($_GET['search']))
            $search = $_GET['search'];
        //$produits = $boutique->findProduitsByLibelleOrTexte($search);
        $produits = $this->getDoctrine()->getManager()->getRepository(Produit::class)->findByTexteOrLibelle($search);

        return $this->render('rayon.html.twig',
            ['produits' => $produits,]
        );
    }
}