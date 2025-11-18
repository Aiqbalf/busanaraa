document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  
  form.addEventListener("submit", (e) => {
    const fileInput = form.querySelector('input[type="file"]');
    if (!fileInput.files.length) {
      alert("Silakan upload bukti pembayaran sebelum submit.");
      e.preventDefault();
    }
  });
});
