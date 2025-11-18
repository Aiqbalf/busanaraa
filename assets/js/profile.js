function validateForm(form) {
  const namePattern = /^[a-zA-Z .]+$/;
  if (!namePattern.test(form.fullname.value)) {
    alert("Nama hanya boleh berisi huruf dan spasi!");
    form.fullname.focus();
    return false;
  }
  return true;
}
