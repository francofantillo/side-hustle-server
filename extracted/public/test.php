<?php
// Simple debug script to verify PHP execution
echo "PHP is working correctly!";
echo "<br><br>";
echo "PHP version: " . phpversion();
echo "<br><br>";
echo "Server variables:<pre>";
print_r($_SERVER);
echo "</pre>";
?>