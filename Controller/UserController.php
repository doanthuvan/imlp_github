<?php
class UserController
{
    public function __construct()
    {
        if(!isset($_GET['page'])) {
            header("Location: http://localhost/github_project?page=dashboard");
        }
        if($_GET['page'] == 'login') {
            include_once('View/login.php');
            return;
        }
        if(!isset($_SESSION['access_token'])) {
            if(isset($_GET['code'])) {
                $this->login();
            } else {
                header("Location: http://localhost/github_project?page=login");
            }
        }
    }

    public function login()
    {
        $curl = curl_init();
        $data = [
            'client_id' => Client_ID,
            'client_secret' => Client_Secret,
            'code' => $_GET['code'],
            'redirect_uri' => 'http://localhost/github_project/dashboard'
        ];
        $options =array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://github.com/login/oauth/access_token',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data
        );
        curl_setopt_array($curl, $options);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/json'
            )
        );
        $result = curl_exec($curl);
        $result = json_decode($result, true);
        if($result['access_token']) {
            $_SESSION['access_token'] = $result['access_token'];
            header("Location: http://localhost/github_project?page=dashboard");
        } else {
            echo "Not login";
        }
    }
}
$UserController = new UserController();

?>