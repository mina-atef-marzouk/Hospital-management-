<?php
session_start();
include("../includes/user-header.php");
include("../includes/dbh.inc.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch doctors with their attributes
$sql = "
    SELECT d.DoctorID, d.Name, d.Specialty, a.AttributeName, v.AttributeValue
    FROM DoctorDivHome d
    LEFT JOIN DynamicAttributeValues v ON d.DoctorID = v.DoctorID
    LEFT JOIN Attributes a ON v.AttributeID = a.AttributeID
    ORDER BY d.DoctorID, a.AttributeName
";

$result = $conn->query($sql);

$doctors = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctorID = $row['DoctorID'];
        if (!isset($doctors[$doctorID])) {
            $doctors[$doctorID] = [
                'name' => $row['Name'],
                'specialty' => $row['Specialty'],
                'attributes' => []
            ];
        }
        $doctors[$doctorID]['attributes'][$row['AttributeName']] = $row['AttributeValue'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" defer></script>
  <!--=============== REMIXICONS ===============-->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
  <!--=============== CSS ===============-->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../public/css/user.css">
  <title> - Care Flow</title>
</head>
<body class="homepage">
  <!--=============== HEADER ===============-->
  <div class="video-bg">
    <video autoplay muted loop>
      <source src="../public/img/video.mp4" type="video/mp4">
    </video>
    <div class="overlay"></div>
  </div>

  <section class="section__container about__container" id="about">
    <div class="about__content">
      <h2 class="section__header">About Us</h2>
      <p>
        At CareFlow, we are dedicated to transforming the way patients manage their healthcare.
        Our mission is to empower individuals with the tools and information they need to take charge of their health journeys.
      </p>
      <p>
        Explore our extensive collection of expertly written articles and guides covering a wide range of health topics.
      </p>
      <p>
        Discover practical health tips and lifestyle advice to optimize your physical and mental well-being.
      </p>
    </div>
    <div class="about__image">
      <img src="../public/img/aboutUs.png" alt="about" />
    </div>
  </section>

  <section class="section__container service__container" id="service">
    <div class="service__grid">
      <div class="service__card">
        <span><i class="ri-microscope-line"></i></span>
        <h4>Generate Reports</h4>
        <p>Accurate Diagnostics, Swift Results: comprehensive reports for patients, providing them with valuable insights into their healthcare journey.</p>
      </div>
      <div class="service__card">
        <span><i class="ri-mental-health-line"></i></span>
        <h4>Health Check</h4>
        <p>Our thorough assessments and expert evaluations help you stay proactive about your health.</p>
      </div>
      <div class="service__card">
        <span><i class="ri-hospital-line"></i></span>
        <h4>Reminders</h4>
        <p>Automated reminders for when to take medications, which can be sent via SMS or email.</p>
      </div>
    </div>
  </section>

  <section class="section__container why__container" id="blog">
    <div class="why__image">
      <img src="../public/img/Medical Care Logo.png" alt="why choose us" />
    </div>
    <div class="why__content">
      <h2 class="section__header">Why Choose Us</h2>
      <p>
        With a steadfast commitment to your well-being, our team of highly trained healthcare professionals ensures that you receive nothing
        short of exceptional patient experiences.
      </p>
      <div class="why__grid">
        <span><i class="ri-hand-heart-line"></i></span>
        <div>
          <h4>Intensive Care</h4>
          <p>Our Intensive Care Unit is equipped with advanced technology and staffed by a team of professionals.</p>
        </div>
        <span><i class="ri-truck-line"></i></span>
        <div>
          <h4>Free Ambulance Car</h4>
          <p>A compassionate initiative to prioritize your health and well-being without any financial burden.</p>
        </div>
        <span><i class="ri-hospital-line"></i></span>
        <div>
          <h4>Medical and Surgical</h4>
          <p>Our Medical and Surgical services offer advanced healthcare solutions to address medical needs.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section__container doctors__container" id="Doctors">
    <div class="doctors__grid" id="doctors-list">
        <?php foreach ($doctors as $doctor): ?>
            <div class="doctors__card">
                <div class="doctors__card__image">
                    <img src="<?= htmlspecialchars($doctor['attributes']['Image'] ?? '../public/img/default.jpg') ?>" alt="doctor" />
                    <div class="doctors__socials">
                        <span><a href="<?= htmlspecialchars($doctor['attributes']['Instagram'] ?? '#') ?>"><i class="ri-instagram-line"></i></a></span>
                        <span><a href="<?= htmlspecialchars($doctor['attributes']['Facebook'] ?? '#') ?>"><i class="ri-facebook-fill"></i></a></span>
                        <span><a href="<?= htmlspecialchars($doctor['attributes']['Heart'] ?? '#') ?>"><i class="fas fa-stethoscope"></i></a></span>
                    </div>
                </div>
                <h4><?= htmlspecialchars($doctor['name']) ?></h4>
                <p><?= htmlspecialchars($doctor['specialty']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
  </section>
<br>
<br>
  <footer class="footer">
    <br>
    <div class="section__container footer__container">
      <div class="footer__col">
        <h3>Care<span>Flow</span></h3>
        <p>
          We are honored to be a part of your healthcare journey and committed to delivering compassionate, personalized, and top-notch care every step of the way.
        </p>
        <div id="google_translate_element" style="display: none;"></div>
        <div class="custom-translate">
          <select onchange="translateLanguage(this.value)">
            <option value="">Select Language</option>
            <option value="en">English</option>
            <option value="ar">Arabic</option>
            <option value="de">Deutsch</option>
          </select>
        </div>
      </div>
      <div class="footer__col">
        <h4>About Us</h4>
        <p>Home</p>
        <p>About Us</p>
        <p>Work With Us</p>
        <p>Our Blog</p>
        <p>Terms & Conditions</p>
      </div>
      <div class="footer__col">
        <h4>Services</h4>
        <p>Search Terms</p>
        <p>Advance Search</p>
        <p>Privacy Policy</p>
        <p>Suppliers</p>
        <p>Our Stores</p>
      </div>
      <div class="footer__col">
        <h4>Contact Us</h4>
        <p><i class="ri-map-pin-2-fill"></i> 123, Cairo Street, Egypt</p>
        <p><i class="ri-mail-fill"></i> support@careFlow.com</p>
        <p><i class="ri-phone-fill"></i> (+012) 3456 789</p>
      </div>
    </div>
  </footer>

  <script src="../public/js/NavFooter.js"></script>
</body>
</html>
