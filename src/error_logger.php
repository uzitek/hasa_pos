<?php
function log_error($message, $severity = 'ERROR') {
    $log_file = __DIR__ . '/../logs/error_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$severity] $message\n";
    error_log($log_entry, 3, $log_file);
}
?>