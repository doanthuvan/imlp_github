<?php
include_once('header.php');
?>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>
</head>
<body>
    <h2>Danh sách Repos đã lưu</h2>
    <table style="width: 100%;" id='repoSaveTable'>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>clone_url</th>
            <th>owner</th>
            <th>action</th>
        </tr>
        <?php 
        $dataRepos = $dataRepoSave['repos'];
        $dataForkRepos = $dataRepoSave['forkRepos'];
        $count = 0;
        if (mysqli_num_rows($dataRepos) > 0) {
            while($row = mysqli_fetch_assoc($dataRepos)) {
                $count++;
                $data = "<tr><td>$row[id]</td>
                    <td>$row[name]</td>
                    <td>$row[clone_url]</td>
                    <td>$row[owner]</td>";
                    if (array_key_exists($row['id'], $dataForkRepos)) {
                        $data .= "<td><a target='blank' href='" .$dataForkRepos[$row['id']] . "'>link</a></td>";
                    }
                    else {
                        $data .= "<td><button onclick='fork(this)' data-index='$count' data-idRepo='$row[id]' data-repo='$row[name]' data-owner='$row[owner]'>fork</button></td>";
                    }
                $data .= "</tr>"; 
                echo $data;
            }
        }
        ?>
    </table>
    <script>
    let table = document.getElementById("repoSaveTable")
    function fork(row) {
        let data = {
            action: 'fork',
            idRepo: row.getAttribute("data-idRepo"),
            nameRepo: row.getAttribute("data-repo"),
            owner: row.getAttribute("data-owner")
        }
        $.ajax({
            url: 'http://localhost/github_project/?page=reposSave',
            type: 'POST',
            dataType: 'html',
            data: data
        }).done(function(response) {
            if(response) {
                let mess = "<p>Your fork is done and this is your new fork repo: <a target='blank' href=" + response + ">URL</a></p>";
                conn.send(mess)
                $("#notification").append(mess)
                table.rows[row.getAttribute("data-index")].cells[4].outerHTML = "<a target='blank' href='" + response + "'>link</a>";
            }
        }).fail(function() {
            alert("Not fork")
        });
    }
    </script>
</body>
</html>