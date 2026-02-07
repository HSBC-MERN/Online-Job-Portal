<?php
require_once "../config/db.php";
session_start();

if (!isset($_SESSION['seeker_id'])) {
  header("Location: login_seeker.php");
  exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['job_id'])) {
  $job_id = $_GET['job_id'];

  $sql = "INSERT INTO applications (job_id, seeker_id, employee_id, applied_date, status)
            VALUES (:job_id, :seeker_id, 
                (SELECT employee_id FROM job WHERE job_id = :job_id),
                NOW(), 'Pending')";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':job_id' => $job_id,
    ':seeker_id' => $_SESSION['seeker_id']
  ]);

  $message = "✅ Application submitted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Apply for Job</title>
  <style>
    /* form-style.css */

    /* Google Font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Inter", sans-serif;
    }

    /* Background */
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #f9f9f9, #d7e1ec);

      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    /* Header */
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
      height: 80px;
      margin-left: 30%;
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

    /* Container */
    .form-container {
      width: 100%;
      max-width: 420px;
      background: #fff;
      padding: 30px 25px;
      margin-left: 36%;
      border-radius: 14px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      text-align: center;
      animation: fadeIn 0.6s ease-in-out;
    }

    /* Heading */
    h2 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 15px;
    }

    /* Success Message */
    .success {
      background: #e8f9f1;
      color: #27ae60;
      padding: 12px;
      border-radius: 8px;
      font-size: 0.95rem;
      font-weight: 500;
      margin: 15px 0;
      animation: slideDown 0.4s ease;
    }

    /* Paragraph */
    p {
      font-size: 1rem;
      color: #555;
      margin-bottom: 20px;
    }

    /* Button */
    button {
      width: 100%;
      padding: 12px;
      background: #3498db;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #2980b9;
      transform: translateY(-2px) scale(1.03);
      box-shadow: 0 6px 15px rgba(52, 152, 219, 0.3);
    }

    /* Animations */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(25px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsive */
    @media (max-width: 500px) {
      .form-container {
        padding: 25px 20px;
      }

      h2 {
        font-size: 1.5rem;
      }

      button {
        font-size: 0.9rem;
        padding: 10px;
      }
    }
  </style>
</head>

<body>
  <header>
    <div class="container">
      <h1>Job Portal</h1>
      <nav>
        <a href="seeker_dashboard.php">back</a>
        <a href="index.php">log out</a>


      </nav>
    </div>
  </header>
  <div class="form-container">
    <h2>Apply for Job</h2>
    <?php if ($message): ?>
      <div class="success"><?= htmlspecialchars($message) ?></div>
    <?php else: ?>
      <form method="post">
        <p>Click below to confirm your application.</p>
        <button type="submit">Apply Now</button>
      </form>
    <?php endif; ?>
  </div>
</body>

</html>