function openForm(id) {
  document.getElementById('inkoopFormulierID').classList.add("inkoopFormulierActive");
  document.getElementById('submitNewProd').setAttribute("name","submitNewProd"+id);
  return false;
}

function closeForm() {
  document.getElementById('inkoopFormulierID').classList.remove("inkoopFormulierActive");
  return false;
}
