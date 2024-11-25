document.addEventListener("DOMContentLoaded", () => {
    const darkModeToggle = document.getElementById("darkModeToggle");
    const body = document.body;
    const sunIcons = document.querySelectorAll(".sun-icon");
    const moonIcons = document.querySelectorAll(".moon-icon");

    // Check and apply the saved theme from localStorage
    const currentTheme = localStorage.getItem("theme");
    if (currentTheme === "dark") {
        body.classList.add("dark-mode");
        darkModeToggle.checked = true;
        toggleIcons("dark");
    }

    // Add event listener for dark mode toggle
    darkModeToggle.addEventListener("change", () => {
        if (darkModeToggle.checked) {
            body.classList.add("dark-mode");
            localStorage.setItem("theme", "dark");
            toggleIcons("dark");
        } else {
            body.classList.remove("dark-mode");
            localStorage.setItem("theme", "light");
            toggleIcons("light");
        }
    });

    // Function to toggle between sun and moon icons
    function toggleIcons(mode) {
        if (mode === "dark") {
            sunIcons.forEach(icon => (icon.style.display = "none"));
            moonIcons.forEach(icon => (icon.style.display = "block"));
        } else {
            sunIcons.forEach(icon => (icon.style.display = "block"));
            moonIcons.forEach(icon => (icon.style.display = "none"));
        }
    }
});