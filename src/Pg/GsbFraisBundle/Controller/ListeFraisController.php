<?php

namespace Pg\GsbFraisBundle\Controller;
require_once 'include/fct.inc.php';
require_once 'include/class.pdogsb.inc.php';

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \PdoGsb;

class ListeFraisController extends Controller {
public function IndexAction() {
        
    $session=$this->container->get('request')->getSession();// tester sans container
       // return $this->render('PgGsbFraisBundle:Home:connexion.html.twig');
    $idVisiteur = $session->get('id');
    $pdo = PdoGsb::getPdoGsb();
    $lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
    if ($this->getRequest()->getMethod() == 'GET') {
    // Afin de sélectionner par défaut le dernier mois dans la zone de liste
    // on demande toutes les clés, et on prend la première,
    // les mois étant triés décroissants
		$lesCles = array_keys($lesMois);
		$moisASelectionner = $lesCles[0];
                return $this->render('PgGsbFraisBundle:ListeFrais:ListeMois.html.twig', array (
                    'lesmois'   => $lesMois,
                    'lemois'    => $moisASelectionner,
                ));
    }
    else {
        $request = $this->getRequest();
        $leMois = $request->request->get('lstMois');
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
	$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$leMois);
	$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
	$numAnnee =substr( $leMois,0,4);
	$numMois =substr( $leMois,4,2);
	$libEtat = $lesInfosFicheFrais['libEtat'];
	$montantValide = $lesInfosFicheFrais['montantValide'];
	$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
	$dateModif =  $lesInfosFicheFrais['dateModif'];
	$dateModif =  dateAnglaisVersFrancais($dateModif);
        return $this->render('PgGsbFraisBundle:ListeFrais:Listetouslesfrais.html.twig', array (
                    'lesmois'               => $lesMois,
                    'lesfraisforfait'       => $lesFraisForfait,
                    'lesfraishorsforfait'   => $lesFraisHorsForfait,
                    'lemois'                => $leMois,
                    'numannee'              => $numAnnee,
                    'nummois'               => $numMois,
                    'libetat'               => $libEtat,
                    'montantvalide'         => $montantValide,
                    'nbjustificatifs'       => $nbJustificatifs,
                    'datemodif'             => $dateModif,
                ));
    }
}
}
?>
