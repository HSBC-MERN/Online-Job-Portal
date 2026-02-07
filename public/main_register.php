<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Job Portal - Welcome</title>
  <style>
    /* Google Font */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Inter", sans-serif;
    }

    body {
      background: #f3f4f6;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    /* Centered Card */
    .auth-container {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
    }

    .auth-card {
      background: #fff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      max-width: 420px;
      width: 100%;
      text-align: center;
      animation: fadeIn 0.5s ease;
    }

    .logo {
      font-size: 1.8rem;
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 15px;
    }

    .auth-card h2 {
      font-size: 1.3rem;
      font-weight: 600;
      color: #333;
      margin-bottom: 10px;
    }

    .auth-card p {
      font-size: 0.95rem;
      color: #666;
      margin-bottom: 20px;
    }

    /* Hero Section */
    .hero h2 {
      font-size: 1.2rem;
      margin-bottom: 10px;
      color: #2c3e50;
    }

    .hero p {
      font-size: 0.95rem;
      color: #555;
      margin-bottom: 20px;
    }

    .hero-buttons {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    /* Buttons */
    .btn {
      width: 100%;
      padding: 12px;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: all 0.3s ease;
    }

    .btn-seeker {
      background: #3498db;
      color: #fff;
    }

    .btn-seeker:hover {
      background: #2980b9;
      transform: translateY(-2px);
    }

    .btn-employee {
      background: #2ecc71;
      color: #fff;
    }

    .btn-employee:hover {
      background: #27ae60;
      transform: translateY(-2px);
    }

    /* Social Register */
    .social-register {
      margin-top: 25px;
    }

    .social-register p {
      margin-bottom: 10px;
      color: #555;
      font-size: 0.9rem;
    }

    .social-icons {
      display: flex;
      justify-content: center;
      gap: 15px;
    }

    .social-btn {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 1.3rem;
      color: #fff;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .google { background: #db4437; }
    .linkedin { background: #0a66c2; }
    .twitter { background: #1da1f2; }

    .social-btn:hover {
      opacity: 0.85;
      transform: scale(1.1);
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 500px) {
      .auth-card {
        padding: 25px 20px;
      }

      .logo {
        font-size: 1.5rem;
      }
    }
  </style>

  <!-- ✅ Font Awesome CDN (works without kit) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="auth-container">
    <div class="auth-card">
      <h1 class="logo">Job Portal</h1>
      <h2>Ready to take the next step?</h2>
      <p>Create an account and get started.</p>

      <section class="hero">
        <!-- <h2>Find Your Dream Job or Hire Top Talent</h2> -->
        <p>Connecting job seekers and employers efficiently and professionally.</p>
        <div class="hero-buttons">
          <a href="register_seeker.php"><button class="btn btn-seeker">Job Seeker Register</button></a>
          <a href="register_employee.php"><button class="btn btn-employee">Employee Register</button></a>
        </div>
      </section>

      <!-- Social Register -->
      <div class="social-register">
        <p>Or register using</p>
        <div class="social-icons">
          <a href="#"><div class="social-btn google"><i class="fab fa-google"></i></div></a>
          <a href="#"><div class="social-btn linkedin"><i class="fab fa-linkedin-in"></i></div></a>
          <a href="#"><div class="social-btn twitter"><i class="fab fa-twitter"></i></div></a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
