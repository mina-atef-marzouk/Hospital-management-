<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in (e.g., check session variable)
$isLoggedIn = isset($_SESSION['user_id']); // Replace 'user_id' with your session variable for user login
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
  <link rel="stylesheet" href="../public/css/NavFooter.css">
  <title>NAV - Care Flow</title>
</head>
<body>

<header class="header">
  <nav class="nav container">
    <div class="nav__data">
      <a href="#" class="nav__logo">
        <img src="../public/img/Medical Care Logo.png" alt="doctor" /> Care Flow
      </a>

      <div class="nav__toggle" id="nav-toggle">
        <i class="ri-menu-line nav__burger"></i>
        <i class="ri-close-line nav__close"></i>
      </div>
    </div>

    <!--=============== NAV MENU ===============-->
    <div class="nav__menu" id="nav-menu">
      <ul class="nav__list">
        <li><a href="../users/index.php" class="nav__link">Home</a></li>
        <!--=============== DROPDOWN 1 ===============-->
        <li class="dropdown__item">
          <div class="nav__link">
          Appointments <i class="ri-arrow-down-s-line dropdown__arrow"></i></a>
          </div>
          <ul class="dropdown__menu">
            <li>
              <a href="../patients/view_appointments.php" class="dropdown__link">
                <i class="ri-eye-line"></i> View Appointment
              </a>
            </li>

            <li>
              <a href="../patients/book_appointment.php" class="dropdown__link">
              <i class="ri-calendar-line"></i> Book Appointment
              </a>
            </li>
          </ul>
        </li>
        <!--=============== DROPDOWN 1 ===============-->
        <li class="dropdown__item">
          <div class="nav__link">
            Prespection <i class="ri-arrow-down-s-line dropdown__arrow"></i></a>
          </div>
          <ul class="dropdown__menu">
            <li>
              <a href="../patients/HealthreportsNOUR.php" class="dropdown__link">
                <i class="ri-pie-chart-line"></i> Reports
              </a>
            </li>
            <li>
              <a href="../patients/payment.php" class="dropdown__link">
                <i class="ri-arrow-up-down-line"></i> Payments
              </a>
            </li>
            <li>
              <a href="../patients/medicalHistory.php" class="dropdown__link">
                <i class="ri-bar-chart-line"></i> Medical History
              </a>
            </li>
          </ul>
        </li>
        <li><a href="../users/index.php#Doctors" class="nav__link">Doctors</a></li>
        <!--=============== DROPDOWN 2 ===============-->
        <li class="dropdown__item">
          <div class="nav__link">
            Profile <i class="ri-arrow-down-s-line dropdown__arrow"></i>
          </div>
          <ul class="dropdown__menu">
            <li><a href="../patients/Profile.php" class="dropdown__link"><i class="ri-user-line"></i> Information</a></li>
            <li><a href="../patients/ProfileChange.php" class="dropdown__link"><i class="ri-lock-line"></i> Change Password</a></li>
            <li><a href="../patients/message.php" class="dropdown__link"><i class="ri-message-3-line"></i> Message</a></li>
            <li class="dropdown__item">
              <a href="" class="dropdown__link" id="theme-button">
                <i class="ri-sun-line" id="theme-icon"></i> Dark/Light Mode
              </a>
            </li>
          </ul>
        </li>

        <!-- LOGIN/LOGOUT BUTTON -->
        <li>
          <a href="<?= $isLoggedIn ? '../users/logout.php' : '../users/Login.php'; ?>" id="SIGN" class="nav__link">
            <?= $isLoggedIn ? 'LOGOUT' : 'LOGIN'; ?>
          </a>
        </li>
      </ul>
    </div>
  </nav>
</header>

<script>
// Script to toggle navigation menu (if applicable)
const navToggle = document.getElementById('nav-toggle');
const navMenu = document.getElementById('nav-menu');
if (navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.toggle('show-menu');
    });
}
/*=============== translation api external google translate ===============*/
function googleTranslateElementInit() {
    new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}
function translateLanguage(language) {
    var select = document.querySelector('.goog-te-combo');
    if (select) {
        select.value = language;
        select.dispatchEvent(new Event('change'));
    }
}
</script>
<script src="../public/js/NavFooter.js"></script>

<script>
      // Disable right-click
    document.addEventListener("contextmenu", (e) => {
        alert("Right-click is disabled.");
        e.preventDefault();
    });
      // Disable Developer Tools keyboard shortcuts
    document.addEventListener("keydown", (e) => {
        if (
        e.key === "F12" ||
        (e.ctrlKey && e.shiftKey && ["I","i", "C","c", "J","j"].includes(e.key)) ||
        (e.ctrlKey && e.key === "U")
        ) {
        alert("Developer Tools shortcuts are disabled.");
        e.preventDefault();
        }
    });
    </script>
</body>
</html>
