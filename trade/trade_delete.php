<?php
require 'db.php';

$id = $_GET['id'] ?? 0;
if ($id) {
    $stmt = $conn->prepare("DELETE FROM Trades WHERE Trade_Id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header("Location: trades.php");
exit;
