<?php
require 'db.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header("Location: modules.php");
    exit;
}

// Fetch module data
$stmt = $conn->prepare("SELECT Module_Id, Module_Name, Trade_Id FROM Modules WHERE Module_Id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$module = $result->fetch_assoc();

if (!$module) {
    header("Location: modules.php");
    exit;
}

// Fetch trades for dropdown
$trades = $conn->query("SELECT Trade_Id, Trade_Name FROM Trades ORDER BY Trade_Name");

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_module'])) {
    $module_name = trim($_POST['module_name']);
    $trade_id = intval($_POST['trade_id']);

    if ($module_name && $trade_id) {
        $stmt = $conn->prepare("UPDATE Modules SET Module_Name = ?, Trade_Id = ? WHERE Module_Id = ?");
        $stmt->bind_param('sii', $module_name, $trade_id, $id);
        $stmt->execute();
        header("Location: modules.php");
        exit;
    } else {
        $error = "Module name and trade must be provided.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Module</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <a href="home.php" style="
    display: inline-block;
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 600;
    transition: background-color 0.3s ease;
">Back</a>
<div class="container mt-4">
    <h2>Edit Module</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Module Name</label>
            <input type="text" name="module_name" class="form-control" required value="<?= htmlspecialchars($module['Module_Name']) ?>">
        </div>
        <div class="mb-3">
            <label>Trade</label>
            <select name="trade_id" class="form-select" required>
                <option value="">Select Trade</option>
                <?php while ($trade = $trades->fetch_assoc()): ?>
                    <option value="<?= $trade['Trade_Id'] ?>" <?= $trade['Trade_Id'] == $module['Trade_Id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($trade['Trade_Name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button name="update_module" class="btn btn-success" type="submit">Update Module</button>
        <a href="modules.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
