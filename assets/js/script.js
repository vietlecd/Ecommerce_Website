document.addEventListener("DOMContentLoaded", () => {
  // Mobile menu toggle
  const mobileMenuBtn = document.querySelector(".mobile-menu-btn")
  const navMenu = document.querySelector("nav ul")

  if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener("click", () => {
      navMenu.classList.toggle("active")
      // mobileMenuBtn.classList.toggle("active")
    })
  }

  // Quantity selector for product detail page
  const minusBtn = document.querySelector(".quantity-minus")
  const plusBtn = document.querySelector(".quantity-plus")
  const quantityInput = document.querySelector(".quantity-input")

  if (minusBtn && plusBtn && quantityInput) {
    minusBtn.addEventListener("click", () => {
      const currentValue = Number.parseInt(quantityInput.value)
      if (currentValue > 1) {
        quantityInput.value = currentValue - 1
      }
    })

    plusBtn.addEventListener("click", () => {
      const currentValue = Number.parseInt(quantityInput.value)
      quantityInput.value = currentValue + 1
    })
  }

  // Image preview for admin product form
  const imageInput = document.querySelector("#product-image")
  const imagePreview = document.querySelector("#image-preview")

  if (imageInput && imagePreview) {
    imageInput.addEventListener("change", function () {
      const file = this.files[0]
      if (file) {
        const reader = new FileReader()
        reader.onload = (e) => {
          imagePreview.src = e.target.result
          imagePreview.style.display = "block"
        }
        reader.readAsDataURL(file)
      }
    })
  }
})

