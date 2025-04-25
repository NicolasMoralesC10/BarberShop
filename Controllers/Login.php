<?php

class Login extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (isset($_SESSION['login'])) {
            header('Location: ' . base_url() . '/dashboard');
        }
    }
    public function login()
    {
        $data['page_title'] = "Página de Barberia";
        $data['page_name'] = "login";
        $data['script'] = "login";
        $this->views->getView($this, "login", $data);
    }

    public function loginUser()
    {
        if ($_POST) {
            $arrPost = ['txtNombre', 'txtPassword'];
            if (!check_post($arrPost)) {
                $arrResponse = array('status' => false, 'msg' => 'Error de datos');
            } else {
                $strUsuario = strClean($_POST['txtNombre']);
                $strPassword = hash("SHA256", $_POST['txtPassword']);
                $requestUser = $this->model->loginUser($strUsuario, $strPassword);
                if (empty($requestUser)) {
                    $arrResponse = array('status' => false, 'msg' => 'El usuario o la contraseña es incorrecto');
                } else {
                    $arrData = $requestUser;
                    if ($arrData['status'] == 1) {
                        $_SESSION['id'] = $arrData['id'];
                        $_SESSION['login'] = true;

                        $arrData = $this->model->sessionLogin($_SESSION['id']);
                        $_SESSION['userData'] = $arrData;

                        $arrResponse = array('status' => true, 'msg' => 'ok');
                    } else {
                        $arrResponse = array('status' => false, 'msg' => 'Usuario inactivo');
                    }
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
