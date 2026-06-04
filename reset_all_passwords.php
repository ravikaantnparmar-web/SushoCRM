<?php
require 'config/db.php';

$hashed = password_hash('password123', PASSWORD_DEFAULT);

// Update ALL users
$sql = "UPDATE users SET password = ?";

$stmt = db()->prepare($sql);
if ($stmt->execute([$hashed])) {
    echo "Successfully reset all " . $stmt->rowCount() . " user passwords to 'password123'.";
} else {
    echo "Error updating passwords.";
}
