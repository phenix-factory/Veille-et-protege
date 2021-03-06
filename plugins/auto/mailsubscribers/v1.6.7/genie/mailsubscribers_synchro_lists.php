<?php
/**
 * Plugin mailsubscribers
 * (c) 2012 C�dric Morin
 * Licence GNU/GPL v3
 *
 */


/**
 * CRON de synchro des listes
 * @param $t
 * @return int
 */
function genie_mailsubscribers_synchro_lists_dist($t){

	include_spip("inc/mailsubscribers");

	$listes = mailsubscribers_listes(array('category'=>'newsletter'));

	// pour chaque liste disponible on inserer un job de synchro (si on trouve la fonction de synchro)
	// pour les traiter separemment les uns des autres si jamais l'un est trop gros
	foreach($listes as $liste){
		if ($liste['status']=='open'
		  AND mailsubscribers_trouver_fonction_synchro($liste['id'])){
			job_queue_add("mailsubscribers_do_synchro_list","Synchro liste ".$liste['titre'],array($liste['id']),"inc/mailsubscribers");
		}
	}

	return 1;
}