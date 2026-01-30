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
  

  /* ==========================================================================
    SECTION: SLIDER HANDLER
    ========================================================================== */
  

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
