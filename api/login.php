<?php
session_start();
include '../db.php';

header('Content-Type: application/json');

// Get raw POST JSON data
$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['email']) && isset($data['password'])) {

    $email = $data['email'];
    $password = $data['password'];

    $stmt = $conn->prepare("SELECT user_id, name, password_hash, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $name, $password_hash, $role);
        $stmt->fetch();

        // For SHA2 stored password
        if(hash_equals($password_hash, hash('sha256', $password))) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;

            echo json_encode([
                "success" => true,
                "message" => "Login successful! Welcome, $name.",
                "status" => 200

            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Invalid password!",
                "status" => 401            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "No user found with that email!",
            "status" => 404
        ]);
    }

    $stmt->close();
}

$conn->close();
?>
