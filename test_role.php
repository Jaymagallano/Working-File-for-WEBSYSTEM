<?php
// Quick test script to check session data
session_start();

echo "<h1>Session Data Test</h1>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['role'])) {
    echo "<h2>Detected Role: " . $_SESSION['role'] . "</h2>";
} else {
    echo "<h2 style='color: red;'>No Role Found in Session!</h2>";
}

echo "<hr>";
echo "<a href='login'>Go to Login</a> | ";
echo "<a href='dashboard'>Go to Dashboard</a> | ";
echo "<a href='logout'>Logout</a>";
?>
