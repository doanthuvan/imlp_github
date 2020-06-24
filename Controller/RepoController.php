<?php
include_once('Model/Repo.php');
use Model\Repo;
class RepoController
{
    public function __construct()
    {
        if(!isset($_SESSION['access_token'])) {
            return;
        }
        if(!isset($_GET['page'])) {
            header("Location: http://localhost/github_project?page=dashboard");
        }
        if($_GET['page'] == 'reposByUser') {
            if(isset($_POST['action'])) {
                return $this->store($_POST['id'], $_POST['name'], $_POST['clone_url'], $_POST['owner']);
            }
            else {
                include_once('View/repoUserList.php');
            }
        }
        if($_GET['page'] == 'reposSave') {
            if(isset($_POST['action'])) {
                $idUser = $_SESSION['idUser'];
                $entryData = json_encode(array(
                    'idRepo' => $_POST['idRepo'],
                    'nameRepo' =>  $_POST['nameRepo'],
                    'owner' => $_POST['owner'],
                    'idUser' => $_SESSION['idUser'],
                    'nameUser' => $_SESSION['nameUser'],
                    'access_token' => $_SESSION['access_token']
                ));
                shell_exec("php Notification/push.php ".$entryData);
                exit;
            }
            else {
                $repo = new Repo();
                $dataRepoSave = $repo->index();
                include_once('View/repoSaveList.php');
            }
            
        }
    }
    public function store($id, $name, $clone_url, $owner)
    {
        $repo = new Repo();
        return $repo->store($id, $name, $clone_url, $owner);
    }
}
$repoController = new RepoController();

?>