<?php
require_once 'config.php';
require_once 'functions.php';

function run_tests() {
    $tests = [
        'test_validate_input',
        'test_add_product',
        // Add more test functions here
    ];
    
    $passed = 0;
    $failed = 0;
    
    foreach ($tests as $test) {
        echo "Running $test... ";
        if ($test()) {
            echo "PASSED\n";
            $passed++;
        } else {
            echo "FAILED\n";
            $failed++;
        }
    }
    
    echo "\nTest Results: $passed passed, $failed failed\n";
}

function test_validate_input() {
    $data = ['email' => 'invalid_email'];
    $rules = ['email' => 'required|email'];
    $errors = validate_input($data, $rules);
    return isset($errors['email']) && $errors['email'] === "Invalid email format";
}

function test_add_product() {
    // Implement test for add_product function
    return true; // Placeholder
}

run_tests();
?>