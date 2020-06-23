<?php
include_once('Model/Repo.php');
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
                echo $this->fork($_POST['idRepo'], $_POST['nameRepo'], $_POST['owner']);
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
    
    public function fork($idRepo, $nameRepo, $owner)
    {
        $repo = new Repo();
        return $repo->fork($idRepo, $nameRepo, $owner);
    }
}
$repoController = new RepoController();

?>