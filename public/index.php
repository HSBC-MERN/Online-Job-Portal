<?php
require_once "../config/db.php";

// Fetch featured/open jobs dynamically
$sql = "SELECT job.*, employee.company_name AS company
        FROM job 
        JOIN employee ON job.employee_id = employee.employee_id
        WHERE status = 'Open'
        ORDER BY posted_date DESC
        LIMIT 15";
$stmt = $pdo->query($sql);
$jobs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Job Portal</title>
    <style>
        /* Reset & Global */
        * {
            margin: 2px;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            line-height: 1.6;
            background: #f5f7fa;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 8px;
            background: #4a90e2;
            color: #fff;
            border-radius: 20px;    
            margin-bottom: 5px;
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

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #74ebd5, #9face6);
            color: #fff;
            text-align: center;
            padding: 100px 20px;
            border-radius: 20px;
        }

        .hero h2 {
            font-size: 3em;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease;
        }

        .hero p {
            font-size: 1.2em;
            margin-bottom: 40px;
        }

        .hero .btn {
            padding: 15px 30px;
            margin: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-seeker {
            background: #4a90e2;
            color: #fff;
            /* width: 25vw; */
        }

        .btn-seeker:hover {
            background: #357ABD;
        }

        .btn-employee {
            background: #50c878;
            color: #fff;
        }

        .btn-employee:hover {
            background: #3da360;
        }

        /* Section Styles */
        section {
            padding: 60px 20px;
        }

        section h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
            font-size: 2em;
        }

        /* Cards */
        .cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 280px;
            text-align: center;
            transition: 0.3s;
            cursor: pointer;
        }

        a {
            text-decoration: none;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .card h3 {
            margin-bottom: 15px;
            color: #4a90e2;
        }

        .card p {
            color: #555;
        }

        /* Footer */
        footer {
            background: #333;
            color: #fff;
            padding: 30px 20px;
            text-align: center;
        }

        footer a {
            color: #ffd700;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Animations */
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

        /* Responsive */
        @media(max-width:768px) {
            .cards {
                flex-direction: column;
                align-items: center;
            }
        }

        /* Carousel Styles */
        #carousel-section {
            width: 100%;
            background: #f0f4f9;
            padding: 60px 0;
        }

        .carousel-container {
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .carousel-track {
            display: flex;
            transition: transform 0.6s ease-in-out;
            gap: 40px;
            justify-content: center;
        }

        .carousel-card {
            width: 8cm;
            height: 8cm;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            flex-shrink: 0;
            opacity: 0.6;
            filter: blur(2px);
            transform: scale(0.9);
            transition: all 0.5s ease;

        }

        .carousel-card.active {
            opacity: 1;
            filter: none;
            transform: scale(1.15);
            z-index: 2;
        }

        .carousel-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 8px;
            margin-top: 14%;
            object-fit: cover;
        }

        .carousel-card h5 {
            font-size: 20px;
            font-weight: bold;
        }

        .carousel-card p {
            font-size: 15px;
        }

        /* < !-- Roadmap Styles --> */
        #roadmap {
            background: #f9f9f9;
            padding: 30px 15px;
            /* reduced padding */
        }

        #roadmap h2 {
            text-align: center;
            margin-bottom: 25px;
            /* reduced margin */
            color: #333;
            font-size: 1.6em;
            font-weight: bold;
        }

        .timeline {
            position: relative;
            max-width: 900px;
            margin: auto;
        }

        /* Vertical line */
        .timeline::after {
            content: '';
            position: absolute;
            width: 4px;
            /* thinner line */
            background: #4a90e2;
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -2px;
        }

        /* Timeline item */
        .timeline-item {
            padding: 10px 20px;
            /* less spacing */
            position: relative;
            width: 50%;
        }

        .timeline-item.left {
            left: 0;
        }

        .timeline-item.right {
            left: 50%;
        }

        .timeline-content {
            padding: 12px;
            /* reduced padding */
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            font-size: 0.9em;
        }

        /* Circles */
        .timeline-item::before {
            content: '';
            position: absolute;
            top: 15px;
            /* adjusted */
            right: -10px;
            width: 14px;
            /* smaller circle */
            height: 14px;
            background: #4a90e2;
            border: 3px solid #fff;
            border-radius: 50%;
            z-index: 1;
        }

        .timeline-item.right::before {
            left: -10px;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .timeline::after {
                left: 20px;
            }

            .timeline-item {
                width: 100%;
                padding-left: 50px;
                padding-right: 15px;
            }

            .timeline-item.right {
                left: 0;
            }

            .timeline-item::before {
                left: 12px;
            }
        }

        /* Main Button */
        /* === Modal Overlay === */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 15, 15, 0.55);
            backdrop-filter: blur(8px);
            justify-content: center;
            align-items: center;
            animation: fadeBg 0.4s ease-in-out;
        }

        @keyframes fadeBg {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* === Modal Content === */
        .modal-content {
            background: rgba(255, 255, 255, 0.9);
            padding: 35px 28px;
            border-radius: 16px;
            max-width: 420px;
            height: 92%;
            /* width: 92%; */

            text-align: center;
            position: relative;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            animation: slideUp 0.5s ease forwards;
            transform: translateY(40px);
            opacity: 0;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* === Close Button === */
        .close-btn {
            position: absolute;
            top: 12px;
            right: 18px;
            font-size: 26px;
            font-weight: bold;
            color: #444;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .close-btn:hover {
            color: #e74c3c;
            transform: rotate(90deg);
        }

        /* === Titles & Text === */
        .logo {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #3498db, #2ecc71);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 18px;
        }

        .modal-content h2 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 12px;
        }

        .modal-content p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        /* === Hero Section === */
        .hero1 p {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 18px;


        }

        .hero-buttons {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* === Buttons === */
        .btn {
            width: 100%;
            padding: 13px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-seeker {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: #fff;
            width: 400px;
        }

        .seeker {
            width: 314px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: #fff;
        }
        .seeker:hover{
            background: linear-gradient(135deg, #2980b9, #3498db);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(41, 128, 185, 0.3);
        }
        .btn-seeker:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(41, 128, 185, 0.3);
        }

        .btn-employee {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: #fff;
        }

        .btn-employee:hover {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(39, 174, 96, 0.3);
        }

        /* === Social Register === */
        .social-register {
            margin-top: 25px;
        }

        .social-register p {
            font-size: 0.95rem;
            color: #444;
            margin-bottom: 12px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 18px;
        }

        .social-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.3rem;
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .google {
            background: #db4437;
        }

        .linkedin {
            background: #0a66c2;
        }

        .twitter {
            background: #1da1f2;
        }

        .social-btn:hover {
            transform: scale(1.15) rotate(8deg);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
            opacity: 0.9;
        }

        #contact {
            background-color: #d7f1f9ff;
        }
    </style>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


</head>

<body>

    <!-- Header -->
    <header>
        <div class="container">
            <h1>Job Portal</h1>
            <nav>
                <a href="#jobs">Jobs</a>
                <a href="cards.php">Company reviews</a>
                <a href="#contact">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="hero">
        <h2>Find Your Dream Job or Hire Top Talent</h2>
        <p>Connecting job seekers and employers efficiently and professionally.</p>
        <button class="btn btn-seeker" onclick="openModal()">Get Start</button>
    </section>
    <!-- Modal -->

    <div id="authModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>

            <h1 class="logo">Job Portal</h1>
            <h2>Ready to take the next step?</h2>
            <p>Create an account and get started.</p>

            <section class="hero1">
                <p>Connecting job seekers and employers efficiently and professionally.</p>
                <div class="hero-buttons">
                    <a href="register_seeker.php"><button class="btn seeker">Job Seeker Register</button></a>
                    <a href="register_employee.php"><button class="btn btn-employee">Employee Register</button></a>
                </div>
            </section>

            <div class="social-register">
                <p>Or register using</p>
                <div class="social-icons">
                    <a href="#">
                        <div class="social-btn google"><i class="fab fa-google"></i></div>
                    </a>
                    <a href="#">
                        <div class="social-btn linkedin"><i class="fab fa-linkedin-in"></i></div>
                    </a>
                    <a href="#">
                        <div class="social-btn twitter"><i class="fab fa-twitter"></i></div>
                    </a>
                </div>
            </div>
        </div>
    </div>


    <script>
        function openModal() {
            document.getElementById("authModal").style.display = "flex";
        }

        function closeModal() {
            document.getElementById("authModal").style.display = "none";
        }
        window.onclick = function(event) {
            let modal = document.getElementById("authModal");
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>




    <!-- Featured Jobs -->
    <section id="jobs">
        <h2>Featured Jobs</h2>
        <a onclick="openModal()">
            <div class="cards">
                <?php if ($jobs): ?>
                    <?php foreach ($jobs as $job): ?>
                        <div class="card">
                            <h3><?= htmlspecialchars($job['job_title']) ?></h3>
                            <p>Company: <?= htmlspecialchars($job['company']) ?></p>
                            <p>Skills: <?= htmlspecialchars($job['required_skills']) ?></p>
                            <p>Experience: <?= htmlspecialchars($job['experience_required']) ?> yrs</p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align:center;">No jobs available right now.</p>
                <?php endif; ?>
            </div>
        </a>
    </section>

    <!-- 🔥 Feedback Carousel -->
    <section id="carousel-section">
        <h2>User Feedback</h2>
        <div class="carousel-container">
            <div class="carousel-track" id="carouselTrack">
                <div class="carousel-card">
                    <img src="https://randomuser.me/api/portraits/men/11.jpg" />
                    <h5>John Doe</h5>
                    <p>Software Engineer • Google</p>
                    <p>"This portal helped me land my dream job quickly."</p>
                </div>
                <div class="carousel-card">
                    <img src="https://randomuser.me/api/portraits/women/21.jpg" />
                    <h5>Jane Smith</h5>
                    <p>HR • Microsoft</p>
                    <p>"We found great talent via this platform."</p>
                </div>
                <div class="carousel-card">
                    <img src="https://randomuser.me/api/portraits/men/31.jpg" />
                    <h5>David Lee</h5>
                    <p>Product Manager • Amazon</p>
                    <p>"Smooth process and excellent UX."</p>
                </div>
                <div class="carousel-card">
                    <img src="https://randomuser.me/api/portraits/women/41.jpg" />
                    <h5>Sophia Williams</h5>
                    <p>Designer • Adobe</p>
                    <p>"I loved the UI and fast apply flow."</p>
                </div>
                <div class="carousel-card">
                    <img src="data:image/jpeg;base64,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" />
                    <h5>Steve smith</h5>
                    <p>Developer • Facebook</p>
                    <p>"Great opportunities and an easy dashboard."</p>
                </div>
                <div class="carousel-card">
                    <img src="https://cdn.decohere.ai/cus_QmeRRs7CrpQL06-stablevideo/outputs/1726698371871/thumbnail.webp" />
                    <h5>Chirs Evans</h5>
                    <p>Developer • instagram</p>
                    <p>"interative design and an easy dashboard"</p>
                </div>
                <div class="carousel-card">
                    <img src="data:image/jpeg;base64,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" />
                    <h5>Tony stark</h5>
                    <p>Developer • microsoft</p>
                    <p>"It Help to find a job "</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Roadmap Section -->
    <section id="roadmap">
        <h2>Platform Roadmap</h2>
        <div class="container">
            <div class="timeline">
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h3>Step 1: Register</h3>
                        <p>Quick signup for job seekers and employers.</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h3>Step 2: Browse & Post</h3>
                        <p>Seekers explore jobs, employers post openings.</p>
                    </div>
                </div>
                <div class="timeline-item left">
                    <div class="timeline-content">
                        <h3>Step 3: Apply & Manage</h3>
                        <p>One-click apply, employers manage applications.</p>
                    </div>
                </div>
                <div class="timeline-item right">
                    <div class="timeline-content">
                        <h3>Step 4: Get Hired</h3>
                        <p>Employers hire, seekers land jobs.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section
    <section id="about">
        <h2>How It Works</h2>
        <div class="cards">
            <div class="card">
                <h3>Job Seekers</h3>
                <p>Create profile, browse jobs, and apply with one click.</p>
            </div>
            <div class="card">
                <h3>Employers</h3>
                <p>Post jobs, manage applications, and hire the right talent.</p>
            </div>
        </div>
    </section> -->

    <!-- Contact Section -->
    <section id="contact">
        <h2>Contact Us</h2>
        <p style="text-align:center;">Email: <a href="mailto:support@jobportal.com">support@jobportal.com</a></p>
    </section>

    <!-- Footer -->
    <footer>
        &copy; <?= date('Y') ?> Online Job Portal | All rights reserved.
    </footer>

    <script>
        (function() {
            const track = document.getElementById("carouselTrack");
            const cards = Array.from(track.children);
            let index = 0;
            const total = cards.length;

            function updateCarousel() {
                cards.forEach(card => card.classList.remove("active"));
                const activeCard = cards[index];
                activeCard.classList.add("active");
                const offset = -(activeCard.offsetLeft - (track.parentElement.offsetWidth / 2 - activeCard.offsetWidth / 2));
                track.style.transform = `translateX(${offset}px)`;
            }

            function nextCard() {
                index = (index + 1) % total;
                updateCarousel();
            }

            updateCarousel();
            setInterval(nextCard, 3000);
        })();
    </script>
</body>

</html>