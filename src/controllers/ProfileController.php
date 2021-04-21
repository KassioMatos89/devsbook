<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class ProfileController extends Controller {
    private $loggedUser;

    public function __construct() {
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false) {
            $this->redirect('/login');
        }
    }

    public function index($atts = []) {
        $page = intval(filter_input(INPUT_GET, 'page'));

        // Detectando o usuário acessado
        $id = $this->loggedUser->id;
        if(!empty($atts['id'])) {
            $id = $atts['id'];
        }

        // Pegando informações do usuário
        $user = UserHandler::getUser($id, true);
        if(!$user) {
            $this->redirect('/');
        }

        $dateFrom = new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');
        $user->ageYears = $dateFrom->diff($dateTo)->y;

        // Pegando o feed do usuário
        $feed = PostHandler::getUserFeed(
            $id, 
            $page, 
            $this->loggedUser->id
        );

        // Verificar se EU sigo o usuário
        $isFollowing = false;
        if ($user->id != $this->loggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);

        }

        $this->render('profile', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'feed' => $feed,
            'isFollowing' => $isFollowing
        ]);
    }

    public function follow($atts) {
        $to = intval($atts['id']);

        if(UserHandler::idExists($to)) {

            if(UserHandler::isFollowing($this->loggedUser->id, $to)) {
                // Se ja segue --> Deixar de seguir
                UserHandler::unfollow($this->loggedUser->id, $to);
            } else {
                // Senão --> Seguir
                UserHandler::follow($this->loggedUser->id, $to);
            }

        }

        $this->redirect('/perfil/'.$to);
    }

    public function friends($atts = []) {
        // Detectando o usuário acessado
        $id = $this->loggedUser->id;
        if(!empty($atts['id'])) {
            $id = $atts['id'];
        }

        // Pegando informações do usuário
        $user = UserHandler::getUser($id, true);
        if(!$user) {
            $this->redirect('/');
        }

        $dateFrom = new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');
        $user->ageYears = $dateFrom->diff($dateTo)->y;

        // Verificar se EU sigo o usuário
        $isFollowing = false;
        if($user->id != $this->çpggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile_friends', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'isFollowing' => $isFollowing
        ]);
    }

    public function photos($atts = []) {
        // Detectando o usuário acessado
        $id = $this->loggedUser->id;
        if(!empty($atts['id'])) {
            $id = $atts['id'];
        }

        // Pegando informações do usuário
        $user = UserHandler::getUser($id, true);
        if(!$user) {
            $this->redirect('/');
        }

        $dateFrom = new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');
        $user->ageYears = $dateFrom->diff($dateTo)->y;

        // Verifica se EU sigo o usuário
        $isFollowing = false;
        if($user->id != $this->loggedUser->id) {
            $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile_photos', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'isFollowing' => $isFollowing
        ]);
    }

    public function config($atts = []) {

        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        // Pegando id usuário logado
        $id = $this->loggedUser->id;

        // Retornando dados do usuário
        $user = UserHandler::getUser($id);
        if(!$user) {
            $this->redirect('/');
        }

        $this->render('profile_config', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'flash' => $flash
        ]);
    }

    public function updateProfile() {
        $id = filter_input(INPUT_POST, 'id');
        $name = filter_input(INPUT_POST, 'name');
        $birthdate = filter_input(INPUT_POST, 'birthdate');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $city = filter_input(INPUT_POST, 'city');
        $work = filter_input(INPUT_POST, 'work');
        $password = filter_input(INPUT_POST, 'password');
        $repeatPassword = filter_input(INPUT_POST, 'repeat-password');

        if($id != $this->loggedUser->id) {
            return $this->redirect('/config');
        }
            
        if ($id && $name && $birthdate && $email) {

            // Validando se a data esta em um formato valido com DD, MM, YYYY            
            $birthdate = explode('/', $birthdate);
            if(count($birthdate) != 3) {
                $_SESSION['flash'] = 'Data de nascimento inválida!';
                $this->redirect('/config');
            }
            
            // Valida se email digitado é valido
            $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];
            if (strtotime($birthdate) === false) {
                $_SESSION['flash'] = 'Data de nascimento INVÁLIDA!';
                $this->redirect('/config');
            }

            // Valida se senha confere com repetição da senha
            if($password != $repeatPassword) {
                $_SESSION['flash'] = 'Senha e Repetição de senha NÃO conferem';
                return $this->redirect('/config');
            }

            // Realiza a atualização dos campos passados.
        UserHandler::updateProfile($id, $name, $birthdate, $email, $city, $work /*, $password */);
            
            // Ajustar mensagem de sucesso
            //$_SESSION['flash'] = 'Dados atualizados com sucesso';
            return $this->redirect('/config');

        } else {
            $_SESSION['flash'] = 'Preencha todos os dados obrigatórios!';
            return $this->redirect('/config');
        }
    }
}