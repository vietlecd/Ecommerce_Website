document.addEventListener("DOMContentLoaded", () => {
  // Mobile menu toggle
  const mobileMenuBtn = document.querySelector(".mobile-menu-btn")
  const navMenu = document.querySelector("nav ul")

  if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener("click", () => {
      navMenu.classList.toggle("active")
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
  const initChatWidget = () => {
    if (window.__chatWidgetBootstrapped) {
      return
    }

    const chatWidget = document.getElementById("chat-widget")
    if (!chatWidget) {
      return
    }

    const chatButton = chatWidget.querySelector("#chat-button")
    const chatWindow = chatWidget.querySelector("#chat-window")
    const chatClose = chatWidget.querySelector("#chat-close")
    const chatInput = chatWidget.querySelector("#chat-input")
    const chatSend = chatWidget.querySelector("#chat-send")
    const chatMessages = chatWidget.querySelector("#chat-messages")
    const chatForm = chatWidget.querySelector(".chat-input-group")
    const webhookBaseUrl = "index.php"

    if (!chatButton || !chatWindow || !chatClose || !chatInput || !chatSend || !chatMessages || !chatForm) {
      return
    }

    window.__chatWidgetBootstrapped = true

    const openChat = () => {
      chatWindow.classList.add("is-open")
      chatWindow.setAttribute("aria-hidden", "false")
      chatButton.setAttribute("aria-expanded", "true")
    }

    const closeChat = () => {
      chatWindow.classList.remove("is-open")
      chatWindow.setAttribute("aria-hidden", "true")
      chatButton.setAttribute("aria-expanded", "false")
    }

    const scrollToBottom = () => {
      chatMessages.scrollTop = chatMessages.scrollHeight
    }

    const addMessage = (text, isUser = false) => {
      const bubble = document.createElement("div")
      bubble.className = `chat-bubble ${isUser ? "user" : "bot"}`
      bubble.textContent = text
      chatMessages.appendChild(bubble)
      scrollToBottom()
    }

    const addLoadingMessage = () => {
      const bubble = document.createElement("div")
      bubble.className = "chat-bubble bot"
      bubble.id = "chat-loading"
      bubble.innerHTML = '<span class="chat-loading">Curating recommendations...</span>'
      chatMessages.appendChild(bubble)
      scrollToBottom()
    }

    const removeLoadingMessage = () => {
      const bubble = document.getElementById("chat-loading")
      if (bubble) {
        bubble.remove()
      }
    }

    const buildProductCard = (item) => {
      const card = document.createElement("div")
      card.className = "chat-product-card"

      if (item.img) {
        const img = document.createElement("img")
        img.src = item.img
        img.alt = item.h3 || "Product image"
        card.appendChild(img)
      }

      const content = document.createElement("div")
      content.className = "chat-product-content"

      if (item.h3) {
        const title = document.createElement("div")
        title.className = "chat-product-title"
        title.textContent = item.h3
        content.appendChild(title)
      }

      if (item.desc) {
        const desc = document.createElement("div")
        desc.className = "chat-product-desc"
        desc.textContent = item.desc
        content.appendChild(desc)
      }

      const metaItems = []
      if (item.price) metaItems.push(item.price)
      if (item.size) metaItems.push(`Size: ${item.size}`)
      if (item.stock) metaItems.push(`In stock: ${item.stock}`)

      if (metaItems.length) {
        const meta = document.createElement("div")
        meta.className = "chat-product-meta"
        meta.textContent = metaItems.join(" • ")
        content.appendChild(meta)
      }

      if (item.link) {
        const link = document.createElement("a")
        link.href = item.link
        link.target = "_blank"
        link.rel = "noopener noreferrer"
        link.className = "chat-product-link"
        link.textContent = "View details"
        content.appendChild(link)
      }

      card.appendChild(content)
      return card
    }

    const renderProductResponse = (data) => {
      const bubble = document.createElement("div")
      bubble.className = "chat-bubble bot"
      const container = document.createElement("div")
      container.className = "chat-rich-response"

      if (data.p) {
        const intro = document.createElement("p")
        intro.textContent = data.p
        container.appendChild(intro)
      }

      if (data.h2) {
        const heading = document.createElement("p")
        heading.className = "chat-rich-heading"
        heading.textContent = data.h2
        container.appendChild(heading)
      }

      if (Array.isArray(data.items) && data.items.length) {
        const list = document.createElement("div")
        list.className = "chat-product-list"
        data.items.forEach((item) => {
          list.appendChild(buildProductCard(item))
        })
        container.appendChild(list)
      }

      if (data.note) {
        const note = document.createElement("div")
        note.className = "chat-note"
        note.textContent = data.note
        container.appendChild(note)
      }

      bubble.appendChild(container)
      chatMessages.appendChild(bubble)
      scrollToBottom()
    }

    const sendMessage = () => {
      const message = chatInput.value.trim()
      if (!message) {
        return
      }

      addMessage(message, true)
      chatInput.value = ""
      chatSend.disabled = true
      addLoadingMessage()

      const url = new URL(webhookBaseUrl, window.location.origin)
      url.searchParams.set("controller", "chat")
      url.searchParams.set("action", "api")
      url.searchParams.set("chatInput", message)

      const controller = new AbortController()
      const timeoutId = window.setTimeout(() => controller.abort(), 200000)

      fetch(url.toString(), { signal: controller.signal })
        .then((response) => {
          window.clearTimeout(timeoutId)
          if (!response.ok) {
            throw new Error(`HTTP error: ${response.status}`)
          }
          return response.json()
        })
        .then((result) => {
          removeLoadingMessage()
          chatSend.disabled = false

          if (result.success && result.data) {
            const data = result.data
            if (data.p && Array.isArray(data.items)) {
              renderProductResponse(data)
            } else if (Array.isArray(data.items) && data.items.length) {
              renderProductResponse(data)
            } else if (data.p) {
              addMessage(data.p, false)
            } else if (data.message) {
              addMessage(data.message, false)
            } else {
              addMessage("Sorry, I cannot find the information.", false)
            }
          } else if (result.error || result.message) {
            addMessage(result.message || "Sorry, an error occurred.", false)
          } else {
            addMessage("Sorry, I cannot find the information.", false)
          }
        })
        .catch((error) => {
          window.clearTimeout(timeoutId)
          removeLoadingMessage()
          chatSend.disabled = false
          let errorMsg = "Sorry, an error occurred."
          if (error.name === "AbortError") {
            errorMsg = "Request timed out. Please try again."
          } else if (error.message.includes("NetworkError") || error.message.includes("Failed to fetch")) {
            errorMsg = "Cannot connect to server. Please check your network."
          } else if (error.message.includes("HTTP error")) {
            errorMsg = "Server responded with an error. Please try later."
          }
          addMessage(errorMsg, false)
        })
    }

    chatButton.addEventListener("click", (event) => {
      event.stopPropagation()
      if (chatWindow.classList.contains("is-open")) {
        closeChat()
      } else {
        openChat()
      }
    })

    chatClose.addEventListener("click", (event) => {
      event.stopPropagation()
      closeChat()
    })

    chatWindow.addEventListener("click", (event) => {
      event.stopPropagation()
    })

    document.addEventListener("click", (event) => {
      if (!chatWidget.contains(event.target)) {
        closeChat()
      }
    })

    chatSend.addEventListener("click", sendMessage)

    chatForm.addEventListener("submit", (event) => {
      event.preventDefault()
      sendMessage()
    })

    chatInput.addEventListener("keydown", (event) => {
      if (event.key === "Enter" && !event.shiftKey) {
        event.preventDefault()
        sendMessage()
      }
    })
  }

  initChatWidget()
  window.__initChatWidget = initChatWidget
})

