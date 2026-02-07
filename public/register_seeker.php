<?php
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];

  // Check if email already exists
  $check = $pdo->prepare("SELECT * FROM job_seeker WHERE email = :email");
  $check->execute([':email' => $email]);

  if ($check->rowCount() > 0) {
    echo "<script>alert('❌ Email already registered! Please login instead.');</script>";
    exit;
  }

  // Collect other fields
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $highest_qualification = $_POST['highest_qualification'] ?? '';
  $specialization = $_POST['specialization'] ?? '';
  $university = $_POST['university'] ?? '';
  $passout_year = $_POST['passout_year'];
  $skills = $_POST['skills'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // 🔽 Resume Upload Handling
  $resume_db_path = null;
  if (!empty($_FILES['resume']['name'])) {
    $allowed = [
      'application/pdf',
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    if (
      $_FILES['resume']['error'] === 0 &&
      in_array($_FILES['resume']['type'], $allowed) &&
      $_FILES['resume']['size'] <= 2 * 1024 * 1024
    ) { // 2MB max

      $ext = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
      $newName = uniqid('resume_') . '.' . $ext;

      $uploadDir = __DIR__ . '/../uploads/resumes/';
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
      }

      $dest = $uploadDir . $newName;
      move_uploaded_file($_FILES['resume']['tmp_name'], $dest);

      $resume_db_path = 'uploads/resumes/' . $newName; // relative path for DB
    }
  }

  // Insert into DB (with resume)
  $sql = "INSERT INTO job_seeker (
                name, email, phone, address, 
                highest_qualification, specialization, university, passout_year, 
                skills, password, resume
            ) VALUES (
                :name, :email, :phone, :address, 
                :highest_qualification, :specialization, :university, :passout_year, 
                :skills, :password, :resume
            )";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':name' => $name,
    ':email' => $email,
    ':phone' => $phone,
    ':address' => $address,
    ':highest_qualification' => $highest_qualification,
    ':specialization' => $specialization,
    ':university' => $university,
    ':passout_year' => $passout_year,
    ':skills' => $skills,
    ':password' => $password,
    ':resume' => $resume_db_path
  ]);

  echo "<script>
            alert('✅ Registration successful!');
            window.location.href='seeker_dashboard.php';
          </script>";
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Job Seeker Registration</title>
  <style>
    /* Global reset */
    * {
      margin: 8 px;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      height: 130vh;
      /* display: flex; */
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #74ebd5, #9face6);
      animation: gradientMove 12s ease infinite alternate;
    }

    @keyframes gradientMove {
      0% {
        background-position: 0% 50%;
      }

      100% {
        background-position: 100% 50%;
      }
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

    .form-container {
      background: #fff;
      padding: 30px 25px;
      border-radius: 14px;
      margin-left: 40%;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      width: 370px;
      animation: fadeIn 0.9s ease-out;
      text-align: center;
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

    .form-container h2 {
      margin-bottom: 18px;
      font-size: 22px;
      font-weight: 600;
      color: #333;
      letter-spacing: 0.5px;
    }

    .form-container input {
      width: 100%;
      padding: 11px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .form-container input:focus {
      border-color: #4a90e2;
      box-shadow: 0 0 8px rgba(74, 144, 226, 0.3);
      transform: scale(1.02);
    }

    .form-container button {
      width: 100%;
      padding: 12px;
      margin-top: 12px;
      background: #4a90e2;
      border: none;
      border-radius: 8px;
      color: #fff;
      font-weight: 600;
      font-size: 15px;
      letter-spacing: 0.5px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .form-container button:hover {
      background: #357ABD;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(53, 122, 189, 0.3);
    }

    .divider {
      margin: 18px 0;
      position: relative;
      text-align: center;
      font-size: 14px;
      color: #666;
    }

    .divider:before,
    .divider:after {
      content: "";
      position: absolute;
      top: 50%;
      width: 40%;
      height: 1px;
      background: #ddd;
    }

    .divider:before {
      left: 0;
    }

    .divider:after {
      right: 0;
    }

    .divider span {
      background: #fff;
      padding: 0 10px;
      position: relative;
    }

    .login-note {
      font-size: 13px;
      color: #333;
    }

    .login-note a {
      color: #4a90e2;
      font-weight: 600;
      text-decoration: none;
    }

    .login-note a:hover {
      text-decoration: underline;
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
    <h2>Job Seeker Registration</h2>
    <form method="post" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="phone" placeholder="Phone" required>
      <input type="text" name="address" placeholder="Address" required>
      <input type="text" name="highest_qualification" placeholder="Highest Qualification" required>
      <input type="text" name="specialization" placeholder="Specialization">
      <input type="text" name="university" placeholder="University">
      <input type="text" name="skills" placeholder="Skills" required>
      <input type="number" name="passout_year" placeholder="Passout Year" required>
      <input type="password" name="password" placeholder="Password" required>
      <!-- New resume upload field -->
      <input type="file" name="resume" accept=".pdf,.doc,.docx">
      <button type="submit">Register</button>
    </form>

    <div class="divider"><span>or</span></div>
    <p class="login-note">If already registered, please <a href="login_seeker.php">login here</a>.</p>
  </div>
</body>

</html>