<?php
namespace App\Controller;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\BoutiqueService;

class BoutiqueController extends AbstractController {

    public function index(BoutiqueService $boutique) {
        $categories = $boutique->findAllCategories();

        return $this->render('categorie.html.twig',
            ['categories' => $categories,]
        );
    }

    public function rayon(BoutiqueService $boutique, int $idCategorie) {
        $produits = $boutique->findProduitsByCategorie($idCategorie);

        return $this->render('rayon.html.twig',
            ['produits' => $produits,]
        );
    }

    public function search(BoutiqueService $boutique, string $search) {
        if(isset($_GET['search']))
            $search = $_GET['search'];
        $produits = $boutique->findProduitsByLibelleOrTexte($search);

        return $this->render('rayon.html.twig',
            ['produits' => $produits,]
        );
    }
}