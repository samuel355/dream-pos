// Function to preview image
function previewEditImage(input) {
  const preview = document.getElementById("product-image-preview");

  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      preview.src = e.target.result;
      preview.style.display = "block";
    };

    reader.readAsDataURL(input.files[0]);
  } else {
    preview.style.display = "none";
  }
}