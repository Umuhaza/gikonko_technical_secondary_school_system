<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gikonko TSS Trainee Performance Report</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 2rem;
      background-color: #f9f9f9;
      color: #333;
    }

    h1 {
      text-align: center;
      color: #004085;
      margin-bottom: 1rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      background-color: #fff;
    }

    th, td {
      padding: 0.75rem 1rem;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #007bff;
      color: white;
      font-weight: bold;
    }

    tr:nth-child(even) {
      background-color: #f2f6fc;
    }

    .competent {
      color: green;
      font-weight: bold;
    }

    .not-competent {
      color: red;
      font-weight: bold;
    }

    footer {
      margin-top: 2rem;
      text-align: center;
      font-size: 0.9rem;
      color: #666;
    }
  </style>
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

  <h1>Gikonko TSS Trainee Performance Report</h1>

  <table>
    <thead>
      <tr>
        <th>Trainee Name</th>
        <th>Module</th>
        <th>Average Marks</th>
        <th>Competency Status</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Jane Doe</td>
        <td>ICT Fundamentals</td>
        <td>75.50</td>
        <td class="competent">Competent</td>
      </tr>
      <tr>
        <td>John Smith</td>
        <td>Professional Accounting</td>
        <td>68.00</td>
        <td class="not-competent">Not Yet Competent</td>
      </tr>
      <tr>
        <td>Mary Johnson</td>
        <td>Electrical Technology</td>
        <td>82.25</td>
        <td class="competent">Competent</td>
      </tr>
      <tr>
        <td>Paul Nkunda</td>
        <td>Building Construction</td>
        <td>59.75</td>
        <td class="not-competent">Not Yet Competent</td>
      </tr>
    </tbody>
  </table>
<button onclick="window.print()">Print Report</button>

  <footer>
    &copy; 2025 Gikonko TSS - All rights reserved
  </footer>

</body>
</html>
