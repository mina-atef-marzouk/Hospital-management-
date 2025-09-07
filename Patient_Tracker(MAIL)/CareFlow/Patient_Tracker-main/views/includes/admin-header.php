<?php
    // include("../includes/session_check.php");
    // checkUserSession([3]);



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../public/css/layout1.css">
</head>
<nav>
<div class="sidebar">
            <div class="logo">
                <img src="../public/img/Medical Care Logo.png" alt="CareFlow Logo">
                <h2>CareFlow</h2>
            </div>
            <div class="profile_details">
                <div class="menu-container">
                    <div style="padding: 20px">
                        <div class="profile-container">
                            <img src="../public/img/user.png" alt="User Profile" width="30%" style="border-radius:50%;">
                            <p class="profile-title">Administrator</p>
                            <p class="profile-subtitle">admin@gmail.com</p>
                            <div class="div-logout-btn">
                                <a href="../users/logout.php"><input type="button" value="Log out" class="logout-btn"></a>
                            </div>
                        </div>
                    </div>
                    <!-- Navigation Menu -->
                    <ul class="menu">
                        <li class="nav">
                            <span class="menu-icon-dashboard"></span>
                            <a href="../admin/dashboard.php" class="sidebar-link">Dashboard</a>
                        </li>
                        <li class="nav">
                            <span class="menu-icon-appoinment"></span>
                            <a href="../appointments/appointments.php" class="sidebar-link">Appointments</a>
                        </li>
                        <li class="nav">
                            <span class="menu-icon-patient"></span>
                            <a href="../patients/display_patients.php" class="sidebar-link">Patients</a>
                        </li>
                        
                        <li class="nav">
                            <span class="menu-icon-doctors"><img src="../public/img/icons/icons8-doctor-50.png" alt="doctor-icon" style="height:30px; width: 30px; margin-right:10px"></span>
                            <a href="../doctors/display_doctors.php" class="sidebar-link">Doctors</a>
                        </li>
                        
                        <li class="nav">
                            <span class="menu-icon-messages"></span>
                            <a href="#" class="sidebar-link">Messages</a>
                        </li>
                        <li class="nav">
                            <span class="menu-icon-report"></span>
                            <a href="#" class="sidebar-link">Reports</a>
                        </li>
                         <li class="nav">
                            <span class="menu-icon-report"></span>
                            <a href="../admin/payment2.php" class="sidebar-link">Payments</a>
                        </li>
                         <li class="nav">
                            <span class="menu-icon-report"></span>
                            <a href="#" class="sidebar-link">Invoices</a>
                        </li>
                        <li class="nav">
                            <span class="menu-icon-settings"></span>
                            <a href="#" class="sidebar-link">Settings</a>
                        </li>
                    </ul>
                </div>
            </div>
 </div>

<!-- <button class="toggle-btn" onclick="toggleSidebar()">☰</button> -->
</nav>
 <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }
    </script>
<!-- <head>
    <link rel="stylesheet" href="../public/css/layout.css">
   <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }
    </script>
</head>
    <nav>
        <div class="container admin-nav sidebar">
                <div class="logo">
                    <img src="../public/img/Medical Care Logo.png" alt="CareFlow Logo">
                    <h2>CareFlow</h2>
                </div>
                <div class="profile_details">
                    <div class="menu-container">
                        <div>
                            <div style="padding:20px">
                                <div class="profile-container">
                                    <div style="align-items: center;">
                                        <div width="8%">
                                            <img src="../public/img/user.png" alt="User  Profile" width="30%" style="border-radius:50%">
                                        </div>
                                        <div style="padding:0px;margin:0px;">
                                            <p class="profile-title">Administrator</p>
                                            <p class="profile-subtitle">admin@gmail.com</p>
                                        </div>
                                    </div>
                                    <div class="div-logout-btn">
                                        <a href="#"><input type="button" value="Log out" class="logout-btn"></a>
                                    </div>
                                </div> 
                            </div>
                        </div> 
                    </div>  
                </div>  
                <div> 
                        <ul class="menu">
                            <li class="nav">
                                <span class="menu-icon-dashboard"></span>
                                <a href="layout.html" class="sidebar-link">Dashboard</a>
                            </li>
                            <li class="nav">
                                <span class="menu-icon-appoinment"></span>
                                <a href="../appointments/appointments.php" class="sidebar-link">Appointments</a>
                            </li>
                            <li class="nav">
                                <span class="menu-icon-patient"></span>
                                <a href="../patients/patients.php" class="sidebar-link">Patients</a>
                            </li>
                            <li class="nav">
                                <span class="menu-icon-messages"></span>
                                <a href="#" class="sidebar-link">Messages</a>
                            </li>
                            <li class="nav">
                                <span class="menu-icon-report"></span>
                                <a href="#" class="sidebar-link">Report</a>
                            </li>
                            <li class="nav">
                                <span class="menu-icon-settings"></span>
                                <a href="#" class="sidebar-link">Settings</a>
                            </li>
                        </ul>
                    </div>
            <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
            </div>
    
    </nav> 
 -->
