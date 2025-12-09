function loadFooter(callback) {
  const footerTarget = document.getElementById("footer-container");
  if (!footerTarget || footerTarget.dataset.static === "true") return;

  fetch("/frontend/components/footer.html")
    .then((res) => {
      if (!res.ok) throw new Error("Gagal memuat footer");
      return res.text();
    })
    .then((html) => {
      footerTarget.innerHTML = html;

      const yearSpan = document.getElementById("year");
      if (yearSpan) yearSpan.textContent = new Date().getFullYear();
      if (window.feather) feather.replace();

      if (typeof callback === "function") callback();
    })
    .catch((err) => console.error("Error saat memuat footer:", err));
}

// Auto load on page load
document.addEventListener("DOMContentLoaded", loadFooter);