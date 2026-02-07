<?php
require_once "../config/db.php";
require_once "../includes/auth.php";
session_start();

if (!isset($_SESSION['seeker_id'])) {
  header("Location: login_seeker.php");
  exit;
}

// ==========================
// Fetch available jobs (with filters)
// ==========================
$sql = "SELECT job.*, employee.company_name AS company 
        FROM job 
        JOIN employee ON job.employee_id = employee.employee_id
        WHERE status = 'Open'";

$conditions = [];
$params = [];

if (!empty($_GET['title'])) {
    $conditions[] = "job.job_title LIKE :title";
    $params[':title'] = "%" . $_GET['title'] . "%";
}
if (!empty($_GET['skills'])) {
    $conditions[] = "job.required_skills LIKE :skills";
    $params[':skills'] = "%" . $_GET['skills'] . "%";
}
if (!empty($_GET['experience'])) {
    $conditions[] = "job.experience_required <= :experience";
    $params[':experience'] = $_GET['experience'];
}
if (!empty($_GET['company'])) {
    $conditions[] = "employee.company_name LIKE :company";
    $params[':company'] = "%" . $_GET['company'] . "%";
}

if ($conditions) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY posted_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll();

// ==========================
// Fetch seeker’s applications
// ==========================
$sql2 = "SELECT applications.*, job.job_title, employee.company_name
         FROM applications
         JOIN job ON applications.job_id = job.job_id
         JOIN employee ON job.employee_id = employee.employee_id
         WHERE applications.seeker_id = :seeker_id
         ORDER BY applications.applied_date DESC";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute([':seeker_id' => $_SESSION['seeker_id']]);
$applications = $stmt2->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Seeker Dashboard</title>
  <style>
    /* seeker_dashboard.css */

    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Inter", sans-serif;
    }

    body {
      background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
      min-height: 100vh;
      flex-direction: column;
      align-items: center;
    }

    header {
      position: sticky;
      top: 0;
      z-index: 1000;
      background: #4a90e2;
      color: #fff;
      border-radius: 20px;
      margin-bottom: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    header .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 90%;
      max-width: 1200px;
      margin: auto;
    }

    header h1 {
      font-size: 2em;
    }

    nav a {
      color: #fff;
      margin-left: 20px;
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s;
      font-size: 18px;
    }

    nav a:hover {
      color: #ffd700;
    }

    .form-container {
      background: #fff;
      margin: 30px auto;
      padding: 25px 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      animation: fadeInUp 0.6s ease-out;
    }

    h2 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 15px;
      text-align: center;
    }

    h3 {
      font-size: 1.4rem;
      font-weight: 600;
      color: #34495e;
      margin: 20px 0 10px;
      border-left: 5px solid #3498db;
      padding-left: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 15px 0;
      font-size: 0.95rem;
    }

    table th,
    table td {
      padding: 12px 15px;
      text-align: left;
    }

    table th {
      background: #3498db;
      color: #fff;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    table tr:nth-child(even) {
      background: #f9f9f9;
    }

    table tr:hover {
      background: #ecf6fd;
      transition: 0.3s ease-in-out;
    }

    button {
      background: #3498db;
      color: #fff;
      border: none;
      padding: 8px 16px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 0.9rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #2980b9;
      transform: scale(1.05);
      box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
    }

    p {
      font-size: 1rem;
      color: #555;
      text-align: center;
      margin: 15px 0;
    }

    .filter-form {
      text-align: center;
      margin-bottom: 20px;
    }

    .filter-form input {
      padding: 8px 12px;
      margin: 5px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 0.9rem;
    }

    .filter-form button {
      padding: 8px 16px;
      margin: 5px;
      background: #3498db;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    .filter-form button:hover {
      background: #2980b9;
    }

    @keyframes fadeInUp {
      from {
        transform: translateY(30px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @media (max-width: 768px) {
      .form-container {
        padding: 20px;
      }

      table,
      th,
      td {
        font-size: 0.85rem;
      }

      button {
        padding: 6px 12px;
        font-size: 0.8rem;
      }
    }
  </style>
</head>

<body>
  <header>
    <div class="container">
      <h1>Job Portal</h1>
      <nav>
        <a href="cards.php">Company reviews</a>
        <a href="index.php">Log out</a>
      </nav>
    </div>
  </header>
  <?php include "../includes/header.php"; ?>

  <div class="form-container" style="width:90%;max-width:1000px;">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['seeker_name'] ?? 'Job Seeker') ?></h2>

    <h3>Available Jobs</h3>

    <!-- Filter Form -->
    <form method="GET" class="filter-form">
      <input type="text" name="title" placeholder="Search by Title" value="<?= htmlspecialchars($_GET['title'] ?? '') ?>">
      <input type="text" name="skills" placeholder="Search by Skills" value="<?= htmlspecialchars($_GET['skills'] ?? '') ?>">
      <input type="number" name="experience" placeholder="Max Experience (yrs)" value="<?= htmlspecialchars($_GET['experience'] ?? '') ?>">
      <input type="text" name="company" placeholder="Search by Company" value="<?= htmlspecialchars($_GET['company'] ?? '') ?>">
      <button type="submit">Filter</button>
      <a href="seeker_dashboard.php"><button type="button">Reset</button></a>
    </form>

    <?php if ($jobs): ?>
      <table border="1" cellpadding="10" width="100%" style="border-collapse:collapse;">
        <tr>
          <th>Title</th>
          <th>Company</th>
          <th>Skills</th>
          <th>Experience</th>
          <th>Action</th>
        </tr>
        <?php foreach ($jobs as $job): ?>
          <tr>
            <td><?= htmlspecialchars($job['job_title']) ?></td>
            <td><?= htmlspecialchars($job['company']) ?></td>
            <td><?= htmlspecialchars($job['required_skills']) ?></td>
            <td><?= htmlspecialchars($job['experience_required']) ?> yrs</td>
            <td>
              <a href="apply_job.php?job_id=<?= $job['job_id'] ?>">
                <button>Apply</button>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p>No jobs available right now.</p>
    <?php endif; ?>

    <h3>My Applications</h3>
    <?php if ($applications): ?>
      <table border="1" cellpadding="10" width="100%" style="border-collapse:collapse;">
        <tr>
          <th>Job Title</th>
          <th>Company</th>
          <th>Applied Date</th>
          <th>Status</th>
        </tr>
        <?php foreach ($applications as $app): ?>
          <tr>
            <td><?= htmlspecialchars($app['job_title']) ?></td>
            <td><?= htmlspecialchars($app['company_name']) ?></td>
            <td><?= htmlspecialchars($app['applied_date']) ?></td>
            <td><?= htmlspecialchars($app['status']) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php else: ?>
      <p>You haven’t applied for any jobs yet.</p>
    <?php endif; ?>
  </div>

  <?php include "../includes/footer.php"; ?>
</body>
</html>
