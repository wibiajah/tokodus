// ========================================
// SECTION 8: STORE SECTION JAVASCRIPT
// ========================================

document.addEventListener('DOMContentLoaded', function() {
  
  // Store data dengan informasi lengkap
  const storeData = {
    tki: {
      name: "Tokodus Taman Kopo Indah",
      address: "Jl. Taman Kopo Indah Blok D No. 123, Bandung",
      phone: "022-1234567",
      whatsapp: "6281120061333",
      hours: "Senin - Sabtu: 08.00 - 17.00 WIB",
      mapUrl: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.5!2d107.5750!3d-6.9500!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwNTcnMDAuMCJTIDEwN8KwMzQnMzAuMCJF!5e0!3m2!1sen!2sid!4v1234567890",
      tokopedia: "https://tokopedia.com/tokodus-tki",
      shopee: "https://shopee.co.id/tokodus.tki",
      lazada: "https://lazada.co.id/tokodus-tki"
    },
    cimahi: {
      name: "Tokodus Cimahi",
      address: "Jl. Raya Cimahi No. 456, Cimahi",
      phone: "022-7654321",
      whatsapp: "6281120061334",
      hours: "Senin - Sabtu: 08.00 - 17.00 WIB",
      mapUrl: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.5!2d107.5400!3d-6.8700!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwNTInMTIuMCJTIDEwN8KwMzInMjQuMCJF!5e0!3m2!1sen!2sid!4v1234567891",
      tokopedia: "https://tokopedia.com/tokodus-cimahi",
      shopee: "https://shopee.co.id/tokodus.cimahi",
      lazada: "https://lazada.co.id/tokodus-cimahi"
    },
    cibaduyut: {
      name: "Tokodus Cibaduyut",
      address: "Jl. Cibaduyut Raya No. 789, Bandung",
      phone: "022-9876543",
      whatsapp: "6281120061335",
      hours: "Senin - Sabtu: 08.00 - 17.00 WIB",
      mapUrl: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.5!2d107.6300!3d-6.9700!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwNTgnMTIuMCJTIDEwN8KwMzcnNDguMCJF!5e0!3m2!1sen!2sid!4v1234567892",
      tokopedia: "https://tokopedia.com/tokodus-cibaduyut",
      shopee: "https://shopee.co.id/tokodus.cibaduyut",
      lazada: "https://lazada.co.id/tokodus-cibaduyut"
    },
    pagarsih: {
      name: "Tokodus Pagarsih",
      address: "Jl. Pagarsih No. 321, Bandung",
      phone: "022-5432109",
      whatsapp: "6281120061336",
      hours: "Senin - Sabtu: 08.00 - 17.00 WIB",
      mapUrl: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.5!2d107.6100!3d-6.9300!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwNTUnNDguMCJTIDEwN8KwMzYnMzYuMCJF!5e0!3m2!1sen!2sid!4v1234567893",
      tokopedia: "https://tokopedia.com/tokodus-pagarsih",
      shopee: "https://shopee.co.id/tokodus.pagarsih",
      lazada: "https://lazada.co.id/tokodus-pagarsih"
    },
    buahbatu: {
      name: "Tokodus Buahbatu",
      address: "Jl. Buahbatu No. 654, Bandung",
      phone: "022-1112233",
      whatsapp: "6281120061337",
      hours: "Senin - Sabat: 08.00 - 17.00 WIB",
      mapUrl: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.5!2d107.6350!3d-6.9450!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwNTYnNDIuMCJTIDEwN8KwMzgnMDYuMCJF!5e0!3m2!1sen!2sid!4v1234567894",
      tokopedia: "https://tokopedia.com/tokodus-buahbatu",
      shopee: "https://shopee.co.id/tokodus.buahbatu",
      lazada: "https://lazada.co.id/tokodus-buahbatu"
    }
  };

  // Elements
  const dropdownBtn = document.querySelector('.dropdown-btn');
  const dropdownContent = document.querySelector('.dropdown-content');
  const storeInfoDiv = document.querySelector('.store-info');
  const storeMap = document.getElementById('storeMap');
  const dropdownItems = document.querySelectorAll('.dropdown-content li');
  
  // Marketplace links
  const tokopediaLink = document.getElementById('tokopediaLink');
  const shopeeLink = document.getElementById('shopeeLink');
  const lazadaLink = document.getElementById('lazadaLink');
  const whatsappLink = document.getElementById('whatsappLink');

  // Toggle dropdown
  if (dropdownBtn && dropdownContent) {
    dropdownBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      dropdownContent.classList.toggle('show');
    });
  }

  // Close dropdown when clicking outside
  document.addEventListener('click', function(e) {
    if (dropdownContent && !dropdownContent.contains(e.target) && e.target !== dropdownBtn) {
      dropdownContent.classList.remove('show');
    }
  });

  // Function to update store info
  function updateStoreInfo(storeKey) {
    const store = storeData[storeKey];
    
    if (!store) return;

    // Update store info display
    if (storeInfoDiv) {
      storeInfoDiv.innerHTML = `
        <h3>${store.name}</h3>
        <p><strong>Alamat:</strong> ${store.address}</p>
        <p><strong>Telepon:</strong> ${store.phone}</p>
        <p><strong>Jam Buka:</strong> ${store.hours}</p>
      `;
    }

    // Update map
    if (storeMap) {
      storeMap.src = store.mapUrl;
    }

    // Update marketplace links
    if (tokopediaLink) tokopediaLink.href = store.tokopedia;
    if (shopeeLink) shopeeLink.href = store.shopee;
    if (lazadaLink) lazadaLink.href = store.lazada;
    if (whatsappLink) {
      whatsappLink.href = `https://wa.me/${store.whatsapp}`;
    }

    // Close dropdown
    if (dropdownContent) {
      dropdownContent.classList.remove('show');
    }
  }

  // Add click event to dropdown items
  dropdownItems.forEach(item => {
    item.addEventListener('click', function() {
      const storeKey = this.getAttribute('data-store');
      updateStoreInfo(storeKey);
    });
  });

  // Load default store (first store)
  if (dropdownItems.length > 0) {
    const firstStoreKey = dropdownItems[0].getAttribute('data-store');
    updateStoreInfo(firstStoreKey);
  }

  // WhatsApp float button functionality
  const whatsappButton = document.getElementById('whatsappButton');
  if (whatsappButton) {
    // Scroll behavior untuk show/hide button
    let lastScroll = 0;
    window.addEventListener('scroll', function() {
      const currentScroll = window.pageYOffset;
      
      if (currentScroll <= 0) {
        whatsappButton.style.display = 'flex';
        return;
      }
      
      if (currentScroll > lastScroll && currentScroll > 100) {
        // Scrolling down
        whatsappButton.style.opacity = '0.7';
      } else {
        // Scrolling up
        whatsappButton.style.opacity = '1';
      }
      
      lastScroll = currentScroll;
    });

    // Hover effect
    whatsappButton.addEventListener('mouseenter', function() {
      this.style.opacity = '1';
    });
  }

  // Smooth scroll untuk semua anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      
      // Skip jika href hanya "#"
      if (href === '#') {
        e.preventDefault();
        return;
      }
      
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });

});