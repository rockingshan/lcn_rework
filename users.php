<?php
require './include/dbvar.php';
if (!isset($_SESSION["user"])) {
    header("location:login.html");
}
if (isset($_POST["sec_key"])) {
    DB::useDB('lcn_auth_db');
    DB::insert('lcn_auth_tb', [
        'user' => $_POST['user'],
        'pass' => md5($_POST['password']),
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'default_city' => $_POST['default_city'],
        'role' => 'hdnd_manager'
    ]);
    if (DB::insertId()) {
        $success = TRUE;
    }
}
DB::useDB('lcn_auth_db');
$result = DB::query("SELECT * FROM lcn_auth_tb");
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
    <title>Add Users</title>
</head>

<body>
    <div class="ui small menu">
        <a class="active item" href="index.php">
            Meghbela LCN
        </a>
        <a class="item">
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
            <div class="item">
                <div class="ui button"><a href="./process/logout.php">Log Out</a></div>
            </div>
        </div>
    </div>
    <?php if ($success) {
        echo '<div class="ui success message compact huge"><i class="close icon"></i><div class="header">User creation was successful.</div></div>';
    }
    ?>
    <div class="add_form">
        <form class="ui form" action="users.php" method="POST">
            <h4 class="ui dividing header">Add/Modify Users</h4>
            <div class="field">
                <label>Username</label>
                <input type="text" name="user" placeholder="Username">
                
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password">
            </div>
            <div class="field">
                <label>Name</label>
                <div class="two fields">
                    <div class="field">
                        <input type="text" name="first_name" placeholder="First name">
                    </div>
                    <div class="field">
                        <input type="text" name="last_name" placeholder="Last Name">
                    </div>
                </div>
            </div>
            <div class="field">
                <label>Email</label>
                <input type="text" name="email" placeholder="Email">
            </div>
            <div class="field">
                <label>Default City</label>
                <div class="ui selection dropdown">
                    <input type="hidden" name="default_city">
                    <i class="dropdown icon"></i>
                    <div class="default text">Default City</div>
                    <div class="menu">
                        <div class="item" data-value="1">Kolkata</div>
                        <div class="item" data-value="2">Bankura</div>
                        <div class="item" data-value="3">Berhampore</div>
                        <div class="item" data-value="4">Haldia</div>
                        <div class="item" data-value="5">Chandipur</div>
                        <div class="item" data-value="6">Dinhata</div>
                    </div>
                </div>
            </div>
            <button class="ui button" type="submit">Submit</button>
            <input type="hidden" name="sec_key" value="3ewasd23eqe098aildjo8udljd" />
            <div class="ui error message"></div>
        </form>
    </div>

    <script src="./include/jquery-3.6.0.min.js"></script>
    <script src="./include/semantic.min.js"></script>
    <script>
        $('.message .close')
            .on('click', function() {
                $(this)
                    .closest('.message')
                    .transition('fade');
            });
        $('.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'user',
                        rules: [{
                            type: 'empty',
                            prompt: 'Please enter username'
                        }]
                    },
                    fname: {
                        identifier: 'first_name',
                        rules: [{
                            type: 'empty',
                            prompt: 'Please enter First Name'
                        }]
                    },
                    password: {
                        identifier: 'password',
                        rules: [{
                            type: 'empty',
                            prompt: 'Please enter a password'
                        }]
                    },
                    def_city: {
                        identifier: 'default_city',
                        rules: [{
                            type: 'empty',
                            prompt: 'Must select default City'
                        }]
                    },
                }
            });
    </script>
</body>

</html>