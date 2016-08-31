<?php

use Symfony\Component\HttpFoundation\Request;

/**
 *
 *   Find all films
 *
 */
$app->get('/api/films', function () use ($app) {

	$responseData = array();
	$sql = "SELECT * FROM jos_diabwbs_serie ORDER BY titre";
	$result = $app['db']->fetchAll($sql);

	$films = array();
	foreach ( $result as $row ) {
		$id = $row['id'];

		$films[$id] = array(
			'id' => $row['id'],
			'state' => $row['state'],
			'titre' => $row['titre'],
			'nom_type' => $row['nom_type'],
			'saison' => $row['saison'],
			'episode' => $row['episode'],
			'epmax' => $row['epmax'],
			'etat' => $row['etat'],
			'img_av' => $row['img_av'],
			'url' => $row['url'],
			'last_update' => $row['last_update'],
		);
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
			'last_update' => $film['last_update']
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
	$sql = "SELECT * FROM jos_diabwbs_serie WHERE nom_type=? ORDER BY titre";
	$result = $app['db']->fetchAll($sql, array($type));

	$films = array();
	foreach ( $result as $row ) {
		$id = $row['id'];

		$films[$id] = array(
			'id' => $row['id'],
			'state' => $row['state'],
			'titre' => $row['titre'],
			'nom_type' => $row['nom_type'],
			'saison' => $row['saison'],
			'episode' => $row['episode'],
			'epmax' => $row['epmax'],
			'etat' => $row['etat'],
			'img_av' => $row['img_av'],
			'url' => $row['url'],
			'last_update' => $row['last_update'],
		);
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
			'last_update' => $film['last_update']
		);
	}

	return $app->json($responseData);
})->bind('api_films_state');

/**
 *
 *   Find one film by $id
 *
 */
$app->get('/api/film/{id}', function ($id, Request $request) use ($app) {
	$sql = "SELECT * FROM jos_diabwbs_serie WHERE id=?";
	$row = $app['db']->fetchAssoc($sql, array($id));

	if ($row) {
		$film = array(
			'id' => $row['id'],
			'state' => $row['state'],
			'titre' => $row['titre'],
			'nom_type' => $row['nom_type'],
			'saison' => $row['saison'],
			'episode' => $row['episode'],
			'epmax' => $row['epmax'],
			'etat' => $row['etat'],
			'img_av' => $row['img_av'],
			'url' => $row['url'],
			'last_update' => $row['last_update'],
		);
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
		'last_update' => $film['last_update']
	);

	return $app->json($responseData);
})->bind('api_film');

/**
 *
 *   Create one film
 *
 */
$app->post('/api/film/create', function (Request $request) use ($app) {
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
		$film['img_av'] = "";
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

	return $app->json($responseData, 201);
})->bind('api_film_add');

/**
 *
 *   Delete one film by $id
 *
 */
$app->delete('/api/film/delete/{id}', function ($id, Request $request) use ($app) {
	// $app['db']->delete('jos_diabwbs_serie', array('id' => $id));

	return $app->json('No content', 204);
})->bind('api_film_delete');

/**
 *
 *   Update one film by $id
 *
 */
$app->put('/api/film/update/{id}', function ($id, Request $request) use ($app) {

	$sql = "SELECT * FROM jos_diabwbs_serie WHERE id=?";
	$row = $app['db']->fetchAssoc($sql, array($id));

	if ($row) {
		$film = array(
			'id' => $row['id'],
			'state' => $row['state'],
			'titre' => $row['titre'],
			'nom_type' => $row['nom_type'],
			'saison' => $row['saison'],
			'episode' => $row['episode'],
			'epmax' => $row['epmax'],
			'etat' => $row['etat'],
			'img_av' => $row['img_av'],
			'url' => $row['url'],
			'last_update' => $row['last_update'],
		);
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
	if ($request->request->has('titre')) {
		$film['titre'] = $request->request->get('titre');
	}
	if ($request->request->has('nom_type')) {
		$film['nom_type'] = $request->request->get('nom_type');
	}
	if ($request->request->has('saison')) {
		$film['saison'] = $request->request->get('saison');
	}
	if ($request->request->has('episode')) {
		$film['episode'] = $request->request->get('episode');
	}
	if ($request->request->has('epmax')) {
		$film['epmax'] = $request->request->get('epmax');
	}
	if ($request->request->has('etat')) {
		$film['etat'] = $request->request->get('etat');
	}
	if ($request->request->has('img_av')) {
		$film['img_av'] = $request->request->get('img_av');
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
		'last_update' => $film['last_update']
	);

	return $app->json($responseData, 202);
})->bind('api_film_update');
