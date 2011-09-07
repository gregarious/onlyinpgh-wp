<?php
    if(!isset($_SESSION)) { @session_start(); }
    $_SESSION['qpw_visibility'] = $_POST['visibility'];
?>