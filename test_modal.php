<?php session_start(); $_SESSION['user_id']=6; $_SESSION['role']='practitioner'; $_GET['url']='doctor/dashboard'; $_SERVER['REQUEST_METHOD']='GET'; require 'public/index.php';
