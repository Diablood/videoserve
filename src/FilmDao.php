<?php
namespace SilexApi;

use SilexApi\Film;

use Doctrine\DBAL\Connection;

class FilmDao
{
  private $db;

  public function __construct(Connection $db)
	{
		$this->db = $db;
	}

	protected function getDb()
	{
		return $this->db;
	}

  public function findAll()
	{
		$sql = "SELECT * FROM jos_diabwbs_serie";
		$result = $this->getDb()->fetchAll($sql);

		$entities = array();
		foreach ( $result as $row ) {
			$id = $row['id'];
			$entities[$id] = $this->buildDomainObjects($row);
		}

		return $entities;
	}

	public function find($id)
	{
		$sql = "SELECT * FROM jos_diabwbs_serie WHERE id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));

		if ($row) {
			return $this->buildDomainObjects($row);
		} else {
			throw new \Exception("Aucun film de correspond à l'id ".$id);
		}
	}

	public function save(Film $film)
	{
		$filmData = array(
			'state' => $film->getState(),
			'titre' => $film->getTitre(),
			'nom_type' => $film->getNom_type(),
			'saison' => $film->getSaison(),
			'episode' => $film->getEpisode(),
			'epmax' => $film->getEpmax(),
			'etat' => $film->getEtat(),
			'img_av' => $film->getImg_av(),
			'url' => $film->getUrl(),
			'last_update' => $film->getLast_update(),
		);

		// TODO CHECK
		if ($film->getId()) {
			$this->getDb()->update('jos_diabwbs_serie', $filmData, array('id' => $film->getId()));
		} else {
			$this->getDb()->insert('jos_diabwbs_serie', $filmData);
			$id = $this->getDb()->lastInsertId();
			$film->setId($id);
		}
	}

	public function delete($id)
	{
		$this->getDb()->delete('jos_diabwbs_serie', array('id' => $id));
	}

	protected function buildDomainObjects($row)
	{
		$film = new Film();
		$film->setId($row['id']);
		$film->setState($row['state']);
		$film->setTitre($row['titre']);
		$film->setNom_type($row['nom_type']);
		$film->setSaison($row['saison']);
		$film->setEpisode($row['episode']);
		$film->setEpmax($row['epmax']);
		$film->setEtat($row['etat']);
		$film->setImg_av($row['img_av']);
		$film->setUrl($row['url']);
		$film->setLast_update($row['last_update']);

		return $film;
	}

}
