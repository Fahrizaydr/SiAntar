window.addEventListener('DOMContentLoaded', event => {
    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple, {
            // Konfigurasi lainnya
            paging: false, // Menonaktifkan halaman
            searching: false // Menonaktifkan pencarian
        });
    }
});
