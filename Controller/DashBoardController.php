<?php
class DashBoardController
{
    public function __construct()
    {
        if(!isset($_SESSION['access_token'])) {
            return;
        }
        if(isset(($_GET['page']))) {
            if($_GET['page'] == 'dashboard') {
                $curl = curl_init();
                $options =array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => 'https://api.github.com/user'
                );
                curl_setopt_array($curl, $options);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    "Accept: application/vnd.github.v3+json",
                    "Content-Type: text/plain",
                    "Authorization: token " .$_SESSION['access_token'],
                    "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 YaBrowser/16.3.0.7146 Yowser/2.5 Safari/537.36"
                    )
                );
                $result = curl_exec($curl);
                $dataUser = json_decode($result, true);
                $_SESSION['idUser'] = $dataUser['id'];
                $_SESSION['nameUser'] = $dataUser['login'];
                include_once('View/dashboard.php');
            }
        }
    }
}
$dashBoardcontroller = new DashBoardController();

?>