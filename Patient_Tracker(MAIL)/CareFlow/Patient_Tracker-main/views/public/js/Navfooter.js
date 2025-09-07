/*=============== SHOW MENU ===============*/
const showMenu = (toggleId, navId) => {
    const toggle = document.getElementById(toggleId),
        nav = document.getElementById(navId);

    toggle.addEventListener('click', () => {
        // Add show-menu class to nav menu
        nav.classList.toggle('show-menu');

        // Add show-icon to show and hide the menu icon
        toggle.classList.toggle('show-icon');
    });
};

showMenu('nav-toggle', 'nav-menu');

/*=============== DARK AND LIGHT MODE ===============*/
const themeButton = document.getElementById("theme-button");
const themeIcon = document.getElementById("theme-icon");
const body = document.body;

// Check saved theme preference from localStorage
const savedTheme = localStorage.getItem("theme");
if (savedTheme) {
  body.dataset.theme = savedTheme;
  themeIcon.classList.toggle("ri-sun-line", savedTheme === "light");
  themeIcon.classList.toggle("ri-moon-line", savedTheme === "dark");
} else {
  // Default to light mode if no preference is saved
  body.dataset.theme = "light";
  themeIcon.classList.toggle("ri-sun-line", true);
  themeIcon.classList.toggle("ri-moon-line", false);
}

// Toggle theme and icon
themeButton.addEventListener("click", () => {
    const isDarkMode = body.dataset.theme === "dark";
    body.dataset.theme = isDarkMode ? "light" : "dark";
  
    // Switch icon between sun and moon
    themeIcon.classList.toggle("ri-sun-line", !isDarkMode);
    themeIcon.classList.toggle("ri-moon-line", isDarkMode);

    // Save theme preference to local storage
    localStorage.setItem("theme", body.dataset.theme);
});

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
