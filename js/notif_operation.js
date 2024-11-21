function showNotification(message) {
    const notificationBox = document.getElementById("notification");
    const notificationMessage = document.getElementById("notificationMessage");

    notificationMessage.textContent = message;
    notificationBox.classList.add("show"); // Menambahkan class untuk menampilkan notifikasi

    // Menyembunyikan notifikasi setelah 5 detik
    setTimeout(() => {
        notificationBox.classList.remove("show"); // Menghapus class setelah beberapa waktu
    }, 5000);
}

function isOperatingHours() {
    const now = new Date();
    const day = now.getDay(); // 0 (Minggu) - 6 (Sabtu)
    const hours = now.getHours();
    const minutes = now.getMinutes();

    if (day === 0) {
        return false; // Tidak beroperasi pada hari Minggu
    }

    if (day >= 1 && day <= 4) {
        if ((hours > 7 || (hours === 7 && minutes >= 30)) && hours < 17) {
            return true; // Senin-Kamis jam 07.30-17.00
        }
    }

    if (day === 5) {
        if ((hours > 7 || (hours === 7 && minutes >= 30)) && hours < 11.30) {
            return true; // Jumat jam 07.30-11.30
        }
        if (hours >= 14 && hours < 17) {
            return true; // Jumat jam 14.00-17.00
        }
    }

    return false; // Jika tidak dalam jam operasional
}

// Menampilkan notifikasi jika sedang jam operasional
if (isOperatingHours()) {
    showNotification("Kami sedang beroperasi. Silakan hubungi kami.");
} else {
    showNotification("Kami sedang tidak beroperasi. Silakan hubungi kami selama jam operasional.");
}