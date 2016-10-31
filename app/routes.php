<?php

use Symfony\Component\HttpFoundation\Request;

/**
 *
 *   Find all films
 *
 */
$app->get('/api/films', function () use ($app) {

	$responseData = array();
	$sql = "SELECT s.*, c.nom, c.description, c.etat as state FROM series AS s INNER JOIN categories AS c WHERE s.categorie=c.id ORDER BY titre";
	$result = $app['db']->fetchAll($sql);

	$films = array();
	foreach ( $result as $row ) {
		$id = $row['id'];
		$films[$id] = $row;
	}

	foreach ($films as $film) {
		$responseData[] = array(
			'id' => $film['id'],
			'id_mdb' => $film['state'],
			'titre' => $film['titre'],
			'saison' => $film['saison'],
			'episode' => $film['episode'],
			'data' => $film['data'],
			'etat' => $film['etat'],
			'categorie' => $film['categorie'],
			'nom' => $film['nom'],
			'description' => $film['description'],
			'state' => $film['state'],
			'last_update' => $film['last_update'],
		);
	}

	return $app->json($responseData);
})->bind('api_films');

/**
 *
 *   Find all films by $state
 *
 */
$app->get('/api/films/type/{type}', function ($type) use ($app) {

	$responseData = array();
	$sql = "SELECT s.*, c.nom, c.description, c.etat as state FROM series AS s INNER JOIN categories AS c WHERE s.categorie=c.id AND c.id=? ORDER BY titre";
	$result = $app['db']->fetchAll($sql, array($type));

	$films = array();
	foreach ( $result as $row ) {
		$id = $row['id'];
		$films[$id] = $row;
	}

	foreach ($films as $film) {
		$responseData[] = array(
			'id' => $film['id'],
			'id_mdb' => $film['id_mdb'],
			'titre' => $film['titre'],
			'saison' => $film['saison'],
			'episode' => $film['episode'],
			'data' => $film['data'],
			'etat' => $film['etat'],
			'categorie' => $film['categorie'],
			'nom' => $film['nom'],
			'description' => $film['description'],
			'state' => $film['state'],
			'last_update' => $film['last_update'],
		);
	}

	return $app->json($responseData);
})->bind('api_films_state');

/**
 *
 *   Find one film by $id
 *
 */
$app->get('/api/films/{id}', function ($id, Request $request) use ($app) {
	$sql = "SELECT s.*, c.nom, c.description, c.etat as state FROM series AS s INNER JOIN categories AS c WHERE s.id=?";
	$row = $app['db']->fetchAssoc($sql, array($id));

	if ($row) {
		$film = $row;
	} else {
		throw new \Exception("Aucun film de correspond à l'id ".$id);
	}
	if (!isset($film)) {
		$app->abort(404, "Ce film n'existe pas");
	}

	$responseData = array(
		'id' => $film['id'],
		'id_mdb' => $film['id_mdb'],
		'titre' => $film['titre'],
		'saison' => $film['saison'],
		'episode' => $film['episode'],
		'data' => $film['data'],
		'etat' => $film['etat'],
		'categorie' => $film['categorie'],
		'nom' => $film['nom'],
		'description' => $film['description'],
		'state' => $film['state'],
		'last_update' => $film['last_update'],
	);

	return $app->json($responseData);
})->bind('api_film');

/**
 *
 *   Find all logs
 *
 */
$app->get('/api/logs', function () use ($app) {
	$responseData = array();
	$sql = "SELECT h.id, h.last_update, h.action, h.saison, h.episode, h.detail, s.titre FROM history AS h INNER JOIN series AS s WHERE h.id_serie=s.id ORDER BY last_update";
	$result = $app['db']->fetchAll($sql);

	$logs = array();
	foreach ( $result as $row ) {
		$id = $row['id'];
		$logs[$id] = $row;
	}

	foreach ($logs as $log) {
		$responseData[] = array(
			'id' => $log['id'],
			'last_update' => $log['last_update'],
			'action' => $log['action'],
			'titre' => $log['titre'],
			'saison' => $log['saison'],
			'episode' => $log['episode'],
			'detail' => json_decode($log['detail']),
		);
	}

	return $app->json($responseData);
})->bind('api_log_films');

/**
 *
 *   Find all logs by $state
 *
 */
$app->get('/api/logs/type/{type}', function ($type) use ($app) {
	$responseData = array();
	$sql = "SELECT h.id, h.last_update, h.action, h.saison, h.episode, h.detail, s.titre FROM history AS h INNER JOIN series AS s INNER JOIN categories AS c WHERE h.id_serie=s.id AND s.categorie=c.id AND c.id=?  ORDER BY last_update";
	$result = $app['db']->fetchAll($sql, array($type));

	$logs = array();
	foreach ( $result as $row ) {
		$id = $row['id'];
		$logs[$id] = $row;
	}

	foreach ($logs as $log) {
		$responseData[] = array(
			'id' => $log['id'],
			'last_update' => $log['last_update'],
			'action' => $log['action'],
			'titre' => $log['titre'],
			'saison' => $log['saison'],
			'episode' => $log['episode'],
			'detail' => json_decode($log['detail']),
		);
	}

	return $app->json($responseData);
})->bind('api_log_films_state');

/**
 *
 *   Find all logs by $id
 *
 */
$app->get('/api/logs/{id}', function ($id) use ($app) {
	$responseData = array();
	$sql = "SELECT h.id, h.last_update, h.action, h.saison, h.episode, h.detail, s.titre FROM history AS h INNER JOIN series AS s WHERE h.id_serie=s.id AND s.id=?  ORDER BY last_update";
	$result = $app['db']->fetchAll($sql, array($id));

	$logs = array();
	foreach ( $result as $row ) {
		$id = $row['id'];
		$logs[$id] = $row;
	}

	foreach ($logs as $log) {
		$responseData[] = array(
			'id' => $log['id'],
			'last_update' => $log['last_update'],
			'action' => $log['action'],
			'titre' => $log['titre'],
			'saison' => $log['saison'],
			'episode' => $log['episode'],
			'detail' => json_decode($log['detail']),
		);
	}

	return $app->json($responseData);
})->bind('api_log_film');

/**
 *
 *   Create one film
 *
 */
$app->post('/api/films', function (Request $request) use ($app) {

	$log = array();

	if (!$request->request->has('titre')) {
		return $app->json('Paramètre manquant: titre', 400);
	}

	$film = array();

	if ($request->request->has('id_mdb')) {
		$film['id_mdb'] = $request->request->get('id_mdb');
	} else {
		$film['id_mdb'] = null;
	}
	$film['titre'] = $request->request->get('titre');
	if ($request->request->has('saison')) {
		$film['saison'] = $request->request->get('saison');
	} else {
		$film['saison'] = 1;
	}
	if ($request->request->has('episode')) {
		$film['episode'] = $request->request->get('episode');
	} else {
		$film['episode'] = 1;
	}
	if ($request->request->has('data')) {
		$film['data'] = $request->request->get('data');
	} else {
		$film['data'] = "";
	}
	if ($request->request->has('etat')) {
		$film['etat'] = $request->request->get('etat');
	} else {
		$film['etat'] = 1;
	}
	if ($request->request->has('categorie')) {
		$film['categorie'] = $request->request->get('categorie');
	} else {
		$film['categorie'] = 1;
	}



	$film['last_update'] = date_create()->format('Y-m-d H:i:s');

	$filmData = array(
		'id_mdb' => $film['id_mdb'],
		'titre' => $film['titre'],
		'saison' => $film['saison'],
		'episode' => $film['episode'],
		'data' => $film['data'],
		'etat' => $film['etat'],
		'categorie' => $film['categorie'],
		'last_update' => $film['last_update'],
	);

	$app['db']->insert('series', $filmData);
	$id = $app['db']->lastInsertId();
	// $app['db']->update('series', array('id' => $id));

	$responseData = array(
		'id' => $id,
		'id_mdb' => $film['id_mdb'],
		'titre' => $film['titre'],
		'saison' => $film['saison'],
		'episode' => $film['episode'],
		'data' => $film['data'],
		'etat' => $film['etat'],
		'categorie' => $film['categorie'],
		'last_update' => $film['last_update'],
	);

	// LOG
	$filmlog = array(
		'last_update' => $film['last_update'],
		'id_serie' => $id,
		'action' => 'CREATE',
		'saison' => $film['saison'],
		'episode' => $film['episode'],
		'detail' => json_encode($log)
	);
	$app['db']->insert('history', $filmlog);

	return $app->json($responseData, 201);
})->bind('api_film_add');

/**
 *
 *   Delete one film by $id
 *
 */
$app->delete('/api/films/{id}', function ($id, Request $request) use ($app) {
	// $app['db']->delete('series', array('id' => $id));

	return $app->json('No content', 204);
})->bind('api_film_delete');

/**
 *
 *   Update one film by $id
 *
 */
$app->put('/api/films/{id}', function ($id, Request $request) use ($app) {

	$log = array();

	$sql = "SELECT * FROM series WHERE id=?";
	$row = $app['db']->fetchAssoc($sql, array($id));

	if ($row) {
		$film = $row;
	} else {
		$app->abort(404, "Aucun film de correspond à l'id ".$id);
		throw new \Exception("Aucun film de correspond à l'id ".$id);
	}
	if (!isset($film)) {
		$app->abort(404, "Ce film n'existe pas");
	}

	if ($request->request->has('id_mdb')) {
		$film['id_mdb'] = $request->request->get('id_mdb');
	}
	if ($request->request->has('titre')) {
		if($film['titre'] != $request->request->get('titre'))		$log['titre'] = $film['titre'];
		$film['titre'] = $request->request->get('titre');
	}
	if ($request->request->has('saison')) {
		if($film['saison'] != $request->request->get('saison'))		$log['saison'] = $film['saison'];
		$film['saison'] = $request->request->get('saison');
	}
	if ($request->request->has('episode')) {
		if($film['episode'] != $request->request->get('episode'))	$log['episode'] = $film['episode'];
		$film['episode'] = $request->request->get('episode');
	}
	if ($request->request->has('data')) {
		$film['data'] = $request->request->get('data');
	}
	if ($request->request->has('etat')) {
		if($film['etat'] != $request->request->get('etat'))			$log['etat'] = $film['etat'];
		$film['etat'] = $request->request->get('etat');
	}
	if ($request->request->has('categorie')) {
		$film['categorie'] = $request->request->get('categorie');
	}
	$film['last_update'] = date_create()->format('Y-m-d H:i:s');

	$app['db']->update('series', $film, array('id' => $id));

	$responseData = array(
		'id' => $id,
		'id_mdb' => $film['id_mdb'],
		'titre' => $film['titre'],
		'saison' => $film['saison'],
		'episode' => $film['episode'],
		'data' => $film['data'],
		'etat' => $film['etat'],
		'categorie' => $film['categorie'],
		'last_update' => $film['last_update'],
		'log' => json_encode($log)
	);

	// LOG
	$filmlog = array(
		'last_update' => $film['last_update'],
		'id_serie' => $id,
		'action' => 'UPDATE',
		'saison' => $film['saison'],
		'episode' => $film['episode'],
		'detail' => json_encode($log)
	);
	$app['db']->insert('history', $filmlog);

	return $app->json($responseData, 202);
})->bind('api_film_update');
