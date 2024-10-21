<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

check_access(ROLE_STAFF); // Allow both staff and admin to access inventory

// ... (rest of the file remains the same)
?>