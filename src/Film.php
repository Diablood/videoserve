<?php

namespace SilexApi;

class Film
{
    /**
  	 * @var integer
  	 */
    private $id;

    /**
  	 * @var integer
  	 */
    private $state;

    /**
  	 * @var string
  	 */
    private $titre;

    /**
     * @var string
     */
    private $nom_type;

    /**
  	 * @var integer
  	 */
    private $saison;

    /**
  	 * @var integer
  	 */
    private $episode;

    /**
  	 * @var integer
  	 */
    private $epmax;

    /**
  	 * @var integer
  	 */
    private $etat;

    /**
     * @var string
     */
    private $img_av;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $last_update;

    /**
  	 * @return int
  	 */
    public function getId() {
      return $this->id;
    }
    /**
  	 * @param int $id
  	 */
    public function setId() {
      $this->id = $id;
    }

    /**
  	 * @return int
  	 */
    public function getState() {
      return $this->state;
    }
    /**
  	 * @param int $state
  	 */
    public function setState() {
      $this->state = $state;
    }

    /**
  	 * @return string
  	 */
    public function getTitre() {
      return $this->titre;
    }
    /**
  	 * @param string $titre
  	 */
    public function setTitre() {
      $this->titre = $titre;
    }

    /**
  	 * @return string
  	 */
    public function getNom_type() {
      return $this->nom_type
    }
    /**
  	 * @param string $nom_type
  	 */
    public function setNom_type() {
      $this->nom_type = $nom_type;
    }

    /**
  	 * @return int
  	 */
    public function getSaison() {
      return $this->saison
    }
    /**
  	 * @param int $saison
  	 */
    public function setSaison() {
      $this->saison = $saison;
    }

    /**
  	 * @return int
  	 */
    public function getEpisode() {
      return $this->episode
    }
    /**
  	 * @param int $episode
  	 */
    public function setEpisode() {
      $this->episode = $episode;
    }

    /**
  	 * @return int
  	 */
    public function getEpmax() {
      return $this->epmax
    }
    /**
  	 * @param int $epmax
  	 */
    public function setEpmax() {
      $this->epmax = $epmax;
    }

    /**
  	 * @return int
  	 */
    public function getEtat() {
      return $this->etat
    }
    /**
  	 * @param int $etat
  	 */
    public function setEtat() {
      $this->etat = $etat;
    }

    /**
  	 * @return string
  	 */
    public function getImg_av() {
      return $this->img_av
    }
    /**
  	 * @param string $img_av
  	 */
    public function setImg_av() {
      $this->img_av = $img_av;
    }

    /**
  	 * @return string
  	 */
    public function getUrl() {
      return $this->url
    }
    /**
  	 * @param string $url
  	 */
    public function setUrl() {
      $this->url = $url;
    }

    /**
  	 * @return string
  	 */
    public function getLast_update() {
      return $this->last_update
    }
    /**
  	 * @param string $last_update
  	 */
    public function setLast_update() {
      $this->last_update = $last_update;
    }
}
