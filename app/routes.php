<?php

use Symfony\Component\HttpFoundation\Request;

/**
 *
 *   Find all films
 *
 */
$app->get('/api/films', function () use ($app) {

	$responseData = array();
	$sql = "SELECT s.*, c.nom, c.description FROM jos_diabwbs_serie AS s INNER JOIN serie_cat AS c WHERE s.nom_type=c.id ORDER BY titre";
	$result = $app['db']->fetchAll($sql);

	$films = array();
	foreach ( $result as $row ) {
		$id = $row['id'];
		$films[$id] = $row;
	}

	foreach ($films as $film) {
		$responseData[] = array(
			'id' => $film['id'],
			'state' => $film['state'],
			'titre' => $film['titre'],
			'nom_type' => $film['nom_type'],
			'saison' => $film['saison'],
			'episode' => $film['episode'],
			'epmax' => $film['epmax'],
			'etat' => $film['etat'],
			'img_av' => $film['img_av'],
			'url' => $film['url'],
			'last_update' => $film['last_update'],
			'nom' => $film['nom'],
			'description' => $film['description']
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
	$sql = "SELECT s.*, c.nom, c.description FROM jos_diabwbs_serie AS s INNER JOIN serie_cat AS c WHERE s.nom_type=c.id AND c.id=? ORDER BY titre";
	// $sql = "SELECT * FROM jos_diabwbs_serie WHERE nom_type=? ORDER BY titre";
	$result = $app['db']->fetchAll($sql, array($type));

	$films = array();
	foreach ( $result as $row ) {
		$id = $row['id'];
		$films[$id] = $row;
	}

	foreach ($films as $film) {
		$responseData[] = array(
			'id' => $film['id'],
			'state' => $film['state'],
			'titre' => $film['titre'],
			'nom_type' => $film['nom_type'],
			'saison' => $film['saison'],
			'episode' => $film['episode'],
			'epmax' => $film['epmax'],
			'etat' => $film['etat'],
			'img_av' => $film['img_av'],
			'url' => $film['url'],
			'last_update' => $film['last_update'],
			'nom' => $film['nom'],
			'description' => $film['description']
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
	$sql = "SELECT s.*, c.nom, c.description FROM jos_diabwbs_serie AS s INNER JOIN serie_cat AS c WHERE s.id=?";
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
		'state' => $film['state'],
		'titre' => $film['titre'],
		'nom_type' => $film['nom_type'],
		'saison' => $film['saison'],
		'episode' => $film['episode'],
		'epmax' => $film['epmax'],
		'etat' => $film['etat'],
		'img_av' => $film['img_av'],
		'url' => $film['url'],
		'last_update' => $film['last_update'],
		'nom' => $film['nom'],
		'description' => $film['description']
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
	$sql = "SELECT h.id, h.last_update, h.action, h.saison, h.episode, h.detail, s.titre, s.img_av FROM history AS h INNER JOIN jos_diabwbs_serie AS s WHERE h.id_serie=s.id ORDER BY last_update";
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
			'img_av' => $log['img_av'],
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
	$sql = "SELECT h.id, h.last_update, h.action, h.saison, h.episode, h.detail, s.titre, s.img_av FROM history AS h INNER JOIN jos_diabwbs_serie AS s INNER JOIN serie_cat AS c WHERE h.id_serie=s.id AND s.nom_type=c.id AND c.id=?  ORDER BY last_update";
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
			'img_av' => $log['img_av'],
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
	$sql = "SELECT h.id, h.last_update, h.action, h.saison, h.episode, h.detail, s.titre, s.img_av FROM history AS h INNER JOIN jos_diabwbs_serie AS s WHERE h.id_serie=s.id AND s.id=?  ORDER BY last_update";
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
			'img_av' => $log['img_av'],
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
	if (!$request->request->has('nom_type')) {
		return $app->json('Paramètre manquant: nom_type', 400);
	}
	if (!$request->request->has('etat')) {
		return $app->json('Paramètre manquant: etat', 400);
	}

	$film = array();
	$film['state'] = 1;
	$film['ordering'] = $app['db']->lastInsertId();
	$film['checked_out'] = 0;
	$film['checked_out_time'] = date_create()->format('Y-m-d H:i:s');
	$film['created_by'] = 438;
	$film['titre'] = $request->request->get('titre');
	$film['nom_type'] = $request->request->get('nom_type');
	if ($request->request->has('saison')) {
		$film['saison'] = $request->request->get('saison');
	} else {
		$film['saison'] = 1;
	}
	if ($request->request->has('episode')) {
		$film['episode'] = $request->request->get('episode');
	} else {
		$film['episode'] = 0;
	}
	if ($request->request->has('epmax')) {
		$film['epmax'] = $request->request->get('epmax');
	} else {
		$film['epmax'] = 0;
	}
	$film['etat'] = $request->request->get('etat');
	if ($request->request->has('img_av')) {
		$film['img_av'] = $request->request->get('img_av');
	} else {
		$film['img_av'] = "/images/avatar/aucune.jpg";
	}
	if ($request->request->has('url')) {
		$film['url'] = $request->request->get('url');
	} else {
		$film['url'] = "";
	}
	$film['last_update'] = date_create()->format('Y-m-d H:i:s');

	$filmData = array(
		'state' => $film['state'],
		'ordering' => $film['ordering'],
		'checked_out' => $film['checked_out'],
		'checked_out_time' => $film['checked_out_time'],
		'created_by' => $film['created_by'],
		'titre' => $film['titre'],
		'nom_type' => $film['nom_type'],
		'saison' => $film['saison'],
		'episode' => $film['episode'],
		'epmax' => $film['epmax'],
		'etat' => $film['etat'],
		'img_av' => $film['img_av'],
		'url' => $film['url'],
		'last_update' => $film['last_update']
	);

	$app['db']->insert('jos_diabwbs_serie', $filmData);
	$id = $app['db']->lastInsertId();
	$app['db']->update('jos_diabwbs_serie', array('ordering' => $id), array('id' => $id));

	$responseData = array(
		'id' => $id,
		'state' => $film['state'],
		'titre' => $film['titre'],
		'nom_type' => $film['nom_type'],
		'saison' => $film['saison'],
		'episode' => $film['episode'],
		'epmax' => $film['epmax'],
		'etat' => $film['etat'],
		'img_av' => $film['img_av'],
		'url' => $film['url'],
		'last_update' => $film['last_update']
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
	// $app['db']->delete('jos_diabwbs_serie', array('id' => $id));

	return $app->json('No content', 204);
})->bind('api_film_delete');

/**
 *
 *   Update one film by $id
 *
 */
$app->put('/api/films/{id}', function ($id, Request $request) use ($app) {

	$log = array();

	$sql = "SELECT * FROM jos_diabwbs_serie WHERE id=?";
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

	if ($request->request->has('state')) {
		$film['state'] = $request->request->get('state');
	}
	if($film['checked_out_time'] == "0000-00-00 00:00:00") {
		$film['checked_out_time'] = date_create()->format('Y-m-d H:i:s');
	}
	if ($request->request->has('titre')) {
		if($film['titre'] != $request->request->get('titre')) 	$log['titre'] = $film['titre'];
		$film['titre'] = $request->request->get('titre');
	}
	if ($request->request->has('nom_type')) {
		if($film['nom_type'] != $request->request->get('nom_type')) 	$log['nom_type'] = $film['nom_type'];
		$film['nom_type'] = $request->request->get('nom_type');
	}
	if ($request->request->has('saison')) {
		if($film['saison'] != $request->request->get('saison')) 	$log['saison'] = $film['saison'];
		$film['saison'] = $request->request->get('saison');
	}
	if ($request->request->has('episode')) {
		if($film['episode'] != $request->request->get('episode')) 	$log['episode'] = $film['episode'];
		$film['episode'] = $request->request->get('episode');
	}
	if ($request->request->has('epmax')) {
		$film['epmax'] = $request->request->get('epmax');
	}
	if ($request->request->has('etat')) {
		$film['etat'] = $request->request->get('etat');
	}
	if ($request->request->has('img_av')) {
		if($film['img_av'] != $request->request->get('img_av')) 	$log['img_av'] = $film['img_av'];
		if($request->request->get('img_av') == "") {
			$film['img_av'] = "/images/avatar/aucune.jpg";
		} else {
			$film['img_av'] = $request->request->get('img_av');
		}
	}
	if ($request->request->has('url')) {
		$film['url'] = $request->request->get('url');
	}
	$film['last_update'] = date_create()->format('Y-m-d H:i:s');

	$app['db']->update('jos_diabwbs_serie', $film, array('id' => $id));

	$responseData = array(
		'id' => $id,
		'state' => $film['state'],
		'titre' => $film['titre'],
		'nom_type' => $film['nom_type'],
		'saison' => $film['saison'],
		'episode' => $film['episode'],
		'epmax' => $film['epmax'],
		'etat' => $film['etat'],
		'img_av' => $film['img_av'],
		'url' => $film['url'],
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
