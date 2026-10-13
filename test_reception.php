<?php session_start(); $_SESSION['user_id']=8; $_SESSION['role']='receptionist'; $_GET['url']='reception/dashboard'; $_SERVER['REQUEST_METHOD']='GET'; require 'public/index.php';
