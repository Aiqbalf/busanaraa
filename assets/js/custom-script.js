document.addEventListener('DOMContentLoaded', function() {
  // Tombol "Sewa"/"Selengkapnya" sederhana
  document.querySelectorAll('.btn-primary').forEach(btn => {
    btn.addEventListener('click', function(e) {
      // jika href kosong atau link internal, biarkan default.
      // disini contoh sederhana: beri efek klik
      btn.style.transform = 'translateY(-2px)';
      setTimeout(()=> btn.style.transform = '', 180);
    });
  });

  // Simple mobile nav toggle jika header.php punya tombol dengan id #navToggle
  var navToggle = document.getElementById('navToggle');
  if (navToggle) {
    navToggle.addEventListener('click', function() {
      var nav = document.querySelector('.nav');
      if (nav) nav.classList.toggle('open');
    });
  }
});

// Toggle nav mobile
const navToggle = document.getElementById('navToggle');
const nav = document.querySelector('.nav');
if (navToggle && nav) {
  navToggle.addEventListener('click', () => {
    nav.classList.toggle('open');
  });
}


// ====== REGISTER PAGE SCRIPT ======

// Validasi password konfirmasi di sisi klien
document.addEventListener("DOMContentLoaded", function() {
  const form = document.querySelector("form");
  const pass = document.getElementById("pass");
  const conf = document.getElementById("conf");

  form.addEventListener("submit", function(e) {
    if (pass.value !== conf.value) {
      alert("Password dan Konfirmasi Password tidak sama!");
      conf.focus();
      e.preventDefault();
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const emailInput = document.querySelector("#emailid");
  const statusSpan = document.querySelector("#user-availability-status");

  if (emailInput) {
    emailInput.addEventListener("blur", () => {
      fetch("validasi_register.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "emailid=" + encodeURIComponent(emailInput.value)
      })
        .then(res => res.text())
        .then(data => {
          statusSpan.innerHTML = data;
        });
    });
  }
});


// ====== HUBUNGI KAMI PAGE SCRIPT ======

// Animasi sederhana saat form muncul
document.addEventListener("DOMContentLoaded", () => {
  const sections = document.querySelectorAll(".form-section, .info-section");
  sections.forEach((sec, i) => {
    sec.style.opacity = 0;
    sec.style.transform = "translateY(30px)";
    setTimeout(() => {
      sec.style.transition = "all 0.6s ease";
      sec.style.opacity = 1;
      sec.style.transform = "translateY(0)";
    }, 300 * i);
  });
});


// ====== BOOKING PAGE SCRIPT ======


function validasiForm() {
  const from = document.forms["sewa"]["fromdate"].value;
  const to = document.forms["sewa"]["todate"].value;
  const now = document.forms["sewa"]["now"].value;

  if (to < from) {
    alert("Tanggal selesai harus lebih besar dari tanggal mulai sewa!");
    return false;
  }
  if (from < now) {
    alert("Tanggal sewa minimal H-1!");
    return false;
  }
  return true;
}

// ====== BOOKING READY PAGE SCRIPT ======

function validasiForm() {
  const from = document.forms["sewa"]["fromdate"].value;
  const to = document.forms["sewa"]["todate"].value;

  if (to < from) {
    alert("Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.");
    return false;
  }
  return true;
}


// ====== BOOKING DETAIL PAGE SCRIPT ======

document.addEventListener('DOMContentLoaded', () => {
  const btn = document.querySelector('.btn-cetak');
  if (btn) {
    btn.addEventListener('click', () => {
      console.log("Mencetak detail sewa...");
    });
  }
});

