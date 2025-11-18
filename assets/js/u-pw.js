function validasiPassword() {
  const newPass = document.getElementById("new").value;
  const confirmPass = document.getElementById("confirm").value;

  if (newPass !== confirmPass) {
    alert("Password baru dan konfirmasi tidak sama!");
    return false;
  }

  if (newPass.length < 6) {
    alert("Password baru minimal 6 karakter!");
    return false;
  }

  return true;
}
