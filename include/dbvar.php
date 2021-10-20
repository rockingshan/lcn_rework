<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
require_once 'db.class.php';
DB::$user = 'md_dbuser';
DB::$password = 'm#gh@c@s';
DB::$dbName = 'lcn_auth_db';