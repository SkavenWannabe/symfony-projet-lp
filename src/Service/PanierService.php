<?php
// src/Service/PanierService.php
namespace App\Service;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\Usager;
use App\Repository\ArticleRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

// Service pour manipuler le panier et le stocker en session
class PanierService {
////////////////////////////////////////////////////////////////////////////

    const  PANIER_SESSION = 'panier'; // Le nom de la variable de session du panier
    private $session; // Le service Session
    private $articleRepository; // Le service Boutique
    private $panier; // Tableau associatif idProduit => quantite
    // donc $this->panier[$i] = quantite du produit dont l'id = $i
    // constructeur du service

    public function __construct(SessionInterface $session, ArticleRepository $articleRepository) {
        //Récupération des services session et BoutiqueService
        $this->articleRepository = $articleRepository;
        $this->session = $session;
         //Récupération du panier en session s'il existe, init. à vide sinon
        $this->panier = $session->get($this::PANIER_SESSION, array());
    }

    // getContenu renvoie le contenu du panier
    // tableau d'éléments [ "produit" => un produit, "quantite" => quantite ]
    public function getContenu() : array{
        $produits = array();

        foreach ($this->panier as $id => $quantite) {
            $produit = $this->articleRepository->findBy(array('id' => $id));
            if($produit != null) {
                $produits[$id] = ['produit' => $produit, 'quantite' => $quantite];
            }
        }

        return $produits;
    }

    // getTotal renvoie le montant total du panier
    public function getTotal() : float{
        $total = 0;

        foreach ($this->panier as $id => $quantite) {
            $produit = $this->articleRepository->findOneBy(array('id' => $id));
            if($produit != null) {
                $total += $produit->getPrix() * $quantite;
            }
        }

        return $total;
    }

    // getNbProduits renvoie le nombre de produits dans le panier
    public function getNbArticle() {
        $nbProduits = 0;

        foreach ($this->panier as $quantite) {
            $nbProduits += $quantite;
        }

        return $nbProduits;
    }

    // ajouterProduit ajoute au panier le produit $idProduit en quantite $quantite
    public function ajouterArticle(int $idArticle, int $quantite = 1) {
        if(isset($this->panier[$idArticle]))
            $this->panier[$idArticle] += $quantite;
        else
            $this->panier[$idArticle] = $quantite;

        $this->session->set($this::PANIER_SESSION, $this->panier);
    }

    // enleverProduit enlève du panier le produit $idProduit en quantite $quantite
    public function enleverArticle(int $idArticle, int $quantite = 1) {
        if(isset($this->panier[$idArticle])) {
            $this->panier[$idArticle] -= $quantite;

            if($this->panier[$idArticle] <= 0)
                $this->supprimerArticle($idArticle);

            $this->session->set($this::PANIER_SESSION, $this->panier);
        }
    }

    // supprimerProduit supprime complètement le produit $idProduit du panier
    public function supprimerArticle(int $idArticle) {
        if(isset($this->panier[$idArticle])) {
            unset($this->panier[$idArticle]);

            $this->session->set($this::PANIER_SESSION, $this->panier);
        }
    }

    // vider vide complètement le panier
    public function vider() {
        unset($this->panier);

        $this->panier = array();
        $this->session->set($this::PANIER_SESSION, $this->panier);
    }


    function panierToCommande(Usager $usager, EntityManagerInterface $entityManager) : Commande {
        $commande = new Commande();
        if(count($this->panier) > 0) {
            //generate commande
            $commande->setDateCommande(
                \DateTime::createFromFormat('Y-m-d', date('Y-m-d'))
            );
            $commande->setIdUsager($usager);

            $commande->setStatus('En cours');

            $entityManager->persist($commande);

            //generate ligne commande
            foreach ($this->panier as $idArticle => $quantite) {
                $ligneCommande = new LigneCommande();

                $article = $this->articleRepository->findOneBy(array('id' => $idArticle));

                $ligneCommande->setIdArticle($article);
                $ligneCommande->setIdCommande($commande);
                $ligneCommande->setQuantite($quantite);
                $ligneCommande->setPrix($article->getPrix());

                $entityManager->persist($ligneCommande);
            }
            $this->vider();

            $entityManager->flush();
        }
        return $commande;
    }
}