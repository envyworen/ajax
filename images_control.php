<?php
/** @noinspection PhpComposerExtensionStubsInspection */
require_once('images_class.php');

/* Route (page/action/id) pour le contrôleur */
$page = $_GET['page'] ?? 'article';
$action = $_GET['action'] ?? 'read';
$id = $_GET['id'] ?? 0;
$id = intval($id);

/* Liste des informations JSON à renvoyer
 *  - status : true ou false => indique si la requête SQL a fonctionné
 *  - action : l'action qui a été exécutée
 *  - messages : une liste de messages de confirmation ou d'erreur
 *  - image : un objet Image (readOne ou create)
 *  - images : un tableau d'objets Images (readAll)
 */
// Initialisation des informations
$data = [
	'status' => true,
	'action' => $action,
	'messages' => [],
	'image' => null,
	'images' => []
];

switch ($page) {
	case 'article':
		switch ($action) {
			case 'read':
				if ($id > 0) {
					$image = AjaxImage::readOne($id);
					$data['messages'] = ['Récupération de l\'image n°'.$id];
					$data['image'] = $image;
				}
				else {
					$images = AjaxImage::readAll();
					$data['messages'] = ['Récupération de toutes les images'];
					$data['images'] = $images;
				}
				break;

			case 'create':
				$image = new AjaxImage();
				// Si le formulaire article a été rempli
				if (isset($_POST['form_image'])) {
					$data['messages'] = $image->loadPOST();
					if (empty($data['messages'])) {
						$image->create();
						$messages = ['Image insérée'];
						$data['image'] = $image;
					}
					else {
						$data['status'] = false;
					}
				}
				else {
					$data['status'] = false;
					$data['messages'] = ['Formulaire non rempli'];
				}
				break;

			case 'delete':
				$image = AjaxImage::readOne($id);
				$image->delete();
				$data['messages'] = ['Suppression de l\'image n°'.$id];
				$data['image'] = $image;
				break;
		}
		break;

	default:
		$data = [];
}

echo json_encode($data);
