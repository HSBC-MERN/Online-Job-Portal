<?php
require_once "../config/db.php";
require_once "../includes/auth.php";
session_start();

if (!isset($_SESSION['employee_id'])) {
  header("Location: login_employee.php");
  exit;
}

// Handle Approve/Reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'], $_POST['action'])) {
    if ($_POST['action'] === 'approve') {
        $status = 'Selected';   // ✅ matches ENUM
    } elseif ($_POST['action'] === 'reject') {
        $status = 'Rejected';   // ✅ matches ENUM
    } else {
        $status = 'Pending';
    }

    $sql_update = "UPDATE applications SET status = :status WHERE application_id = :application_id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([
        ':status' => $status,
        ':application_id' => $_POST['application_id']
    ]);
}

// Fetch jobs posted by this employee
$sql = "SELECT * FROM job WHERE employee_id = :employee_id ORDER BY posted_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':employee_id' => $_SESSION['employee_id']]);
$jobs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Employee Dashboard</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

    * {
      margin: 2;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background: linear-gradient(135deg, #f9f9f9, #dbe9f6);
      min-height: 100vh;
      flex-direction: column;
      align-items: center;
      color: #2c3e50;
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
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 90%;
      max-width: 1000px;
      animation: fadeIn 0.7s ease-out;
    }

    h2 {
      font-size: 2rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 20px;
      text-align: center;
    }

    h3 {
      font-size: 1.4rem;
      font-weight: 600;
      margin: 25px 0 15px;
      color: #34495e;
      border-left: 5px solid #3498db;
      padding-left: 10px;
    }

    h4 {
      font-size: 1.2rem;
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 8px;
    }

    h5 {
      font-size: 1rem;
      font-weight: 600;
      margin: 15px 0 8px;
      color: #2980b9;
    }

    button {
      background: #3498db;
      color: #fff;
      border: none;
      padding: 8px 14px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.9rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #2980b9;
      transform: translateY(-2px) scale(1.05);
      box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }

    .approve-btn {
      background: green;
    }

    .approve-btn:hover {
      background: darkgreen;
    }

    .reject-btn {
      background: red;
    }

    .reject-btn:hover {
      background: darkred;
    }

    .form-container>div {
      background: #fdfdfd;
      border: 1px solid #e0e0e0;
      border-radius: 12px;
      padding: 18px;
      margin-bottom: 25px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .form-container>div:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    p {
      font-size: 0.95rem;
      color: #555;
      margin: 8px 0;
    }

    strong {
      color: #2c3e50;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
      font-size: 0.9rem;
    }

    table th,
    table td {
      padding: 10px 12px;
      text-align: left;
      border: 1px solid #ddd;
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

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body>
  <?php include "../includes/header.php"; ?>
  <header>
    <div class="container">
      <h1>Job Portal</h1>
      <nav>
        <a href="cards.php">Company reviews</a>
        <a href="index.php">Log out</a>
      </nav>
    </div>
  </header>

  <div class="form-container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['employee_name'] ?? 'Employee') ?></h2>
    <p><a href="post_job.php"><button>+ Post New Job</button></a></p>

    <h3>Your Job Postings</h3>
    <?php if ($jobs): ?>
      <?php foreach ($jobs as $job): ?>
        <div>
          <h4><?= htmlspecialchars($job['job_title']) ?> (<?= htmlspecialchars($job['status']) ?>)</h4>
          <p><strong>Skills:</strong> <?= htmlspecialchars($job['required_skills']) ?> |
            <strong>Experience:</strong> <?= htmlspecialchars($job['experience_required']) ?> yrs
          </p>
          <p><em>Posted on <?= htmlspecialchars($job['posted_date']) ?></em></p>

          <?php
          $sql2 = "SELECT applications.*, job_seeker.name, job_seeker.email
                   FROM applications
                   JOIN job_seeker ON applications.seeker_id = job_seeker.seeker_id
                   WHERE applications.job_id = :job_id";
          $stmt2 = $pdo->prepare($sql2);
          $stmt2->execute([':job_id' => $job['job_id']]);
          $applicants = $stmt2->fetchAll();
          ?>

          <h5>Applicants</h5>
          <?php if ($applicants): ?>
            <table>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Applied Date</th>
                <th>Status</th>
              </tr>
              <?php foreach ($applicants as $app): ?>
                <tr>
                  <td><?= htmlspecialchars($app['name']) ?></td>
                  <td><?= htmlspecialchars($app['email']) ?></td>
                  <td><?= htmlspecialchars($app['applied_date']) ?></td>
                  <td>
                    <form method="post" style="display:inline;">
                      <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                      <input type="hidden" name="action" value="approve">
                      <button type="submit" class="approve-btn">Approve</button>
                    </form>

                    <form method="post" style="display:inline;">
                      <input type="hidden" name="application_id" value="<?= $app['application_id'] ?>">
                      <input type="hidden" name="action" value="reject">
                      <button type="submit" class="reject-btn">Reject</button>
                    </form>

                    <br>
                    <small><?= htmlspecialchars($app['status']) ?></small>
                  </td>
                </tr>
              <?php endforeach; ?>
            </table>
          <?php else: ?>
            <p>No applicants yet.</p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No jobs posted yet.</p>
    <?php endif; ?>
  </div>

  <?php include "../includes/footer.php"; ?>
</body>
</html>
