<?php
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $employee_name   = $_POST['name'];
  $emp_roll        = $_POST['emp_roll'];
  $company_name    = $_POST['company_name'];   // matches form
  $company_address = $_POST['company_address']; // matches form
  $experience      = $_POST['experience'];
  $email           = $_POST['email'];
  $phone           = $_POST['phone'] ?? null; // optional
  $password        = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // ✅ Check if email already exists
  $check = $pdo->prepare("SELECT employee_id FROM employee WHERE email = :email");
  $check->execute([':email' => $email]);

  if ($check->rowCount() > 0) {
    echo "<script>
              alert('❌ This email is already registered. Please login instead.');
              window.location.href='login_employee.php';
            </script>";
    exit;
  }

  // ✅ Insert only if email is new
  $sql = "INSERT INTO employee (
                employee_name, emp_roll, company_name, company_address, 
                experience, email, phone, password
            ) VALUES (
                :employee_name, :emp_roll, :company_name, :company_address, 
                :experience, :email, :phone, :password
            )";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':employee_name'   => $employee_name,
    ':emp_roll'        => $emp_roll,
    ':company_name'    => $company_name,
    ':company_address' => $company_address,
    ':experience'      => $experience,
    ':email'           => $email,
    ':phone'           => $phone,
    ':password'        => $password
  ]);

  echo "<script>
          alert('✅ Registration successful!');
          window.location.href='employee_dashboard.php';
        </script>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Employee Registration</title>
  <style>
    * {
      margin: 2;
      padding: 0;
      box-sizing: border-box;
      font-family: "Inter", sans-serif;
    }

    body {
      height: 130vh;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #74ebd5, #9face6);
      animation: gradientMove 12s ease infinite alternate;
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
      height: 10%;
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
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      height: 70%;
      width: 350px;
      margin-top: 30px;
      margin-left: 40%;
      animation: fadeInUp 0.8s ease;
      text-align: center;
    }

    .form-container h2 {
      margin-bottom: 20px;
      color: #333;
      animation: slideIn 1s ease;
    }

    .form-container input {
      width: 90%;
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
      margin-top: 10px;
      transition: 0.3s;
      cursor: pointer;
    }

    .form-container button:hover {
      background: #357ABD;
      transform: translateY(-2px);
      box-shadow: 0 5px 12px rgba(53, 122, 189, 0.3);
    }

    .divider {
      margin: 15px 0;
      font-size: 14px;
      color: #666;
    }

    .divider span {
      display: inline-block;
      background: #fff;
      padding: 0 10px;
      position: relative;
      top: -10px;
    }

    .divider:before {
      content: "";
      display: block;
      border-top: 1px solid #ddd;
      margin-top: 10px;
    }

    .login-note {
      margin-top: 10px;
      font-size: 13px;
      color: #333;
    }

    .login-note a {
      color: #4a90e2;
      font-weight: bold;
      text-decoration: none;
    }

    .login-note a:hover {
      text-decoration: underline;
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
    <h2>Employee Registration</h2>
    <form method="post">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="text" name="emp_roll" placeholder="Employee Roll/ID" required>
      <input type="text" name="company_name" placeholder="Company Name" required>
      <input type="text" name="company_address" placeholder="Company Address" required>

      <input type="number" name="experience" placeholder="Years of Experience" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Register</button>
    </form>
    <div class="divider"><span>or</span></div>
    <p class="login-note">If already registered, please <a href="login_employee.php">login here</a>.</p>
  </div>
  </div>
</body>

</html>