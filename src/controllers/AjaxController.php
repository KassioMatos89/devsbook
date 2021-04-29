<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

Class AjaxController extends Controller {

    private $loggedUser;

    public function __construct() {
        $this->loggedUser = UserHandler::checkLogin();

        if ($this->loggedUser === false) {
            header("Content-Type: application/json");
            echo json_decode(['error' => 'Usuário não logado']);
            exit;
        }
    }

    public function like($atts) {
        $id = $atts['id'];

        if(PostHandler::isLiked($id, $this->loggedUser->id)) {
            // delete no like
            PostHandler::deleteLike($id, $this->loggedUser->id);
        } else {
            // inserir um like
            PostHandler::addLike($id, $this->loggedUser->id);
        }
    }

}