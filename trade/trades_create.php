<?php include 'db.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $trade_name = $_POST['trade_name'];

    $stmt = $conn->prepare("INSERT INTO Trades (Trade_Name) VALUES (?)");
    $stmt->bind_param("s", $trade_name);
    $stmt->execute();

    header("Location: trades_index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Trade</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10">

    <div class="max-w-md mx-auto bg-white p-8 rounded shadow">
        <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Add New Trade</h2>

        <form method="post" class="space-y-4">
            <div>
                <label for="trade_name" class="block text-gray-700 font-medium">Trade Name</label>
                <input type="text" name="trade_name" id="trade_name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end">
                <a href="trades_index.php" class="mr-4 text-blue-600 hover:underline">Back</a>
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition duration-150">
                    Save
                </button>
            </div>
        </form>
    </div>

</body>
</html>
