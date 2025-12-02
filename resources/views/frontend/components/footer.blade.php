<footer class="footer">
    <div class="footer-container">
        <!-- CTA Section -->
        <div class="footer-content">
            <p>Sedang Butuh Packaging seperti apa?</p>
            <a href="/contact">Konsultasikan Sekarang</a>
        </div>

        <!-- Links -->
        <div class="footer-links">
            <div class="footer-group">
                <div class="footer-column">
                    <h4>Navigasi</h4>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#catalog">Catalog</a></li>
                        <li><a href="#projects">Projects</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h4>Informasi</h4>
                    <ul>
                        <li><a href="#privacy">Privacy Policy</a></li>
                        <li><a href="#terms">Terms & Conditions</a></li>
                        <li><a href="mailto:marketing@tokodus.com">marketing@tokodus.com</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-group">
                <div class="footer-column">
                    <h4>Ikuti Kami</h4>
                    <ul>
                        <li><a href="https://instagram.com/tokodusbdg" target="_blank">Instagram</a></li>
                        <li><a href="https://facebook.com/tokodusbdg" target="_blank">Facebook</a></li>
                        <li><a href="https://tiktok.com/@tokdus" target="_blank">Tiktok</a></li>
                        <li><a href="https://youtube.com/@TokodusOfficial" target="_blank">Youtube</a></li>
                    </ul>
                </div>

                <div class="footer-column footer-signup">
                    <h4>Newsletter</h4>
                    <form class="footer-signup-form">
                        <input type="email" placeholder="Email Anda" required>
                        <button type="submit">Sign Up</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bottom -->
        <div class="footer-bottom">
            <p class="footer-copyright">
                Copyright © {{ date('Y') }} Tokodus. All Rights Reserved
            </p>
            <div class="footer-brand">
                <img src="{{ asset('logo.png') }}" alt="Logo">
                <span>Tokodus</span>
            </div>
        </div>
    </div>
</footer>