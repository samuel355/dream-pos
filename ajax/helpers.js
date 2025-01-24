function allowOnlyNumbers(event) {
  const keyCode = event.keyCode || event.which;

  // Allow: Backspace, Delete, Tab, Escape, Enter, and Arrow keys
  if (
    [8, 9, 13, 27, 46].includes(keyCode) ||
    (keyCode >= 35 && keyCode <= 40) // Home, End, Left, Right
  ) {
    return; // Allow these keys
  }

  // Allow: 0-9 (numbers)
  if (keyCode >= 48 && keyCode <= 57) {
    return; // Allow numeric keys
  }

  // Prevent other keys
  event.preventDefault();
}
const price = document.getElementById("price");
if (price) {
  price.addEventListener("keydown", allowOnlyNumbers);
}
