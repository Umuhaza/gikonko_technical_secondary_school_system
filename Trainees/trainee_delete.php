<?php
require 'db.php';

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $stmt = $conn->prepare("DELETE FROM Trainees WHERE Trainee_Id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header("Location: trainees.php");
exit;
