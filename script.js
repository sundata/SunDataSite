const navToggle = document.querySelector(".nav-toggle");
const siteNav = document.querySelector(".site-nav");
const contactForm = document.querySelector("#contactForm");
const contactStatus = document.querySelector("#contactStatus");

navToggle?.addEventListener("click", () => {
  const isOpen = siteNav.classList.toggle("open");
  navToggle.setAttribute("aria-expanded", String(isOpen));
});

siteNav?.addEventListener("click", (event) => {
  if (event.target instanceof HTMLAnchorElement) {
    siteNav.classList.remove("open");
    navToggle?.setAttribute("aria-expanded", "false");
  }
});

contactForm?.addEventListener("submit", (event) => {
  event.preventDefault();
  const formData = new FormData(contactForm);
  const submitButton = contactForm.querySelector('button[type="submit"]');
  const messages = {
    sending: contactForm.dataset.sending || "Sending...",
    success: contactForm.dataset.success || "Sent.",
    error: contactForm.dataset.error || "Could not send your message.",
  };

  setContactStatus(messages.sending, "");
  if (submitButton) {
    submitButton.disabled = true;
  }

  fetch(contactForm.action, {
    method: "POST",
    body: formData,
    headers: {
      Accept: "application/json",
    },
  })
    .then(async (response) => {
      const data = await response.json().catch(() => ({}));

      if (!response.ok || !data.ok) {
        throw new Error(data.message || messages.error);
      }

      contactForm.reset();
      setContactStatus(messages.success, "success");
    })
    .catch((error) => {
      setContactStatus(error.message, "error");
    })
    .finally(() => {
      if (submitButton) {
        submitButton.disabled = false;
      }
    });
});

function setContactStatus(message, type) {
  if (!contactStatus) return;

  contactStatus.textContent = message;
  contactStatus.className = `form-status${type ? ` ${type}` : ""}`;
}
