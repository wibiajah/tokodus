document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("searchInput");
  const filterContainer = document.getElementById("filter-bar");
  const productContainer = document.getElementById("catalog-products");
  const paginationContainer = document.getElementById("pagination-container");

  let products = [];
  let currentFilter = "all";
  let currentPage = 1;
  const itemsPerPage = 8;

  fetch("/data/products.json")
    .then((res) => res.json())
    .then((data) => {
      products = data;
      renderCategoryFilters(data);
      renderProducts();
    });

  function renderCategoryFilters(data) {
    const kategoriSet = new Set(data.map((p) => p.kategori).filter(Boolean));
    const tipeSet = new Set(
      data.map((p) => p.tipe?.toLowerCase()).filter(Boolean)
    );

    filterContainer.innerHTML = `
      <button class="filter-btn active" data-filter="all">Semua</button>
      ${[...kategoriSet, ...tipeSet]
        .map(
          (kat) =>
            `<button class="filter-btn" data-filter="${kat}">${kat}</button>`
        )
        .join("")}
    `;

    filterContainer.addEventListener("click", (e) => {
      if (e.target.classList.contains("filter-btn")) {
        document
          .querySelectorAll(".filter-btn")
          .forEach((btn) => btn.classList.remove("active"));
        e.target.classList.add("active");
        currentFilter = e.target.getAttribute("data-filter");
        currentPage = 1;
        renderProducts();
      }
    });
  }

  if (searchInput) {
    searchInput.addEventListener("input", () => {
      currentPage = 1;
      renderProducts();
    });
  }

  function renderProducts() {
    const filtered = products.filter((p) => {
      const matchFilter =
        currentFilter === "all" ||
        p.kategori === currentFilter ||
        p.tipe?.toLowerCase() === currentFilter ||
        (Array.isArray(p.tag) && p.tag.includes(currentFilter));

      const matchSearch =
        !searchInput ||
        p.kode.toLowerCase().includes(searchInput.value.toLowerCase());

      return matchFilter && matchSearch;
    });

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageItems = filtered.slice(start, end);

    productContainer.innerHTML = pageItems.map(renderCard).join("");
    renderPagination(filtered.length);
    replaceFeatherIcons(); // ← Penggunaan fungsi utilitas
  }

  function renderPagination(totalItems) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    paginationContainer.innerHTML = "";

    if (totalPages <= 1) return;

    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement("button");
      btn.className = `page-btn${i === currentPage ? " active" : ""}`;
      btn.textContent = i;
      btn.addEventListener("click", () => {
        currentPage = i;
        renderProducts();
      });
      paginationContainer.appendChild(btn);
    }
  }

  function renderCard(product) {
    const ratingStars =
      "★".repeat(product.rating) + "☆".repeat(5 - product.rating);
    const categoryClass =
      product.kategori === "lebaran"
        ? "product-lebaran"
        : product.kategori === "christmas"
        ? "product-christmas"
        : product.kategori === "imlek"
        ? "product-imlek"
        : product.tipe?.toLowerCase() === "masterbox"
        ? "product-masterbox"
        : "product-innerbox";

    return `
      <div class="product-card">
        <div class="product-image">
          <img src="${product.image_default}" alt="${
      product.kode
    }" class="default-image" />
          <img src="${product.image_hover}" alt="${
      product.kode
    }" class="hover-image" />
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
  }

  // Fungsi utilitas untuk mengganti ikon Feather
  function replaceFeatherIcons() {
    if (typeof feather !== "undefined") {
      feather.replace();
    }
  }
});
