<?php
require_once "../config/db.php";
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM employee WHERE email = :email LIMIT 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([':email' => $email]);
  $employee = $stmt->fetch();

  if ($employee && password_verify($password, $employee['password'])) {
    $_SESSION['employee_id'] = $employee['employee_id'];
    $_SESSION['employee_name'] = $employee['emp_name'];
    header("Location: employee_dashboard.php");
    exit;
  } else {
    $message = "❌ Invalid email or password!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Employee Login</title>
  <style>
    /* login-style.css */

    /* Google Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    * {
      margin: 2;
      padding: 0;
      box-sizing: border-box;
      font-family: "Inter", sans-serif;
    }

    /* Body Background */
    body {
      min-height: 130vh;
      background: linear-gradient(135deg, #74ebd5, #ACB6E5);

      justify-content: center;
      align-items: center;
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



    /* Login Card */
    .form-container {
      width: 100%;
      max-width: 380px;
      background: #fff;
      padding: 30px 25px;
      margin-left: 40%;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      text-align: center;
      animation: fadeInDown 0.7s ease-out;
    }

    /* Heading */
    h2 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 20px;
    }

    /* Error Message */
    .error {
      background: #ffe5e5;
      color: #c0392b;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 0.9rem;
      animation: shake 0.4s ease;
    }

    /* Input Fields */
    form input {
      width: 100%;
      padding: 12px 14px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      outline: none;
      font-size: 0.95rem;
      transition: all 0.3s ease;
    }

    form input:focus {
      border-color: #3498db;
      box-shadow: 0 0 8px rgba(52, 152, 219, 0.3);
    }

    /* Login Button */
    button {
      width: 100%;
      padding: 12px;
      margin-top: 15px;
      background: #3498db;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #2980b9;
      transform: scale(1.03);
      box-shadow: 0 5px 12px rgba(52, 152, 219, 0.3);
    }

    /* Subtle Fade In Animation */
    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Shake animation for error */
    @keyframes shake {
      0% {
        transform: translateX(0);
      }

      25% {
        transform: translateX(-5px);
      }

      50% {
        transform: translateX(5px);
      }

      75% {
        transform: translateX(-5px);
      }

      100% {
        transform: translateX(0);
      }
    }

    /* Responsive */
    @media (max-width: 500px) {
      .form-container {
        width: 90%;
        padding: 25px 20px;
      }

      h2 {
        font-size: 1.5rem;
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

      </nav>
    </div>
  </header>
  <div class="form-container">
    <h2>Employee Login</h2>
    <?php if ($message): ?>
      <div class="error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  </div>
</body>

</html>