<?php
require 'db.php';

// Handle add module
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_module'])) {
    $module_name = trim($_POST['module_name']);
    $trade_id = intval($_POST['trade_id']);

    if ($module_name && $trade_id) {
        $stmt = $conn->prepare("INSERT INTO Modules (Module_Name, Trade_Id) VALUES (?, ?)");
        $stmt->bind_param("si", $module_name, $trade_id);
        $stmt->execute();
        header("Location: modules.php");
        exit;
    } else {
        $error = "Module name and trade must be provided.";
    }
}

// Fetch all modules with their trade name
$sql = "SELECT m.Module_Id, m.Module_Name, t.Trade_Name 
        FROM Modules m 
        LEFT JOIN Trades t ON m.Trade_Id = t.Trade_Id
        ORDER BY m.Module_Name";
$result = $conn->query($sql);

// Fetch trades for dropdown
$trades = $conn->query("SELECT Trade_Id, Trade_Name FROM Trades ORDER BY Trade_Name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modules Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Modules</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <input type="text" name="module_name" class="form-control" placeholder="Module Name" required>
            </div>
            <div class="col-auto">
                <select name="trade_id" class="form-select" required>
                    <option value="">Select Trade</option>
                    <?php while ($trade = $trades->fetch_assoc()): ?>
                        <option value="<?= $trade['Trade_Id'] ?>"><?= htmlspecialchars($trade['Trade_Name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-auto">
                <button name="add_module" class="btn btn-primary" type="submit">Add Module</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Module ID</th>
                <th>Module Name</th>
                <th>Trade Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['Module_Id'] ?></td>
                <td><?= htmlspecialchars($row['Module_Name']) ?></td>
                <td><?= htmlspecialchars($row['Trade_Name']) ?></td>
                <td>
                    <a href="module_edit.php?id=<?= $row['Module_Id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="module_delete.php?id=<?= $row['Module_Id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this module?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
