<?php
require_once "../config/db.php";
session_start();

if (!isset($_SESSION['employee_id'])) {
  header("Location: login_employee.php");
  exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $job_title = $_POST['job_title'];
  $job_description = $_POST['job_description'];
  $skills = $_POST['skills'];
  $experience_required = $_POST['experience_required'];
  $employment_type = $_POST['employment_type'];
  $salary_range = $_POST['salary_range'];
  $job_location = $_POST['job_location'];
  $last_date = $_POST['last_date_to_apply'];

  $sql = "INSERT INTO job (employee_id, job_title, job_description, required_skills, experience_required, employment_type, salary_range, job_location, last_date_to_apply, status)
            VALUES (:employee_id, :job_title, :job_description, :skills, :experience_required, :employment_type, :salary_range, :job_location, :last_date, 'Open')";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':employee_id' => $_SESSION['employee_id'],
    ':job_title' => $job_title,
    ':job_description' => $job_description,
    ':skills' => $skills,
    ':experience_required' => $experience_required,
    ':employment_type' => $employment_type,
    ':salary_range' => $salary_range,
    ':job_location' => $job_location,
    ':last_date' => $last_date
  ]);

  $message = "✅ Job posted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Post a Job</title>
  <style>
    /* form-style.css */

    /* Google Font */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    /* Background */
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #E0EAFC, #CFDEF3);
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
    .form-container-1 {
      width: 100%;
      max-width: 550px;
      background: #fff;
      margin-left: 34%;
      padding: 35px 30px;
      border-radius: 16px;
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
      animation: fadeIn 0.7s ease-in-out;
    }

    /* Heading */
    h2 {
      text-align: center;
      font-size: 1.9rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 20px;
    }

    /* Success Message */
    .success-1 {
      background: #eafaf1;
      color: #27ae60;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 0.95rem;
      font-weight: 500;
      text-align: center;
      animation: slideDown 0.4s ease;
    }

    /* Form Elements */
    form {
      display: flex;
      flex-direction: column;
    }

    form input,
    form textarea {
      width: 100%;
      padding: 12px 14px;
      margin: 8px 0 15px;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      resize: none;
    }

    form input:focus,
    form textarea:focus {
      border-color: #3498db;
      box-shadow: 0 0 8px rgba(52, 152, 219, 0.3);
      outline: none;
    }

    /* Button */
    button {
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
      box-shadow: 0 6px 15px rgba(52, 152, 219, 0.4);
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
    @media (max-width: 600px) {
      .form-container-1 {
        padding: 25px 20px;
      }

      h2 {
        font-size: 1.6rem;
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
        <a href="employee_dashboard.php">back</a>
        <a href="index.php">log out</a>


      </nav>
    </div>
  </header>
  <div class="form-container-1">
    <h2>Post a New Job</h2>
    <?php if ($message): ?>
      <div class="success-1"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="text" name="job_title" placeholder="Job Title" required>
      <textarea name="job_description" placeholder="Job Description" rows="4" required></textarea>
      <input type="text" name="skills" placeholder="Required Skills" required>
      <input type="number" name="experience_required" placeholder="Experience (Years)" required>
      <input type="text" name="employment_type" placeholder="Employment Type (e.g. Full-Time)" required>
      <input type="text" name="salary_range" placeholder="Salary Range (e.g. 5-8 LPA)">
      <input type="text" name="job_location" placeholder="Job Location" required>
      <input type="date" name="last_date_to_apply" required>
      <button type="submit">Post Job</button>
    </form>
  </div>
</body>

</html>