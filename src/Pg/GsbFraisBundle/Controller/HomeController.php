<?php

namespace Pg\GsbFraisBundle\Controller;

require_once 'include/fct.inc.php';
require_once 'include/class.pdogsb.inc.php';

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \PdoGsb;

class HomeController extends Controller {

    public function indexAction() {
      // ici je rajoute un changement
      $varInutile = "Complètement inutile";
        // ATTENTION CHANGEMENTS A VERIFIER
        $session = $this->get('session');
        if (estconnecte($session)) {
            return $this->render('PgGsbFraisBundle::accueil.html.twig');
        }
        else
            return $this->render('PgGsbFraisBundle:Home:connexion.html.twig');
    }

    public function validerconnexionAction() {
        
        $session = $this->get('session');
        //$session= $this->get('request')->getSession(); 
        //$login = $session->get('login');
        //$mdp = $session->get('mdp');
        
        $request = $this->get('request');
        // les variables sont passées par un formulaire donc en "POST"
        // on utilise le service request
        $login =  $request->request->get('login');
        $mdp = $request->request->get('mdp');
        $pdo = PdoGsb::getPdoGsb();          
      
        $visiteur = $pdo->getInfosVisiteur($login, $mdp);
        if (!is_array($visiteur)) {
            return $this->render('PgGsbFraisBundle:Home:connexion.html.twig', array(
                        'message' => 'Erreur de login ou de mot de passe ',
            ));
        } else {
            $session->set('id', $visiteur['id']);
            $session->set('nom', $visiteur['nom']);
            $session->set('prenom', $visiteur['prenom']);
            return $this->render('PgGsbFraisBundle::accueil.html.twig');
        }
    }
    
    public function deconnexionAction() {
        // ATTENTION CHANGEMENTS A VERIFIER
        $session = $this->get('session');
        $session->clear();       
        return $this->render('PgGsbFraisBundle:Home:connexion.html.twig');
    }
}
