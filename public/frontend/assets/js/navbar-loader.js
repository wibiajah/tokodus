function loadNavbar(callback) {
  const container = document.getElementById("navbar-container");
  if (!container || container.dataset.static === "true") return;

  fetch("/frontend/components/navbar.html")
    .then((res) => {
      if (!res.ok) throw new Error("Gagal memuat navbar");
      return res.text();
    })
    .then((html) => {
      container.innerHTML = html;
      initializeNavbar();
      if (typeof callback === "function") callback();
    })
    .catch((err) => console.error("Error saat memuat navbar:", err));
}

function initializeNavbar() {
  const navbarNav = document.querySelector(".navbar-nav");
  const hamburger = document.querySelector("#hamburger-menu");
  const navbar = document.querySelector("nav");

  let isMenuOpen = false;

  if (hamburger && navbarNav) {
    hamburger.onclick = () => {
      navbarNav.classList.toggle("active");
      isMenuOpen = navbarNav.classList.contains("active");

      if (isMenuOpen && navbar) {
        navbar.style.top = "0";
      }
    };

    document.addEventListener("click", function (e) {
      if (!hamburger.contains(e.target) && !navbarNav.contains(e.target)) {
        navbarNav.classList.remove("active");
        isMenuOpen = false;
      }
    });
  }

  let lastScrollTop = 0;

  if (navbar) {
    window.addEventListener("scroll", function () {
      if (isMenuOpen) {
        navbar.style.top = "0";
        return;
      }

      let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      if (scrollTop > lastScrollTop) {
        navbar.style.top = "-80px";
      } else {
        navbar.style.top = "0";
      }
      lastScrollTop = scrollTop;
    });
  }

  if (window.feather) {
    feather.replace();
  }
}

// Auto load on page load
document.addEventListener("DOMContentLoaded", loadNavbar);