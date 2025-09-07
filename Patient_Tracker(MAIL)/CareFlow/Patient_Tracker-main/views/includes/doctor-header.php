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
        
      <li><a href="DoctorAppointments.php" class="nav__link">Appointments</a></li>
      <li><a href="DoctorPrescription.php" class="nav__link">Prescription</a></li>
      <li><a href="DoctorInfo.php" class="nav__link">DoctorInfo</a></li>
      <li>
        <a href="" class="nav__link" id="theme-button">
                <i class="ri-sun-line" id="theme-icon"></i> Dark/Light Mode
              </a>
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

</body>
</html>
