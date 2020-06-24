<?php
namespace Model;
include_once('BaseModel.php');
include_once('env.php');

class Repo extends BaseModel
{
    private $table = 'repos';

    public function __construct()
    {
        parent::__construct();
    }

    public function store($id, $name, $clone_url, $owner) 
    {
        $sql = "SELECT * FROM $this->table where id = $id";
        $row_count = mysqli_num_rows(mysqli_query($this->conn, $sql));
        echo $row_count;
        if($row_count > 0) return true;
        $sql = "INSERT INTO $this->table values ($id, '$name', '$clone_url', '$owner')";
        if(mysqli_query($this->conn, $sql)) {
            return true;
        }
        return false;
    }

    public function index()
    {
        $sql = "SELECT * FROM $this->table";
        $repos = mysqli_query($this->conn, $sql);

        $sql = "SELECT * FROM forks WHERE idUser = $_SESSION[idUser]";
        $dataForkRepos = mysqli_query($this->conn, $sql);
        $forkRepos = [];
        if (mysqli_num_rows($dataForkRepos) > 0) {
            while($row = mysqli_fetch_assoc($dataForkRepos)) {
                $forkRepos[$row['idRepo']] = $row['forkUrl'];
            }
        }
        return [
            'repos' => $repos,
            'forkRepos' => $forkRepos
        ];
    }

    public function fork($entry)
    {
        $entry = str_replace('{', '', $entry);
        $entry = str_replace('}', '', $entry);
        $entry = explode(",", $entry);
        $data = [];
        for($i = 0;$i< count($entry); $i++) {
            $item = explode(":", $entry[$i]);
            $data[$item[0]] = $item[1];
        }
        $this->conn = mysqli_connect(ServerName, UserName, Password, DB);
        $idUser = $data['idUser'];

        $sql = "SELECT * FROM forks where idRepo = $data[idRepo] and idUser = $idUser";

        $row_count = mysqli_num_rows(mysqli_query($this->conn, $sql));
        if($row_count > 0) return;
        $curl = curl_init();
        $options =array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://api.github.com/repos/$data[owner]/$data[nameRepo]/forks?access_token=". $data['access_token'],
            CURLOPT_POST => true
        );
        curl_setopt_array($curl, $options);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/vnd.github.v3+json',
            "Content-Type: text/plain",
            "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 YaBrowser/16.3.0.7146 Yowser/2.5 Safari/537.36"
            )
        );
        $result = curl_exec($curl);
        if($result) {
            $forkUrl = "https://github.com/$data[nameUser]/$data[nameRepo]";
            $sql = "INSERT INTO forks values ($idUser,  $data[idRepo], '$forkUrl')";
            if(mysqli_query($this->conn, $sql)) {
                \sleep(2);
                return $forkUrl;
            }
            return;
        }
        return;
    }
}
