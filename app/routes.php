<?php

use Symfony\Component\HttpFoundation\Request;
use SilexApi\Film;

// Get all films
$app->get('/api/films', function () use ($app) {

	$responseData = array();
	$sql = "SELECT * FROM jos_diabwbs_serie";
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

	// return $entities;
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

// Get on film
$app->get('/api/film/{id}', function ($id, Request $request) use ($app) {
	// $film = $app['dao.film']->find($id);
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

// Create film
$app->post('/api/film/create', function (Request $request) use ($app) {
	if (!$request->request->has('titre')) {
		return $app->json('Missing parameter: titre', 400);
	}
	if (!$request->request->has('nom_type')) {
		return $app->json('Missing parameter: nom_type', 400);
	}
	if (!$request->request->has('etat')) {
		return $app->json('Missing parameter: etat', 400);
	}

	$film = array();
	$film['state'] = 1;
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
		$film['epmax'] = 999;
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
	// $film['last_update'] = $request->request->get('last_update');
	$ordering = $app['db']->lastInsertId();

	$filmData = array(
		'state' => $film['state'],
		'ordering' => $ordering,
		'checked_out' => 0,
		'checked_out_time' => date_create()->format('Y-m-d H:i:s'),
		'created_by' => 438,
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

// Delete film
$app->delete('/api/film/delete/{id}', function ($id, Request $request) use ($app) {
	// $app['dao.film']->delete($id);
	$app['db']->delete('jos_diabwbs_serie', array('id' => $id));

	return $app->json('No content', 204);
})->bind('api_film_delete');

// Update film
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
		throw new \Exception("Aucun film de correspond à l'id ".$id);
	}
	if (!isset($film)) {
		$app->abort(404, "Ce film n'existe pas");
	}

	// $film = $app['dao.film']->find($id);

	if ($request->request->has('state')) {
		$film['state'] = $request->request->get('state');
	} else {
		$film['state'] = 1;
	}
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
	}
	$film['etat'] = $request->request->get('etat');
	if ($request->request->has('img_av')) {
		$film['img_av'] = $request->request->get('img_av');
	}
	if ($request->request->has('url')) {
		$film['url'] = $request->request->get('url');
	}
	$film['last_update'] = date_create()->format('Y-m-d H:i:s');

	$app['db']->update('jos_diabwbs_serie', $film, array('id' => $id));

	// $app['dao.film']->save($film);

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
