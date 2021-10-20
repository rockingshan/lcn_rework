<?php
require 'vendor/autoload.php';
require './include/dbvar.php';
if (isset($_SESSION["user"])) {
    if (isset($_GET['city'])) {
        $city = $_GET['city'];
        DB::useDB('lcn_auth_db');
        $city_d = DB::queryFirstRow("SELECT * FROM city_tb WHERE city_name=%s", $city);
        $city_db = $city_d['db_name'];
    } elseif (isset($_SESSION["city"])) {
        $city = $_SESSION["city"];
        DB::useDB('lcn_auth_db');
        $city_d = DB::queryFirstRow("SELECT * FROM city_tb WHERE city_name=%s", $city);
        $city_db = $city_d['db_name'];
    }
    DB::useDB($city_db);
    $result = DB::query("SELECT *,RANK() OVER(PARTITION BY lcn_tb.genre ORDER BY lcn_tb.lcn ASC) AS 'rank' FROM channel_tb,lcn_tb,sid_tb WHERE channel_tb.lcn=lcn_tb.lcn AND channel_tb.sid=sid_tb.sid ORDER BY lcn_tb.lcn");
} else {
    header("location:login.html");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/themes/semantic/bootstrap-table-semantic.min.css">
    <link rel="stylesheet" href="./style/semantic.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="./style/index.css">


    <title>Meghbela LCN <?php echo $city; ?></title>
</head>

<body>
    <div class="ui small menu">
        <a class="active item">
            Meghbela LCN <?php echo $city; ?>
        </a>
        <a class="item" href="./downlaod.php?city=<?php echo $city; ?>">
            Download LCN
        </a>
        <a class="item">
            Add Channel
        </a>
        <a class="item">
            Descriptor Data
        </a>
        <div class="right menu">
            <a class="item" href="users.php">
                Users
            </a>
            <div class="ui dropdown item">
                Change City <i class="dropdown icon"></i>
                <div class="menu">
                    <a class="item" href="index.php?city=Kolkata">Kolkata</a>
                    <a class="item" href="index.php?city=Bankura">Bankura</a>
                    <a class="item" href="index.php?city=Haldia">Haldia</a>
                    <a class="item" href="index.php?city=Chandipur">Chandipur</a>
                    <a class="item" href="index.php?city=Berhampore">Berhampore</a>
                    <a class="item" href="index.php?city=Dinhata">Dinhata</a>
                </div>
            </div>
            <div class="item">
                <div class="ui button"><a href="./process/logout.php">Log Out</a></div>
            </div>
        </div>
    </div>

    <div class="lcndata">
        <table class="ui striped table" data-toggle="table" id="table" data-sortable="true" data-pagination="true" data-search="true" data-trim-on-search="false">
            <thead>
                <th data-field="genre" data-width="200">Genre</th>
                <th data-field="lcn" data-width="100" data-sortable="true">LCN</th>
                <th data-field="rank" data-width="150">Rank in Genere</th>
                <th data-field="channel" data-width="200">Channel Name</th>
                <th data-field="br" data-width="200">Broadcater</th>
                <th data-width="50">Edit</th>
                <th data-width="50">Swap</th>
                <th data-width="50">Delete</th>
            </thead>
            <tbody>
                <?php
                foreach ($result as $row) {
                    echo '<tr><td>' . $row['genre'] . '</td>
                    <td>' . $row['lcn'] . '</td>
                    <td>' . $row['rank'] . '</td>
                    <td>' . $row['channel'] . '<div class="right floated"><a href="./process/lcnnameedit.php?sid=' . $row['sid'] . '"><i class="fa large fa-pen"></i></a></div></td>
                    <td>' . $row['broadcaster'] . '</td>
                    <td><div class="ui modal><a href="./process/lcnedit.php?sid=' . $row['sid'] . '"><i class="fa large fa-edit"></i></a></div></td>
                    <td><a href="./process/lcnswap.php?sid=' . $row['sid'] . '"><i class="fa large fa-retweet susp-teal"></i></a</td>
                    <td><a href="./process/channeldelete.php?sid=' . $row['sid'] . '"><i class="fa large fa-trash danger-red"></i></a</td></tr>';
                }
                ?>
            </tbody>
        </table>

    </div>

    <script src="./include/jquery-3.6.0.min.js"></script>
    <script src="./include/semantic.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/themes/semantic/bootstrap-table-semantic.min.js"></script>
    <script>
        $('.ui.dropdown').dropdown();
        $('.ui.modal')
            .modal('show');
    </script>
</body>

</html>