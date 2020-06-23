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
    <h2>Danh sách Repos người dùng github</h2>
    <form action="" method='GET'>
    <input type="text" placeholder="Nhập tên người dùng github" name='username' id='username'>
    <input type="button" value="Tìm kiếm" name='submit' id="submit">
    </form>
    <div id="countRepo"></div><br>
    <table style="width: 100%;" id='repoUserTable'>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Stargazers</th>
            <th>Action</th>
        </tr>
    </table>
    <br>
    <button id='loadMore'>loadMore</button>
    <script>
    let table = document.getElementById("repoUserTable")
    function clone(row) {
        let data = {
            action: 'clone',
            id: row.getAttribute("data-id"),
            name: row.getAttribute("data-name"),
            clone_url: row.getAttribute("data-clone_url"),
            owner: row.getAttribute("data-owner")
        }
        $.ajax({
            url: 'http://localhost/github_project/?page=reposByUser',
            type: 'POST',
            dataType: 'html',
            data: data
        }).done(function(response) {
            alert('Clone')
        }).fail(function() {
            alert("Not clone")
        });
    }
    $(function() {
        let data = null
        $('#loadMore').hide()

        function countRepo() {
            let value = (table.rows.length - 1) + '/' + data.length
            $('#countRepo').html(value)
        }

        function loadTable() {
            let rowLength = table.rows.length - 1;
            let count = 0;
            for(let i = rowLength; i < data.length; i++) {
                count++
                let row = table.insertRow(-1)
                let cell1 = row.insertCell(0)
                let cell2 = row.insertCell(1)
                let cell3 = row.insertCell(2)
                let cell4 = row.insertCell(3)
                cell1.innerHTML = data[i]['id']
                cell2.innerHTML = data[i]['name']
                cell3.innerHTML = data[i]['stargazers_count']
                cell4.innerHTML = "<button onclick='clone(this)' data-id='" + data[i]['id'] 
                    + "' data-name='" + data[i]['name'] 
                    + "' data-clone_url='" + data[i]['clone_url'] 
                    + "' data-owner='" + data[i]['owner']['login'] + "'>clone</button>"
                if(count == 30) {
                    break;
                }
            }
            if(data.length == table.rows.length - 1) {
                $('#loadMore').hide()
            } else {
                $('#loadMore').show()
            }
            countRepo()
        }

        $("#loadMore").click(function() {
            if(data == null) {
                return
            }
            loadTable()
        })

        $("#submit").click(function(event) {
            event.preventDefault();
            $.ajax({
                url: 'https://api.github.com/users/' + $("#username").val() +'/repos',
                type: 'GET',
                dataType: 'json',
                accepts:"application/vnd.github.nebula-preview+json"
            }).done(function(response) {
                data = response
                let rowLength = table.rows.length;
                let count = 0;
                for(let i = 1; i < rowLength; i++) {
                    table.deleteRow(1)
                }
                loadTable()
            }).fail(function() {
                alert("Not found")
            });
        });


    });
    </script>
</body>
</html>