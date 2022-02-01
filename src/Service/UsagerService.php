<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UsagerService {
////////////////////////////////////////////////////////////////////////////
    const  USAGER_SESSSION = 'id_usager'; // Le nom de la variable de session de l'usager
    private $session;
    private $id;

    public function __construct(SessionInterface $session) {
        //Récupération des services session
        $this->session = $session;
        $this->id = $session->get($this::USAGER_SESSSION, 0);
    }

    public function setIdSession(int $id) {
        $this->id = $id;
        $this->session->set($this::USAGER_SESSSION, $id);
    }

    public function getIdSession() : int {
        return $this->id;
    }
}