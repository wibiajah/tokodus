  /* ==========================================================================
    INIT - DOMContentLoaded
    ========================================================================== */
  document.addEventListener("DOMContentLoaded", function () {
    /* ==============================
      LOAD SPA CONTENT
      ============================== */
    function loadContent(path, skipPushState = false) {
      const content = document.getElementById("content");
      if (!content) return console.error("Container #content tidak ditemukan.");

      content.classList.add("fade-out");
      const currentHeight = content.offsetHeight;
      content.style.minHeight = `${currentHeight}px`;

      let page = path || "index";
      let anchor = null;

      if (page.includes("#")) [page, anchor] = page.split("#");
      const sanitizedPage = page.endsWith(".html") ? page : `${page}.html`;

      setTimeout(() => {
        fetch(sanitizedPage)
          .then((response) => {
            if (!response.ok) throw new Error("Page not found");
            return response.text();
          })
          .then((html) => {
            document
              .querySelectorAll('[data-dynamic="true"]')
              .forEach((el) => el.remove());

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");
            const newContent = doc.getElementById("content");
            if (!newContent) throw new Error("No #content found in loaded HTML");

            // Inject styles
            doc.querySelectorAll('link[rel="stylesheet"]').forEach((link) => {
              const href = link.getAttribute("href");
              if (
                !document.querySelector(`link[rel="stylesheet"][href="${href}"]`)
              ) {
                const newLink = document.createElement("link");
                newLink.rel = "stylesheet";
                newLink.href = href;
                newLink.setAttribute("data-dynamic", "true");
                document.head.appendChild(newLink);
              }
            });

            // Inject scripts if not loaded
            doc.querySelectorAll("script[src]").forEach((script) => {
              const src = script.getAttribute("src");
              if (!document.querySelector(`script[src="${src}"]`)) {
                const newScript = document.createElement("script");
                newScript.src = src;
                newScript.setAttribute("data-page-script", "true");
                document.body.appendChild(newScript);
              }
            });

            content.innerHTML = newContent.innerHTML;
            content.classList.remove("fade-out");
            content.style.minHeight = "";

            executePageScript(page);
            window.scrollTo({ top: 0, behavior: "smooth" });

            if (!skipPushState) {
              const newPath =
                page === "index" && !anchor
                  ? "/"
                  : `/${anchor ? page + "#" + anchor : page}`;
              history.pushState({ page: path }, "", newPath);
            }
// ❌ COMMENT - Navbar & Footer sudah ada di layout
// if (typeof loadNavbar === "function") loadNavbar();
// if (typeof loadFooter === "function") loadFooter();

            if (page === "index" || page === "index.html") {
              if (typeof loadBennerSliderFromJSON === "function")
                loadBennerSliderFromJSON();
              if (typeof loadBestSellerProducts === "function")
                loadBestSellerProducts();
            }

            if (anchor) {
              // Pindahkan scroll ke akhir load footer/nav
              const waitForAllComponents = setInterval(() => {
                const target = document.getElementById(anchor);
                const footerLoaded =
                  document.getElementById("footer-container")?.innerHTML?.trim()
                    .length > 0;

                if (target && target.offsetHeight > 0 && footerLoaded) {
                  target.scrollIntoView({ behavior: "smooth" });
                  sessionStorage.removeItem("scrollTo");
                  clearInterval(waitForAllComponents);
                }
              }, 100);

              // Stop total setelah 5 detik
              setTimeout(() => clearInterval(waitForAllComponents), 5000);
            }
          })
          .catch((error) => {
            content.innerHTML = "<h1>Page not found</h1>";
            content.classList.remove("fade-out");
            content.style.minHeight = "";
            console.error(error);
          });
      }, 300);
    }

    /* ==============================
      PAGE SCRIPT HANDLER
      ============================== */
    const pageHandlers = {
      service: () => loadPageScript("service.js"),
      catalog: () => loadPageScript("catalog.js"),
      project: () => loadPageScript("project.js"),
      resources: () => loadPageScript("resource.js"),
    };

    function loadPageScript(filename) {
      console.log(`Loading script: ${filename}`);
      const script = document.createElement("script");
      script.src = `assets/js/${filename}`;
      script.defer = true;
      document.body.appendChild(script);
    }

    function executePageScript(page) {
      const handler = pageHandlers[page];
      if (typeof handler === "function") {
        handler();
      } else {
        console.warn(`No handler found for page: ${page}`);
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      const pageName = document.body.getAttribute("data-page");
      if (pageName) {
        executePageScript(pageName);
      }
    });

    /* ==============================
      GLOBAL UTILITIES
      ============================== */
    window.navigateTo = (section) => (window.location.href = "#" + section);
    window.navigateToDomain = () => (window.location.href = "/");
    window.scrollToTarget = (targetId) => {
      const targetElement = document.getElementById(targetId);
      if (targetElement) targetElement.scrollIntoView({ behavior: "smooth" });
    };
    window.navigateToHomeAndScroll = (targetId) => {
      const isIndex =
        window.location.pathname === "/" ||
        window.location.pathname === "/index.html";

      if (!isIndex) {
        sessionStorage.setItem("scrollTo", targetId);
        window.location.href = "/";
      } else {
        scrollToTarget(targetId);
      }
    };

    window.loadContent = loadContent;

    /* ==============================
      ON HISTORY BACK/FORWARD
      ============================== */
    window.addEventListener("popstate", (event) => {
      if (event.state && event.state.path) loadContent(event.state.path);
    });

    const targetId = sessionStorage.getItem("scrollTo");

    if (targetId) {
      const waitForTarget = () => {
        const target = document.getElementById(targetId);
        const isContentLoaded =
          document.getElementById("footer-container")?.innerHTML?.trim().length >
            0 &&
          document.getElementById("navbar-container")?.innerHTML?.trim().length >
            0 &&
          document.getElementById("benner-slides")?.children.length > 0;

        if (target && target.offsetHeight > 0 && isContentLoaded) {
          target.scrollIntoView({ behavior: "smooth" });
          sessionStorage.removeItem("scrollTo");
        } else {
          setTimeout(waitForTarget, 100);
        }
      };

      waitForTarget();
    }
    console.log("Checking scroll to", targetId);

    /* ==============================
      INITIALIZE ON LOAD
      ============================== */
  // ❌ COMMENT - Sudah pakai @include di Blade
  // if (typeof loadNavbar === "function") loadNavbar();
  // if (typeof loadFooter === "function") loadFooter();
  

    const path = window.location.pathname.replace("/", "").replace(".html", "");
    const fullPath = location.hash ? `${path}#${location.hash.slice(1)}` : path;
    const validPages = ["service", "catalog", "projects", "resources"];

    if (validPages.includes(path)) {
      loadContent(fullPath);
    } else {
      if (typeof loadBennerSliderFromJSON === "function")
        loadBennerSliderFromJSON();
      if (typeof loadBestSellerProducts === "function") loadBestSellerProducts();
    }
  });

  /* ==========================================================================
    SECTION: BENNER (BANNER) SLIDER
    ========================================================================== */
  function loadBennerSliderFromJSON() {
    fetch("/frontend/data/banner.json")
      .then((res) => res.json())
      .then((slides) => {
        const container = document.getElementById("benner-slides");
        if (!container) return;

        container.innerHTML = ""; // Bersihkan isi lama

        slides.forEach((slide, i) => {
          const variant = slide.variant || `benner${i + 1}`;
          const contentClass = `content${i + 1}`;
          const imageClass = `image-${i + 1}`;
          const ctaClass = `cta-${i + 1}`;
          const backgroundClass = `background-${variant}`;

          let labelsHTML = "";
          if (slide.labels && Array.isArray(slide.labels)) {
            labelsHTML = `
              <div class="labels">
                ${slide.labels
                  .map((label) => `<div class="label">${label}</div>`)
                  .join("")}
              </div>`;
          }

          const termsHTML = slide.terms
            ? `<div class="terms">${slide.terms}</div>`
            : "";

          const content = `
            <div class="${contentClass}" style="position: relative; z-index: 3;">
              <h1>${slide.title}</h1>
              <h2>${slide.subtitle}</h2>
              ${labelsHTML}
              <a href="${slide.ctaLink}" class="${ctaClass}" data-link style="position: relative; z-index: 4;">${slide.cta}</a>
              ${termsHTML}
            </div>
          `;

          const image = `
            <img class="${imageClass}" src="${slide.image}" alt="Slide ${
            i + 1
          } Image"
                style="position: relative; z-index: 1; pointer-events: none;" />
          `;

          const background = `<div class="${backgroundClass}" style="position: absolute; z-index: 0;"></div>`;

          const slideEl = document.createElement("div");
          slideEl.className = variant;
          slideEl.style.position = "relative";
          slideEl.innerHTML = content + image + background;

          container.appendChild(slideEl);
        });

        // Reaktifkan SPA-link untuk semua CTA banner
        document.querySelectorAll("[data-link]").forEach((link) => {
          link.addEventListener("click", function (e) {
            e.preventDefault();
            const href = this.getAttribute("href");
            if (href && href.startsWith("/")) {
              const target = href.replace("/", ""); // /stores → stores
              navigateToHomeAndScroll(target);
            }
          });
        });

        initializeSlider(); // Jalankan slider setelah banner dimuat
      })
      .catch((err) => {
        console.error("Gagal memuat banner.json:", err);
      });
  }

  /* ==========================================================================
    SECTION: SLIDER HANDLER
    ========================================================================== */
  let currentSlide = 1;
  let slideInterval;
  let autoSlide = true;
  let isTransitioning = false;
  let sliderInitialized = false;

  initializeSlider = () => {
    if (sliderInitialized) return;
    sliderInitialized = true;

    const slidesBennerContainer = document.querySelector(".benner-slides");
    const dots = document.querySelectorAll(".dots .dot");
    const prevBtn = document.querySelector(".prev");
    const nextBtn = document.querySelector(".next");

    if (!slidesBennerContainer || dots.length === 0) return;

    let bennerSlides = Array.from(slidesBennerContainer.children);
    let totalBennerSlides = bennerSlides.length;

    const firstClone = bennerSlides[0].cloneNode(true);
    const lastClone = bennerSlides[totalBennerSlides - 1].cloneNode(true);
    slidesBennerContainer.appendChild(firstClone);
    slidesBennerContainer.insertBefore(lastClone, bennerSlides[0]);

    let updatedSlides = Array.from(slidesBennerContainer.children);
    let totalSlides = updatedSlides.length;

    slidesBennerContainer.style.transform = `translateX(-100%)`;

    function updateDots() {
      let dotIndex = currentSlide - 1;
      if (currentSlide === 0) dotIndex = totalBennerSlides - 1;
      if (currentSlide === totalSlides - 1) dotIndex = 0;

      dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === dotIndex);
      });
    }

    function goToSlide(index) {
      if (isTransitioning) return;
      isTransitioning = true;
      currentSlide = index;

      slidesBennerContainer.style.transition = "transform 0.5s ease-in-out";
      slidesBennerContainer.style.transform = `translateX(-${
        currentSlide * 100
      }%)`;

      slidesBennerContainer.addEventListener(
        "transitionend",
        () => {
          isTransitioning = false;

          if (currentSlide === totalSlides - 1) {
            slidesBennerContainer.style.transition = "none";
            currentSlide = 1;
            slidesBennerContainer.style.transform = `translateX(-100%)`;
          }

          if (currentSlide === 0) {
            slidesBennerContainer.style.transition = "none";
            currentSlide = totalBennerSlides;
            slidesBennerContainer.style.transform = `translateX(-${
              currentSlide * 100
            }%)`;
          }

          updateDots();
        },
        { once: true }
      );
    }

    function nextSlide() {
      goToSlide(currentSlide + 1);
    }

    function prevSlide() {
      goToSlide(currentSlide - 1);
    }

    function startAutoSlide() {
      if (slideInterval) clearInterval(slideInterval);
      slideInterval = setInterval(nextSlide, 5000);
      autoSlide = true;
    }

    function stopAutoSlide() {
      clearInterval(slideInterval);
      autoSlide = false;
    }

    if (nextBtn) {
      nextBtn.addEventListener("click", () => {
        stopAutoSlide();
        nextSlide();
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener("click", () => {
        stopAutoSlide();
        prevSlide();
      });
    }

    if (dots.length > 0) {
      dots.forEach((dot, index) => {
        dot.addEventListener("click", () => {
          stopAutoSlide();
          goToSlide(index + 1);
        });
      });
    }

    document.addEventListener("click", (e) => {
      if (
        !e.target.classList.contains("prev") &&
        !e.target.classList.contains("next") &&
        !autoSlide
      ) {
        startAutoSlide();
      }
    });

    startAutoSlide();
    updateDots();
  };

  /* ==========================================================================
    SECTION: BESTSELLER PRODUCTS
    ========================================================================== */
  function loadBestSellerProducts() {
    const container = document.getElementById("bestSellerSlider");
    if (!container) return;

    fetch("/frontend/data/products.json")
      .then((res) => res.json())
      .then((products) => {
        const bestsellers = products.filter((p) => p.tag.includes("bestseller"));
        container.innerHTML = bestsellers
          .map((product) => {
            // Render rating ⭐⭐⭐⭐⭐
            const ratingStars =
              "★".repeat(product.rating) + "☆".repeat(5 - product.rating);

            // Tentukan class kategori atau tipe
            const categoryClass =
              product.kategori === "lebaran"
                ? "product-lebaran"
                : product.kategori === "christmas"
                ? "product-christmas"
                : product.kategori === "imlek"
                ? "product-imlek"
                : product.tipe.toLowerCase() === "masterbox"
                ? "product-masterbox"
                : "product-innerbox";

            return `
        <div class="product-card">
          <div class="product-image">
            <img alt="${product.kode}" class="default-image" src="${
              product.image_default
            }" />
            <img alt="${product.kode}" class="hover-image" src="${
              product.image_hover
            }" />
          </div>
          <div class="${categoryClass}">
            <a href="#">${product.kode}</a>
          </div>
          <div class="product-detail">
            <div class="product-title"><p>${product.tipe}</p></div>
            <div class="ratings" title="${product.rating} dari 5">
              ${ratingStars
                .split("")
                .map((star) => `<span class="star">${star}</span>`)
                .join("")}
            </div>
            <div class="product-deskripsi"><p>${product.dimensi}</p></div>
            <a href="#" class="card-icon"><i data-feather="heart"></i></a>
            <a href="#" class="card-icon"><i data-feather="send"></i></a>
            <a href="#" class="shopping-cart-icon"><i data-feather="shopping-cart"></i></a>
          </div>
        </div>
      `;
          })
          .join("");

        if (window.feather) feather.replace();
      })
      .catch((err) => {
        console.error("Gagal memuat produk:", err);
        container.innerHTML = "<p>Produk tidak tersedia.</p>";
      });
  }
  // ----- [2] INTERAKSI SLIDER (Drag & Snap) ----- //

  document.addEventListener("DOMContentLoaded", function () {
    const bestSellerSlider = document.getElementById("bestSellerSlider");
    let isDown = false;
    let startX;
    let scrollLeft;

    // Mouse Events
    bestSellerSlider.addEventListener("mousedown", (e) => {
      isDown = true;
      startX = e.pageX;
      scrollLeft = bestSellerSlider.scrollLeft;
      bestSellerSlider.classList.add("grabbing");
    });

    bestSellerSlider.addEventListener("mouseleave", () => {
      isDown = false;
      bestSellerSlider.classList.remove("grabbing");
    });

    bestSellerSlider.addEventListener("mouseup", () => {
      isDown = false;
      bestSellerSlider.classList.remove("grabbing");
      snapToCard();
    });

    bestSellerSlider.addEventListener("mousemove", (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX;
      const walk = (x - startX) * 2;
      bestSellerSlider.scrollLeft = scrollLeft - walk;
    });

    // Touch Events
    bestSellerSlider.addEventListener("touchstart", (e) => {
      startX = e.touches[0].clientX;
      scrollLeft = bestSellerSlider.scrollLeft;
    });

    bestSellerSlider.addEventListener("touchmove", (e) => {
      const x = e.touches[0].clientX;
      const walk = (startX - x) * 2;
      bestSellerSlider.scrollLeft = scrollLeft + walk;
    });

    bestSellerSlider.addEventListener("touchend", snapToCard);

    // Snap ke produk terdekat
    function snapToCard() {
      requestAnimationFrame(() => {
        const cardWidth = 335 + 30; // lebar card + margin
        const scrollPos = bestSellerSlider.scrollLeft;
        const snapIndex = Math.round(scrollPos / cardWidth);
        bestSellerSlider.scrollLeft = snapIndex * cardWidth;
      });
    }
  });

  // ============================
  //     END PROMO SLIDER
  // ============================
  // ==============================
  // == Special Edition Paginasi ==
  // ==============================

  const perPage = 8;
  const state = {
    newrelease: { page: 1, products: [] },
    lebaran: { page: 1, products: [] },
    christmas: { page: 1, products: [] },
    imlek: { page: 1, products: [] },
  };

  async function loadSpecialEditionData() {
    const res = await fetch("/frontend/data/products.json");
    const data = await res.json();

    ["newrelease", "lebaran", "christmas", "imlek"].forEach((cat) => {
      state[cat].products = data.filter(
        (p) => Array.isArray(p.tag) && p.tag.includes(cat)
      );
      renderSpecialEdition(cat);
      renderPagination(cat);
    });
  }

  function renderSpecialEdition(cat) {
    const container = document.getElementById(cat);
    if (!container) return;
    container.innerHTML = "";

    const start = (state[cat].page - 1) * perPage;
    const end = start + perPage;
    const productsToShow = state[cat].products.slice(start, end);

    productsToShow.forEach((product) => {
      const tagClasses = Array.isArray(product.tag)
        ? product.tag.join(" ")
        : product.tag;

      const ratingStars = "★".repeat(product.rating).padEnd(5, "☆");

      const card = document.createElement("article");
      card.className = `product-card ${product.kategori} ${tagClasses}`;

      card.innerHTML = `
        <div class="product-image">
          <img alt="${product.kode}" class="default-image" src="${
        product.image_default
      }" />
          <img alt="${product.kode}" class="hover-image" src="${
        product.image_hover
      }" />
        </div>
        <div class="${
          product.tipe.toLowerCase() === "masterbox"
            ? "product-masterbox"
            : product.tipe.toLowerCase() === "innerbox"
            ? "product-innerbox"
            : product.tipe.toLowerCase() === "lebaran"
            ? "product-lebaran"
            : product.tipe.toLowerCase() === "natal"
            ? "product-christmas"
            : product.tipe.toLowerCase() === "imlek"
            ? "product-imlek"
            : ""
        }">
          <a href="#">${product.kode}</a>
        </div>
        <div class="product-detail">
          <div class="product-title"><p>${product.tipe}</p></div>
          <div class="ratings" title="${product.rating} dari 5">
            ${ratingStars
              .split("")
              .map((star) => `<span class="star">${star}</span>`)
              .join("")}
          </div>
          <div class="product-deskripsi"><p>${product.dimensi}</p></div>
          <a href="#" class="card-icon"><i data-feather="heart"></i></a>
          <a href="#" class="card-icon"><i data-feather="send"></i></a>
          <a href="#" class="shopping-cart-icon"><i data-feather="shopping-cart"></i></a>
        </div>
      `;

      container.appendChild(card);
    });

    // Replace icons with Feather icons
    if (typeof feather !== "undefined") feather.replace();
  }

  function renderPagination(cat) {
    const paginationContainer = document.getElementById(`pagination-${cat}`);
    if (!paginationContainer) return;
    paginationContainer.innerHTML = "";

    const totalPages = Math.ceil(state[cat].products.length / perPage);
    if (totalPages <= 1) return;

    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement("button");
      btn.textContent = i;
      btn.className = "page-number";
      if (i === state[cat].page) btn.classList.add("active");
      btn.addEventListener("click", () => {
        state[cat].page = i;
        renderSpecialEdition(cat);
        renderPagination(cat);
      });
      paginationContainer.appendChild(btn);
    }
  }

  function showContent(cat) {
    document
      .querySelectorAll(".spesial-edition")
      .forEach((el) => el.classList.remove("active"));
    document
      .querySelectorAll(".pagination")
      .forEach((el) => (el.style.display = "none"));

    document.getElementById(cat).classList.add("active");
    document.getElementById(`pagination-${cat}`).style.display = "flex";
  }

  document.addEventListener("DOMContentLoaded", () => {
    loadSpecialEditionData();
    showContent("newrelease");
  });

  // ==============================
  // == Ends Special Edition     ==
  // ==============================
  /* ==========================================================================
    SECTION: CATEGORY CARDS
    ========================================================================== */
  function loadCategoryCards() {
    const container = document.getElementById("categoryCardsContainer");
    if (!container) return;

    fetch("/frontend/components/category-cards.html")
      .then((res) => res.text())
      .then((html) => {
        container.innerHTML = html;
        initCategorySlider(); // ✅ panggil setelah konten dimuat
      })
      .catch((err) => {
        console.error("Gagal memuat category cards:", err);
        container.innerHTML = "<p>Kategori tidak tersedia.</p>";
      });
  }

  // ===========================
  // Category Section (Infinite Slider)
  // ===========================
  function initCategorySlider() {
    const categorySliderContainer = document.querySelector(
      ".category-slider-container"
    );
    const categorySlider = document.querySelector(".category-slider");

    let isDragging = false,
      startX = 0,
      currentTranslate = 0,
      prevTranslate = 0,
      autoScroll,
      isAutoScrolling = true;

    const cardWidth = 200;

    // Clone cards untuk efek infinite slide
    function cloneCards() {
      const cards = [...categorySlider.children];
      cards.forEach((card) => {
        let clone = card.cloneNode(true);
        categorySlider.appendChild(clone);
      });
    }

    cloneCards();

    const minTranslate = -cardWidth * (categorySlider.children.length / 2);
    const maxTranslate = 0;

    // Auto Scroll
    function startAutoScroll() {
      if (!isAutoScrolling) return;
      stopAutoScroll();
      autoScroll = setInterval(() => {
        currentTranslate -= cardWidth;
        if (currentTranslate < minTranslate) {
          resetSlider();
          return;
        }
        categorySlider.style.transition = "transform 0.5s ease-in-out";
        categorySlider.style.transform = `translateX(${currentTranslate}px)`;
      }, 2500);
    }

    function stopAutoScroll() {
      clearInterval(autoScroll);
    }

    function resetSlider() {
      categorySlider.style.transition = "none";
      requestAnimationFrame(() => {
        categorySlider.style.transform = `translateX(0px)`;
        currentTranslate = 0;
        prevTranslate = 0;
      });
    }

    startAutoScroll();

    // Mouse Drag Events
    categorySliderContainer.addEventListener("mouseenter", stopAutoScroll);
    categorySliderContainer.addEventListener("mouseleave", () => {
      if (!isDragging) {
        isAutoScrolling = true;
        startAutoScroll();
      }
    });

    categorySliderContainer.addEventListener("mousedown", (e) => {
      isDragging = true;
      isAutoScrolling = false;
      startX = e.pageX;
      stopAutoScroll();
      categorySliderContainer.classList.add("grabbing");
    });

    categorySliderContainer.addEventListener("mouseup", () => {
      if (isDragging) {
        isDragging = false;
        categorySliderContainer.classList.remove("grabbing");
        snapToNearest();
      }
    });

    categorySliderContainer.addEventListener("mousemove", (e) => {
      if (!isDragging) return;
      e.preventDefault();
      const x = e.pageX - startX;
      currentTranslate = prevTranslate + x;
      currentTranslate = Math.max(
        minTranslate,
        Math.min(maxTranslate, currentTranslate)
      );
      categorySlider.style.transform = `translateX(${currentTranslate}px)`;
    });

    // Snap ke kartu terdekat
    function snapToNearest() {
      let snapPoint = Math.round(currentTranslate / cardWidth) * cardWidth;
      snapPoint = Math.max(minTranslate, Math.min(maxTranslate, snapPoint));
      categorySlider.style.transition = "transform 0.3s ease-out";
      categorySlider.style.transform = `translateX(${snapPoint}px)`;
      prevTranslate = snapPoint;

      setTimeout(() => {
        if (!isDragging) {
          isAutoScrolling = true;
          startAutoScroll();
        }
      }, 300);
    }
  }

  /* ==========================================================================
    SECTION: OUR PARTNER LOGO
    ========================================================================== */
  function loadOurPartnerLogo() {
    const container = document.getElementById("our-partner-placeholder");
    if (!container) return;

    fetch("/frontend/components/our-partner-logo.html")
      .then((res) => res.text())
      .then((html) => {
        container.innerHTML = html;
        initPartnerLogoScroll(); //
      })
      .catch((err) => console.error("Gagal memuat partner logo:", err));
  }
  // ----- [2] INFINITE SCROLL LOGO + PAUSE ON HOVER ----- //

  function initPartnerLogoScroll() {
    const logoSliderContainer = document.querySelector(".logo-slider-container");
    const logoSlide = document.querySelector(".logo-slide");

    if (!logoSliderContainer || !logoSlide) return;

    // Gandakan isi untuk infinite scroll
    logoSlide.innerHTML += logoSlide.innerHTML;

    let scrollSpeed = 1;
    let isPaused = false;

    function scrollLogos() {
      if (!isPaused) {
        if (logoSliderContainer.scrollLeft >= logoSlide.scrollWidth / 2) {
          logoSliderContainer.scrollLeft = 0;
        }
        logoSliderContainer.scrollLeft += scrollSpeed;
      }

      requestAnimationFrame(scrollLogos);
    }

    scrollLogos();

    // Pause scroll saat hover
    logoSliderContainer.addEventListener("mouseenter", () => (isPaused = true));
    logoSliderContainer.addEventListener("mouseleave", () => (isPaused = false));
  }

  // ----- [3] INISIALISASI SAAT DOM READY ----- //

  document.addEventListener("DOMContentLoaded", () => {
    loadOurPartnerLogo();
  });

  // ============================
  //   END OUR PARTNER SECTION
  // ============================

  // ==============================
  // == Store Section Start ======
  // ==============================

  // ==============================
  // == Element Initialization ===
  // ==============================
  // Dropdown button and content elements
  const dropdownBtn = document.querySelector(".dropdown-btn");
  const dropdownContent = document.getElementById("dropdownContent");
  const storeInfo = document.getElementById("storeInfo");
  const storeMap = document.getElementById("storeMap");

  // Links to marketplace and WhatsApp
  const tokopediaLink = document.getElementById("tokopediaLink");
  const shopeeLink = document.getElementById("shopeeLink");
  const lazadaLink = document.getElementById("lazadaLink");
  const whatsappLink = document.getElementById("whatsappLink");

  // Default map of Bandung
  const defaultMapEmbed =
    "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63387.67678038912!2d107.5731161675113!3d-6.914864054387586!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6421e57f7a3%3A0x1f9c85fd11fc66b2!2sBandung%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1726500000000";

  // ==============================
  // == Store Data ===============
  // ==============================

  const stores = {
    tki: {
      name: "Tokodus Taman Kopo Indah",
      address:
        "Jl. Taman Kopo Indah 3 Blok F30, Mekar Rahayu, Kec. Margaasih, Kabupaten Bandung, Jawa Barat",
      phone: "+6281317255959",
      hours: "Senin - Jumat: 08:00 - 17:00, Sabtu  - Minggu: 08:00 - 16:00",
      tokopedia: "https://www.tokopedia.com/tokodusbdg",
      shopee: "https://shopee.co.id/tokodusbdg",
      lazada: "https://www.lazada.co.id/shop/tokodus/",
      whatsapp: "http://wa.me/6281317255959",
      mapEmbed:
        "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.348766866562!2d107.55030747360861!3d-6.968118468225218!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68efafefc76823%3A0x9e7c5cfafb88025a!2sTokodus%20Bandung%20%7C%20Pabrik%20Dus%20%2F%20Box%20%2F%20Kardus%20Custom%20dan%20stok%20terlengkap%20di%20Bandung!5e0!3m2!1sen!2sid!4v1726413067498!5m2!1sen!2sid",
    },
    cimahi: {
      name: "Tokodus Cimahi",
      address:
        "Jl. Jend. H. Amir Machmud No.481, Karangmekar, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40523",
      phone: "+628112013738",
      hours: "Senin - Jumat: 08:00 - 17:00, Sabtu - Minggu: 08:00 - 16:00",
      tokopedia: "https://tokopedia.com/tokoduscmh",
      shopee: "https://shopee.co.id/tokoduscmh",
      lazada: "Belum Tersedia",
      whatsapp: "https://wa.me/628112013738",
      mapEmbed:
        "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.1128934737585!2d107.54500487360697!3d-6.877075667287033!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e54221ce2c2d%3A0xf7e21e4afb4389c5!2sTokodus%20Cimahi%20%7C%20Pabrik%20Dus%20Box%20%2F%20Kardus%20Custom%20dan%20Stok%20Terlengkap%20di%20Cimahi!5e0!3m2!1sen!2sid!4v1726414230361!5m2!1sen!2sid",
    },
    cibaduyut: {
      name: "Tokodus Cibaduyut",
      address:
        "Jl. Terusan Cibaduyut No.78, Cangkuang Kulon, Kec. Dayeuhkolot, Kabupaten Bandung, Jawa Barat 40239",
      phone: "+6281290778668",
      hours: "Senin - Jumat: 08:00 - 17:00, Sabtu - Minggu: 08:00 - 16:00",
      tokopedia: "https://tokopedia.com/cibaduyut",
      shopee: "https://shopee.co.id/tokodus.cibaduyut",
      lazada: "Belum Tersedia",
      whatsapp: "https://wa.me/6281290778668",
      mapEmbed:
        "https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15841.4512792536!2d107.591762!3d-6.9664548!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e949836027e1%3A0xc85f2813c7f31e63!2sTokodus%20Cibaduyut!5e0!3m2!1sid!2sid!4v1726450983495!5m2!1sid!2sid",
    },
    pagarsih: {
      name: "Tokodus Pagarsih",
      address:
        "Jl. Pagarsih No.166a, Babakan Tarogong, Kec. Bojongloa Kaler, Kota Bandung, Jawa Barat 40231",
      phone: "+6282130138789",
      hours: "Senin - Jumat: 08:00 - 17:00, Sabtu - Minggu: 08:00 - 16:00",
      tokopedia: "https://www.tokopedia.com/tokoduspagarsih",
      shopee: "https://shopee.co.id/tkduspagarsih",
      lazada: "https://lazada.com/pagarsih",
      whatsapp: "https://wa.me/6282130138789",
      mapEmbed:
        "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.718491350924!2d107.58839082499657!3d-6.924215943075507!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e700255a16b9%3A0x712448cb7e00f584!2sTokodus%20Pagarsih!5e0!3m2!1sid!2sid!4v1726451633860!5m2!1sid!2sid",
    },
    buahbatu: {
      name: "Tokodus Buahbatu",
      address:
        "Jl. Margacinta No.44A, Cijaura, Kec. Buahbatu, Kota Bandung, Jawa Barat 40287",
      phone: "+6281323187789",
      hours: "Senin - Jumat: 08:00 - 17:00, Sabtu - Minggu: 08:00 - 16:00",
      tokopedia: "https://www.tokopedia.com/tokodus-buahbatu",
      shopee: "https://shopee.co.id/tokodusbuahbatu",
      lazada: "https://www.lazada.co.id/shop/tokodusbuahbatu",
      whatsapp: "https://wa.me/6281323187789",
      mapEmbed:
        "//www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.4634734701035!2d107.6396499761799!3d-6.9545272680895645!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e9c31c4bebc5%3A0x54a6e360467a916f!2sTokodus%20Buahbatu!5e0!3m2!1sid!2sid!4v1730085683776!5m2!1sid!2sid",
    },
  };

  // ==============================
  // == Dropdown Interactions ====
  // ==============================

  dropdownBtn.addEventListener("mouseenter", () => {
    dropdownContent.style.display = "block";
  });

  dropdownBtn.addEventListener("mouseleave", () => {
    setTimeout(() => {
      if (!dropdownContent.matches(":hover")) {
        dropdownContent.style.display = "none";
      }
    }, 200);
  });

  dropdownContent.addEventListener("mouseenter", () => {
    dropdownContent.style.display = "block";
  });

  dropdownContent.addEventListener("mouseleave", () => {
    dropdownContent.style.display = "none";
  });

  // ==============================
  // == Store Selection Handling ==
  // ==============================

  const storeItems = dropdownContent.querySelectorAll("li");
  let storeSelected = false;

  storeItems.forEach((item) => {
    item.addEventListener("click", () => {
      const storeKey = item.getAttribute("data-store");
      const storeData = stores[storeKey];

      storeInfo.innerHTML = `
        <h3>${storeData.name}</h3>
        <p><strong>Alamat:</strong> ${storeData.address}</p>
        <p><strong>No. Telp:</strong> <a href="${storeData.whatsapp}" target="_blank">${storeData.phone}</a></p>
        <p><strong>Jam Operasional:</strong> ${storeData.hours}</p>
      `;

      storeMap.src = storeData.mapEmbed;

      tokopediaLink.href = storeData.tokopedia;
      shopeeLink.href = storeData.shopee;
      lazadaLink.href = storeData.lazada;
      whatsappLink.href = storeData.whatsapp;

      dropdownContent.style.display = "none";
      storeSelected = true;
    });
  });

  // ==============================
  // == Outside Click Resets =====
  // ==============================

  document.addEventListener("click", (event) => {
    const isDropdownClick =
      dropdownContent.contains(event.target) ||
      dropdownBtn.contains(event.target);

    if (!isDropdownClick && !storeSelected) {
      storeMap.src = defaultMapEmbed;
      storeInfo.innerHTML = `
        <h3>Bandung, Jawa Barat</h3>
        <p><strong>Silakan pilih cabang toko untuk melihat informasi lebih lanjut.</strong></p>
      `;
    }

    if (
      !dropdownContent.contains(event.target) &&
      !dropdownBtn.contains(event.target)
    ) {
      dropdownContent.style.display = "none";
    }
  });

  // ==============================
  // == Page Load Initialization ==
  // ==============================

  document.addEventListener("DOMContentLoaded", () => {
    storeMap.src = defaultMapEmbed;
    storeInfo.innerHTML = `
      <h3>Bandung, Jawa Barat</h3>
      <p><strong>Silakan pilih cabang toko untuk melihat informasi lebih lanjut.</strong></p>
    `;

    tokopediaLink.href = "#";
    shopeeLink.href = "#";
    lazadaLink.href = "#";
    whatsappLink.href = "#";
    storeSelected = false;
  });

  // ==============================
  // == Store Section End ========
  // ==============================
