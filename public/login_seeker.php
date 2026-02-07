<?php
require_once "../config/db.php";
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM job_seeker WHERE email = :email LIMIT 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([':email' => $email]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['seeker_id'] = $user['seeker_id'];
    $_SESSION['seeker_name'] = $user['name'];
    header("Location: seeker_dashboard.php");
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
  <title>Job Seeker Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #74ebd5, #9face6);  
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
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
      margin-left: 40%;
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
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      width: 350px;
      margin-left: 38%;
      animation: fadeInUp 0.8s ease;
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
      animation: slideIn 1s ease;
    }

    .form-container input {
      width: 94%;
      padding: 10px;
      margin: 8px 0;
      border: 1px solid #ddd;
      border-radius: 8px;
      transition: 0.3s;
    }

    .form-container input:focus {
      border-color: #4a90e2;
      box-shadow: 0 0 6px rgba(74, 144, 226, 0.4);
      transform: scale(1.03);
    }

    .form-container button {
      width: 100%;
      padding: 12px;
      background: #4a90e2;
      border: none;
      border-radius: 8px;
      color: #fff;
      font-weight: bold;
      transition: 0.3s;
    }

    .form-container button:hover {
      background: #357ABD;
      transform: translateY(-2px);
      box-shadow: 0 5px 12px rgba(53, 122, 189, 0.3);
    }

    .success {
      background: #e0ffe0;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 8px;
      color: #2b7a2b;
      font-weight: bold;
      text-align: center;
    }

    .error {
      background: #ffe0e0;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: 8px;
      color: #a33;
      font-weight: bold;
      text-align: center;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(-50px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
  </style>
</head>

<body>

  <!-- Header -->
  <header>
    <div class="container">
      <h1>Job Portal</h1>
      <nav>
        
        <a href="cards.php">Company reviews</a>
        
      </nav>
    </div>
  </header>
  <div class="form-container">
    <h2>Job Seeker Login</h2>
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