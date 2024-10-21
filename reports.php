<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

check_access(ROLE_ADMIN); // Only allow admin to access reports

// ... (rest of the file remains the same)
?>