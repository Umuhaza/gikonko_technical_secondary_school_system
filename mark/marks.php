<?php
require 'db.php';

// Handle Add Mark
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_mark'])) {
    $trainee_id = intval($_POST['trainee_id']);
    $module_id = intval($_POST['module_id']);
    $formative = intval($_POST['formative']);
    $summative = intval($_POST['summative']);

    if ($formative >= 0 && $formative <= 50 && $summative >= 0 && $summative <= 50) {
        $total = $formative + $summative;
        $stmt = $conn->prepare("INSERT INTO Marks (Trainee_Id, Module_Id, Formative_Assessment, Summative_Assessment, Total_Marks) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiii", $trainee_id, $module_id, $formative, $summative, $total);
        $stmt->execute();
        header("Location: marks.php");
        exit;
    } else {
        $error = "Marks must be between 0 and 50.";
    }
}

// Fetch marks with trainee/module info
$query = "SELECT m.Mark_Id, t.FirstNames, t.LastName, mo.Module_Name,
          m.Formative_Assessment, m.Summative_Assessment, m.Total_Marks
          FROM Marks m
          JOIN Trainees t ON m.Trainee_Id = t.Trainee_Id
          JOIN Modules mo ON m.Module_Id = mo.Module_Id
          ORDER BY t.LastName";
$marks = $conn->query($query);

// Dropdowns
$trainees = $conn->query("SELECT Trainee_Id, FirstNames, LastName FROM Trainees ORDER BY LastName");
$modules = $conn->query("SELECT Module_Id, Module_Name FROM Modules ORDER BY Module_Name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marks Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
<div class="max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Manage Marks</h2>

    <?php if (isset($error)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-300 rounded">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 bg-white p-6 rounded-lg shadow">
        <select name="trainee_id" class="p-2 border rounded" required>
            <option value="">Select Trainee</option>
            <?php while ($t = $trainees->fetch_assoc()): ?>
                <option value="<?= $t['Trainee_Id'] ?>"><?= htmlspecialchars($t['FirstNames'] . ' ' . $t['LastName']) ?></option>
            <?php endwhile; ?>
        </select>

        <select name="module_id" class="p-2 border rounded" required>
            <option value="">Select Module</option>
            <?php while ($m = $modules->fetch_assoc()): ?>
                <option value="<?= $m['Module_Id'] ?>"><?= htmlspecialchars($m['Module_Name']) ?></option>
            <?php endwhile; ?>
        </select>

        <input type="number" name="formative" placeholder="Formative (/50)" class="p-2 border rounded" required>
        <input type="number" name="summative" placeholder="Summative (/50)" class="p-2 border rounded" required>

        <button name="add_mark" class="bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">Add Mark</button>
    </form>

    <div class="overflow-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-200 text-gray-800 text-sm">
                <tr>
                    <th class="p-3">Trainee</th>
                    <th class="p-3">Module</th>
                    <th class="p-3">Formative</th>
                    <th class="p-3">Summative</th>
                    <th class="p-3">Total</th>
                    <th class="p-3">Result</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $marks->fetch_assoc()): ?>
                    <tr class="border-t">
                        <td class="p-3"><?= htmlspecialchars($row['FirstNames'] . ' ' . $row['LastName']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($row['Module_Name']) ?></td>
                        <td class="p-3"><?= $row['Formative_Assessment'] ?></td>
                        <td class="p-3"><?= $row['Summative_Assessment'] ?></td>
                        <td class="p-3 font-semibold"><?= $row['Total_Marks'] ?></td>
                        <td class="p-3">
                            <?php if ($row['Total_Marks'] >= 70): ?>
                                <span class="text-green-600 font-medium">Competent</span>
                            <?php else: ?>
                                <span class="text-red-600 font-medium">NYC</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-3 space-x-2">
                            <a href="mark_edit.php?id=<?= $row['Mark_Id'] ?>" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-sm">Edit</a>
                            <a href="mark_delete.php?id=<?= $row['Mark_Id'] ?>" class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm" onclick="return confirm('Delete this mark?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
