// ====================================
// UTILITY FUNCTIONS
// ====================================
const Utils = {
    $(selector, parent = document) {
        try {
            return parent.querySelector(selector);
        } catch (error) {
            console.error(`Error selecting ${selector}:`, error);
            return null;
        }
    },

    $$(selector, parent = document) {
        try {
            return parent.querySelectorAll(selector);
        } catch (error) {
            console.error(`Error selecting ${selector}:`, error);
            return [];
        }
    },

    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

// ====================================
// SLIDER BANNER
// ====================================
let currentSlide = 1;
let slideInterval;
let autoSlide = true;
let isTransitioning = false;

function initializeSlider() {
    const slidesBennerContainer = Utils.$('.benner-slides');
    const dots = Array.from(Utils.$$('.dots .dot'));
    const prevBtn = Utils.$('.prev');
    const nextBtn = Utils.$('.next');

    if (!slidesBennerContainer) {
        console.warn('Banner slider container not found');
        return;
    }

    if (dots.length === 0) {
        console.warn('Banner dots not found');
        return;
    }

    try {
        let bennerSlides = Array.from(slidesBennerContainer.children);
        let totalBennerSlides = bennerSlides.length;

        if (totalBennerSlides === 0) {
            console.warn('No banner slides found');
            return;
        }

        const firstClone = bennerSlides[0].cloneNode(true);
        const lastClone = bennerSlides[totalBennerSlides - 1].cloneNode(true);
        slidesBennerContainer.appendChild(firstClone);
        slidesBennerContainer.insertBefore(lastClone, bennerSlides[0]);

        let updatedSlides = Array.from(slidesBennerContainer.children);
        let totalSlides = updatedSlides.length;

        slidesBennerContainer.style.transform = 'translateX(-100%)';

        function updateDots() {
            let dotIndex = currentSlide - 1;
            if (currentSlide === 0) dotIndex = totalBennerSlides - 1;
            if (currentSlide === totalSlides - 1) dotIndex = 0;

            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === dotIndex);
            });
        }

        function goToSlide(index) {
            if (isTransitioning) return;
            isTransitioning = true;
            currentSlide = index;

            slidesBennerContainer.style.transition = 'transform 0.5s ease-in-out';
            slidesBennerContainer.style.transform = `translateX(-${currentSlide * 100}%)`;

            const handleTransitionEnd = () => {
                isTransitioning = false;

                if (currentSlide === totalSlides - 1) {
                    slidesBennerContainer.style.transition = 'none';
                    currentSlide = 1;
                    slidesBennerContainer.style.transform = 'translateX(-100%)';
                }

                if (currentSlide === 0) {
                    slidesBennerContainer.style.transition = 'none';
                    currentSlide = totalBennerSlides;
                    slidesBennerContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
                }

                updateDots();
                slidesBennerContainer.removeEventListener('transitionend', handleTransitionEnd);
            };

            slidesBennerContainer.addEventListener('transitionend', handleTransitionEnd);
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
            nextBtn.addEventListener('click', () => {
                stopAutoSlide();
                nextSlide();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                stopAutoSlide();
                prevSlide();
            });
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                stopAutoSlide();
                goToSlide(index + 1);
            });
        });

        document.addEventListener('click', (e) => {
            if (!e.target.classList.contains('prev') && 
                !e.target.classList.contains('next') && 
                !autoSlide) {
                startAutoSlide();
            }
        });

        startAutoSlide();
        updateDots();

    } catch (error) {
        console.error('Error initializing slider:', error);
    }
}

// ====================================
// CATEGORY SLIDER
// ====================================
function initCategorySlider() {
    const categorySliderContainer = document.querySelector('.category-slider-container');
    const categorySlider = document.querySelector('.category-slider');

    if (!categorySliderContainer || !categorySlider) {
        console.warn('Category slider elements not found');
        return;
    }

    try {
        const cards = Array.from(categorySlider.children);
        
        if (cards.length === 0) {
            console.log('No categories to display');
            return;
        }

        cards.forEach(card => {
            let clone = card.cloneNode(true);
            categorySlider.appendChild(clone);
        });

        let isDragging = false;
        let startX = 0;
        let currentTranslate = 0;
        let prevTranslate = 0;
        let autoScroll;
        let isAutoScrolling = true;
        const cardWidth = 230;

        const minTranslate = -cardWidth * cards.length;
        const maxTranslate = 0;

        function startAutoScroll() {
            if (!isAutoScrolling) return;
            stopAutoScroll();
            
            autoScroll = setInterval(() => {
                currentTranslate -= cardWidth;
                
                if (currentTranslate <= minTranslate) {
                    categorySlider.style.transition = 'none';
                    currentTranslate = 0;
                    categorySlider.style.transform = `translateX(0px)`;
                    
                    categorySlider.offsetHeight;
                    
                    setTimeout(() => {
                        categorySlider.style.transition = 'transform 0.5s ease-in-out';
                    }, 50);
                } else {
                    categorySlider.style.transition = 'transform 0.5s ease-in-out';
                    categorySlider.style.transform = `translateX(${currentTranslate}px)`;
                }
                
                prevTranslate = currentTranslate;
            }, 2500);
        }

        function stopAutoScroll() {
            if (autoScroll) {
                clearInterval(autoScroll);
            }
        }

        categorySliderContainer.addEventListener('mouseenter', () => {
            stopAutoScroll();
        });

        categorySliderContainer.addEventListener('mouseleave', () => {
            if (!isDragging) {
                isAutoScrolling = true;
                startAutoScroll();
            }
        });

        categorySliderContainer.addEventListener('mousedown', (e) => {
            isDragging = true;
            isAutoScrolling = false;
            startX = e.pageX;
            stopAutoScroll();
            categorySliderContainer.classList.add('grabbing');
            categorySlider.style.transition = 'none';
        });

        document.addEventListener('mouseup', () => {
            if (isDragging) {
                isDragging = false;
                categorySliderContainer.classList.remove('grabbing');
                snapToNearest();
            }
        });

        categorySliderContainer.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            
            const x = e.pageX;
            const walk = (x - startX);
            currentTranslate = prevTranslate + walk;
            
            currentTranslate = Math.max(minTranslate, Math.min(maxTranslate, currentTranslate));
            
            categorySlider.style.transform = `translateX(${currentTranslate}px)`;
        });

        function snapToNearest() {
            let snapPoint = Math.round(currentTranslate / cardWidth) * cardWidth;
            snapPoint = Math.max(minTranslate, Math.min(maxTranslate, snapPoint));
            
            categorySlider.style.transition = 'transform 0.3s ease-out';
            categorySlider.style.transform = `translateX(${snapPoint}px)`;
            currentTranslate = snapPoint;
            prevTranslate = snapPoint;

            setTimeout(() => {
                if (!isDragging) {
                    isAutoScrolling = true;
                    startAutoScroll();
                }
            }, 300);
        }

        startAutoScroll();

    } catch (error) {
        console.error('Error initializing category slider:', error);
    }
}

// ====================================
// PARTNER LOGO SCROLL
// ====================================
function initPartnerLogoScroll() {
    const logoSliderContainer = Utils.$('.logo-slider-container');
    const logoSlide = Utils.$('.logo-slide');

    if (!logoSliderContainer || !logoSlide) {
        console.warn('Partner logo elements not found');
        return;
    }

    try {
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

        logoSliderContainer.addEventListener('mouseenter', () => isPaused = true);
        logoSliderContainer.addEventListener('mouseleave', () => isPaused = false);

    } catch (error) {
        console.error('Error initializing partner logo scroll:', error);
    }
}

// ====================================
// STORE SECTION
// ====================================
function initStoreSection() {
    const dropdownBtn = Utils.$('.dropdown-btn');
    const dropdownContent = Utils.$('#dropdownContent');
    const storeInfo = Utils.$('#storeInfo');
    const storeMap = Utils.$('#storeMap');

    if (!dropdownBtn || !dropdownContent || !storeInfo || !storeMap) {
        console.warn('Store section elements not found');
        return;
    }

    try {
        const defaultMapEmbed = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63387.67678038912!2d107.5731161675113!3d-6.914864054387586!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6421e57f7a3%3A0x1f9c85fd11fc66b2!2sBandung%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1726500000000";

        const stores = {
            tki: {
                name: "Tokodus Taman Kopo Indah",
                address: "Jl. Taman Kopo Indah 3 Blok F30, Mekar Rahayu, Kec. Margaasih, Kabupaten Bandung, Jawa Barat",
                phone: "+6281317255959",
                hours: "Senin - Jumat: 08:00 - 17:00, Sabtu - Minggu: 08:00 - 16:00",
                whatsapp: "http://wa.me/6281317255959",
                mapEmbed: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.348766866562!2d107.55030747360861!3d-6.968118468225218!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68efafefc76823%3A0x9e7c5cfafb88025a!2sTokodus%20Bandung!5e0!3m2!1sen!2sid!4v1726413067498!5m2!1sen!2sid"
            },
            cimahi: {
                name: "Tokodus Cimahi",
                address: "Jl. Jend. H. Amir Machmud No.481, Karangmekar, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40523",
                phone: "+628112013738",
                hours: "Senin - Jumat: 08:00 - 17:00, Sabtu - Minggu: 08:00 - 16:00",
                whatsapp: "https://wa.me/628112013738",
                mapEmbed: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.1128934737585!2d107.54500487360697!3d-6.877075667287033!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e54221ce2c2d%3A0xf7e21e4afb4389c5!2sTokodus%20Cimahi!5e0!3m2!1sen!2sid!4v1726414230361!5m2!1sen!2sid"
            },
            cibaduyut: {
                name: "Tokodus Cibaduyut",
                address: "Jl. Terusan Cibaduyut No.78, Cangkuang Kulon, Kec. Dayeuhkolot, Kabupaten Bandung, Jawa Barat 40239",
                phone: "+6281290778668",
                hours: "Senin - Jumat: 08:00 - 17:00, Sabtu - Minggu: 08:00 - 16:00",
                whatsapp: "https://wa.me/6281290778668",
                mapEmbed: "https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15841.4512792536!2d107.591762!3d-6.9664548!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e949836027e1%3A0xc85f2813c7f31e63!2sTokodus%20Cibaduyut!5e0!3m2!1sid!2sid!4v1726450983495!5m2!1sid!2sid"
            },
            pagarsih: {
                name: "Tokodus Pagarsih",
                address: "Jl. Pagarsih No.166a, Babakan Tarogong, Kec. Bojongloa Kaler, Kota Bandung, Jawa Barat 40231",
                phone: "+6282130138789",
                hours: "Senin - Jumat: 08:00 - 17:00, Sabtu - Minggu: 08:00 - 16:00",
                whatsapp: "https://wa.me/6282130138789",
                mapEmbed: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.718491350924!2d107.58839082499657!3d-6.924215943075507!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e700255a16b9%3A0x712448cb7e00f584!2sTokodus%20Pagarsih!5e0!3m2!1sid!2sid!4v1726451633860!5m2!1sid!2sid"
            },
            buahbatu: {
                name: "Tokodus Buahbatu",
                address: "Jl. Margacinta No.44A, Cijaura, Kec. Buahbatu, Kota Bandung, Jawa Barat 40287",
                phone: "+6281323187789",
                hours: "Senin - Jumat: 08:00 - 17:00, Sabtu - Minggu: 08:00 - 16:00",
                whatsapp: "https://wa.me/6281323187789",
                mapEmbed: "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.4634734701035!2d107.6396499761799!3d-6.9545272680895645!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e9c31c4bebc5%3A0x54a6e360467a916f!2sTokodus%20Buahbatu!5e0!3m2!1sid!2sid!4v1730085683776!5m2!1sid!2sid"
            }
        };

        const esc = Utils.escapeHtml;

        dropdownBtn.addEventListener('mouseenter', () => {
            dropdownContent.style.display = 'block';
        });

        dropdownBtn.addEventListener('mouseleave', () => {
            setTimeout(() => {
                if (!dropdownContent.matches(':hover')) {
                    dropdownContent.style.display = 'none';
                }
            }, 200);
        });

        dropdownContent.addEventListener('mouseenter', () => {
            dropdownContent.style.display = 'block';
        });

        dropdownContent.addEventListener('mouseleave', () => {
            dropdownContent.style.display = 'none';
        });

        const storeItems = dropdownContent.querySelectorAll('li');
        let storeSelected = false;

        storeItems.forEach(item => {
            item.addEventListener('click', () => {
                const storeKey = item.getAttribute('data-store');
                const storeData = stores[storeKey];

                if (!storeData) {
                    console.error('Store data not found for:', storeKey);
                    return;
                }

                storeInfo.innerHTML = `
                    <h3>${esc(storeData.name)}</h3>
                    <p><strong>Alamat:</strong> ${esc(storeData.address)}</p>
                    <p><strong>No. Telp:</strong> 
                        <a href="${esc(storeData.whatsapp)}" target="_blank" rel="noopener noreferrer">
                            ${esc(storeData.phone)}
                        </a>
                    </p>
                    <p><strong>Jam Operasional:</strong> ${esc(storeData.hours)}</p>
                `;

                storeMap.src = esc(storeData.mapEmbed);

                dropdownContent.style.display = 'none';
                storeSelected = true;
            });
        });

        document.addEventListener('click', (event) => {
            const isDropdownClick = dropdownContent.contains(event.target) || dropdownBtn.contains(event.target);

            if (!isDropdownClick && !storeSelected) {
                storeMap.src = defaultMapEmbed;
                storeInfo.innerHTML = `
                    <h3>Bandung, Jawa Barat</h3>
                    <p><strong>Silakan pilih cabang toko untuk melihat informasi lebih lanjut.</strong></p>
                `;
            }

            if (!dropdownContent.contains(event.target) && !dropdownBtn.contains(event.target)) {
                dropdownContent.style.display = 'none';
            }
        });

        storeMap.src = defaultMapEmbed;
        storeSelected = false;

    } catch (error) {
        console.error('Error initializing store section:', error);
    }
}

// ====================================
// MAIN INITIALIZATION
// ====================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initializing Tokodus website...');
    
    const initFunctions = [
        { name: 'Banner Slider', fn: initializeSlider },
        { name: 'Category Slider', fn: initCategorySlider },
        { name: 'Partner Logo Scroll', fn: initPartnerLogoScroll },
        { name: 'Store Section', fn: initStoreSection }
    ];

    let successCount = 0;
    let failCount = 0;

    initFunctions.forEach(({ name, fn }) => {
        try {
            fn();
            console.log(`✅ ${name} initialized`);
            successCount++;
        } catch (error) {
            console.error(`❌ ${name} failed:`, error);
            failCount++;
        }
    });

    console.log(`\n📊 Initialization Summary:`);
    console.log(`   Success: ${successCount}/${initFunctions.length}`);
    console.log(`   Failed: ${failCount}/${initFunctions.length}`);
    
    if (failCount === 0) {
        console.log('✨ All components initialized successfully!');
    } else {
        console.warn('⚠️ Some components failed to initialize. Check errors above.');
    }
});