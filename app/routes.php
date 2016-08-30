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
		exit();
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
	$film->setState(1);
	$film->setTitre($request->request->get('titre'));
	$film->setNom_type($request->request->get('nom_type'));
  if ($request->request->has('saison')) {
  	 $film->setSaison($request->request->get('saison'));
  } else {
     $film->setSaison(1);
  }
  if ($request->request->has('episode')) {
  	 $film->setEpisode($request->request->get('episode'));
  } else {
     $film->setEpisode(1);
  }
  if ($request->request->has('epmax')) {
    $film->setEpmax($request->request->get('epmax'));
  }
  $film->setEtat($request->request->get('etat'));
  if ($request->request->has('img_av')) {
    $film->setImg_av($request->request->get('img_av'));
  }
  if ($request->request->has('url')) {
    $film->setUrl($request->request->get('url'));
  }
  if ($request->request->has('last_update')) {
    $film->setLast_update($request->request->get('last_update'));
  }

	$app['dao.film']->save($film);

	$responseData = array(
		'id' => $film->getId(),
    'state' => $film->getState(),
    'titre' => $film->getTitre(),
    'nom_type' => $film->getNom_type(),
    'saison' => $film->getSaison(),
    'episode' => $film->getEpisode(),
    'epmax' => $film->getEpmax(),
    'etat' => $film->getEtat(),
    'img_av' => $film->getImg_av(),
    'url' => $film->getUrl(),
    'last_update' => $film->getLast_update()
	);

	return $app->json($responseData, 201);
})->bind('api_film_add');

// Delete film
$app->delete('/api/film/delete/{id}', function ($id, Request $request) use ($app) {
	$app['dao.film']->delete($id);

	return $app->json('No content', 204);
})->bind('api_film_delete');

// Update film
$app->put('/api/film/update/{id}', function ($id, Request $request) use ($app) {
	$film = $app['dao.film']->find($id);

	$film->setTitre($request->request->get('titre'));
	$film->setNom_type($request->request->get('nom_type'));
  if ($request->request->has('saison')) {
  	 $film->setSaison($request->request->get('saison'));
  } else {
     $film->setSaison(1);
  }
  if ($request->request->has('episode')) {
  	 $film->setEpisode($request->request->get('episode'));
  } else {
     $film->setEpisode(1);
  }
  if ($request->request->has('epmax')) {
    $film->setEpmax($request->request->get('epmax'));
  }
  $film->setEtat($request->request->get('etat'));
  if ($request->request->has('img_av')) {
    $film->setImg_av($request->request->get('img_av'));
  }
  if ($request->request->has('url')) {
    $film->setUrl($request->request->get('url'));
  }
  if ($request->request->has('last_update')) {
    $film->setLast_update($request->request->get('last_update'));
  }
	$app['dao.film']->save($film);

	$responseData = array(
		'id' => $film->getId(),
    'state' => $film->getState(),
    'titre' => $film->getTitre(),
    'nom_type' => $film->getNom_type(),
    'saison' => $film->getSaison(),
    'episode' => $film->getEpisode(),
    'epmax' => $film->getEpmax(),
    'etat' => $film->getEtat(),
    'img_av' => $film->getImg_av(),
    'url' => $film->getUrl(),
    'last_update' => $film->getLast_update()
	);

	return $app->json($responseData, 202);
})->bind('api_film_update');
