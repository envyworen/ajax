<?php

require_once('../config.php');
require_once('fonctions.php');

class AjaxImage {
	public $id;
	public $titre;
	public $texte;
	public $url;

	function __construct() {
		$this->id = intval($this->id);
	}

	static function connexion() {
		global $config;
		$pdo = new PDO($config['dsn1'] . 'images' . $config['dsn2'],
			$config['user'],
			$config['passwd']);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		return $pdo;
	}

	static function readOne($id) {
		$sql = 'SELECT * FROM images WHERE id = :id';
		$pdo = self::connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
		return $query->fetchObject('AjaxImage');
	}

	static function readAll() {
		$sql = 'SELECT * FROM images order by id desc';
		$pdo = self::connexion();
		$query = $pdo->prepare($sql);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_CLASS, 'AjaxImage');
	}

	function create() {
		$sql = 'INSERT INTO images (titre, texte, url)
				VALUES (:titre, :texte, :url)';
		$pdo = self::connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':titre', $this->titre);
		$query->bindValue(':texte', $this->texte);
		$query->bindValue(':url', $this->url);
		$query->execute();
		$this->id = $pdo->lastInsertId();
	}

	function update() {
		$sql = 'UPDATE images SET titre=:titre, texte=:texte, url=:url
				WHERE id=:id';
		$pdo = self::connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $this->id, PDO::PARAM_INT);
		$query->bindValue(':titre', $this->titre);
		$query->bindValue(':texte', $this->texte);
		$query->bindValue(':url', $this->url);
		$query->execute();
	}

	function delete() {
		$sql = 'DELETE FROM images WHERE id=:id';
		$pdo = self::connexion();
		$query = $pdo->prepare($sql);
		$query->bindValue(':id', $this->id, PDO::PARAM_INT);
		$query->execute();
	}

	function loadPOST() {
		$messages = [];

		// id=0 si création ; id>0 si update
		$id = postInt('id');
		if ($id > 0) $this->id = $id;

		$titre = postString('titre');
		if (!empty($titre)) $this->titre = $titre;
		else $messages[] = 'Titre vide';

		$texte = postString('texte');
		if (!empty($texte)) $this->texte = $texte;
		else $messages[] = 'Texte vide';

		$url = postString('url');
		if (!empty($url)) $this->url = $url;
		else $messages[] = 'URL vide';

		return $messages;
	}
}
