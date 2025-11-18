// Efek animasi baris saat hover
document.addEventListener("DOMContentLoaded", () => {
  const rows = document.querySelectorAll("tbody tr");
  rows.forEach(row => {
    row.addEventListener("mouseenter", () => {
      row.style.transform = "scale(1.01)";
      row.style.transition = "0.2s ease";
    });
    row.addEventListener("mouseleave", () => {
      row.style.transform = "scale(1)";
    });
  });
});
