<footer class="app-footer" id="appFooter">
    <div class="footer-container">
        <!-- Footer Main Content -->
        <div class="footer-main">
            <!-- Logo & Company Info -->
            <div class="footer-brand">
                <div class="footer-logo">
                    <img src="{{ asset('images/pelni_logo.png') }}" alt="PELNI" class="footer-logo-img">
                </div>
                <div class="footer-company-info">
                    <h3 class="footer-company-name">PT PELNI (Persero)</h3>
                    <p class="footer-address">
                        Jl. Gajah Mada No. 14, Jakarta Pusat, 10130 DKI Jakarta, Indonesia
                    </p>
                    <div class="footer-contact">
                        <div class="contact-item">
                            <i class="bi bi-telephone-fill"></i>
                            <span>+62 21 633 1112</span>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-envelope-fill"></i>
                            <span>info@pelni.co.id</span>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-globe"></i>
                            <span>www.pelni.co.id</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Links -->
            <div class="footer-links">
                <div class="footer-column">
                    <h4 class="footer-title">Sistem Sertijab</h4>
                    <ul class="footer-menu">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('abk.index') }}">Kelola ABK</a></li>
                        <li><a href="{{ route('monitoring.index') }}">Monitoring</a></li>
                        <li><a href="{{ route('arsip.index') }}">Arsip Sertijab</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Bantuan</h4>
                    <ul class="footer-menu">
                        <li><a href="#">Panduan Penggunaan</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Hubungi Support</a></li>
                        <li><a href="#">Tutorial Video</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Pengaturan</h4>
                    <ul class="footer-menu">
                        <li><a href="{{ route('profile.index') }}">Profil Admin</a></li>
                        <li><a href="{{ route('settings.index') }}">Pengaturan Sistem</a></li>
                        <li><a href="#">Backup Data</a></li>
                        <li><a href="#">Log Aktivitas</a></li>
                    </ul>
                </div>
            </div>

            <!-- Social Media & App Downloads -->
            <div class="footer-social">
                <h4 class="footer-title">Ikuti PELNI</h4>
                <div class="social-links">
                    <a href="https://facebook.com/pelni.official" target="_blank" class="social-link facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://twitter.com/pelni_official" target="_blank" class="social-link twitter">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="https://instagram.com/pelni_official" target="_blank" class="social-link instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://tiktok.com/@pelni_official" target="_blank" class="social-link tiktok">
                        <i class="bi bi-tiktok"></i>
                    </a>
                    <a href="https://youtube.com/pelniofficial" target="_blank" class="social-link youtube">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>

                <!-- App Download Section -->
                <div class="app-downloads">
                    <p class="download-text">Unduh Aplikasi Resmi Pelni</p>
                    <div class="download-buttons">
                        <a href="#" class="download-btn">
                            <img src="{{ asset('images/app-store-badge.png') }}" alt="Download on App Store">
                        </a>
                        <a href="#" class="download-btn">
                            <img src="{{ asset('images/google-play-badge.png') }}" alt="Get it on Google Play">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-divider"></div>
            <div class="footer-bottom-content">
                <div class="copyright">
                    <p>&copy; {{ date('Y') }} PT Pelayaran Nasional Indonesia (PELNI). All rights reserved.</p>
                    <p class="system-info">Sistem Sertijab PELNI v1.0 - Powered by Laravel</p>
                </div>
                <div class="footer-bottom-links">
                    <a href="#">Kebijakan Privasi</a>
                    <span class="separator">|</span>
                    <a href="#">Syarat & Ketentuan</a>
                    <span class="separator">|</span>
                    <a href="#">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer Variables */
:root {
    --footer-bg: #1e293b;
    --footer-bg-light: #334155;
    --footer-text: #e2e8f0;
    --footer-text-muted: #94a3b8;
    --footer-border: #475569;
    --primary-blue: #2A3F8E;
    --secondary-blue: #3b82f6;
    --light-blue: #8CB4F5;
    --footer-padding: 48px 24px 24px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Footer Container */
.app-footer {
    background: linear-gradient(135deg, var(--footer-bg) 0%, #0f172a 100%);
    color: var(--footer-text);
    margin-left: var(--sidebar-width, 280px);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.app-footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-blue) 0%, var(--secondary-blue) 50%, var(--light-blue) 100%);
}

.app-footer.sidebar-collapsed {
    margin-left: var(--sidebar-width-collapsed, 70px);
}

.footer-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: var(--footer-padding);
}

/* Footer Main Content */
.footer-main {
    display: grid;
    grid-template-columns: 2fr 2fr 1fr;
    gap: 48px;
    margin-bottom: 32px;
}

/* Footer Brand */
.footer-brand {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.footer-logo-img {
    height: 60px;
    width: auto;
    object-fit: contain;
    filter: brightness(1.1);
}

.footer-company-name {
    font-family: 'Poppins', sans-serif;
    font-size: 24px;
    font-weight: 700;
    color: var(--light-blue);
    margin-bottom: 8px;
    line-height: 1.2;
}

.footer-address {
    font-size: 14px;
    color: var(--footer-text-muted);
    line-height: 1.6;
    margin-bottom: 16px;
}

.footer-contact {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    color: var(--footer-text);
}

.contact-item i {
    width: 16px;
    color: var(--light-blue);
    font-size: 14px;
}

/* Footer Links */
.footer-links {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
}

.footer-column {
    display: flex;
    flex-direction: column;
}

.footer-title {
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 600;
    color: var(--light-blue);
    margin-bottom: 16px;
    line-height: 1.2;
}

.footer-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-menu li {
    margin-bottom: 8px;
}

.footer-menu a {
    color: var(--footer-text-muted);
    text-decoration: none;
    font-size: 14px;
    transition: var(--transition);
    line-height: 1.5;
}

.footer-menu a:hover {
    color: var(--light-blue);
    transform: translateX(4px);
}

/* Footer Social */
.footer-social {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.social-links {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
}

.social-link {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: var(--transition);
    font-size: 18px;
    border: 2px solid transparent;
}

.social-link.facebook {
    background: #1877f2;
    color: white;
}

.social-link.twitter {
    background: #000000;
    color: white;
}

.social-link.instagram {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    color: white;
}

.social-link.tiktok {
    background: #000000;
    color: white;
}

.social-link.youtube {
    background: #ff0000;
    color: white;
}

.social-link:hover {
    transform: translateY(-2px);
    border-color: var(--light-blue);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

/* App Downloads */
.app-downloads {
    width: 100%;
}

.download-text {
    font-size: 14px;
    color: var(--footer-text-muted);
    margin-bottom: 12px;
    font-weight: 500;
}

.download-buttons {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.download-btn {
    display: block;
    transition: var(--transition);
}

.download-btn img {
    height: 40px;
    width: auto;
    border-radius: 8px;
    transition: var(--transition);
}

.download-btn:hover img {
    transform: scale(1.05);
    filter: brightness(1.1);
}

/* Footer Bottom */
.footer-bottom {
    margin-top: 32px;
}

.footer-divider {
    height: 1px;
    background: var(--footer-border);
    margin-bottom: 24px;
}

.footer-bottom-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.copyright {
    flex: 1;
}

.copyright p {
    margin: 0;
    font-size: 13px;
    color: var(--footer-text-muted);
    line-height: 1.4;
}

.system-info {
    font-size: 11px !important;
    color: var(--footer-text-muted);
    opacity: 0.8;
}

.footer-bottom-links {
    display: flex;
    align-items: center;
    gap: 12px;
}

.footer-bottom-links a {
    color: var(--footer-text-muted);
    text-decoration: none;
    font-size: 13px;
    transition: var(--transition);
}

.footer-bottom-links a:hover {
    color: var(--light-blue);
}

.separator {
    color: var(--footer-text-muted);
    font-size: 12px;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .footer-main {
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }
    
    .footer-social {
        grid-column: 1 / -1;
        align-items: center;
        text-align: center;
    }
    
    .footer-links {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .app-footer {
        margin-left: 0;
    }
    
    .footer-container {
        padding: 32px 16px 16px;
    }
    
    .footer-main {
        grid-template-columns: 1fr;
        gap: 24px;
        text-align: center;
    }
    
    .footer-links {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .footer-brand {
        align-items: center;
    }
    
    .footer-bottom-content {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .download-buttons {
        flex-direction: row;
        justify-content: center;
        gap: 12px;
    }
}

@media (max-width: 480px) {
    .footer-container {
        padding: 24px 12px 12px;
    }
    
    .social-links {
        justify-content: center;
    }
    
    .footer-company-name {
        font-size: 20px;
    }
    
    .download-buttons {
        flex-direction: column;
        align-items: center;
    }
}

/* Print Styles */
@media print {
    .app-footer {
        display: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const footer = document.getElementById('appFooter');
    const sidebar = document.getElementById('sidebar');
    
    // Adjust footer margin based on sidebar state
    function adjustFooter() {
        if (sidebar && sidebar.classList.contains('collapsed')) {
            footer.classList.add('sidebar-collapsed');
        } else {
            footer.classList.remove('sidebar-collapsed');
        }
    }
    
    // Listen for sidebar changes
    if (sidebar) {
        const observer = new MutationObserver(adjustFooter);
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
        adjustFooter(); // Initial check
    }
    
    // Handle window resize for mobile
    window.addEventListener('resize', function() {
        if (window.innerWidth <= 768) {
            footer.classList.remove('sidebar-collapsed');
        } else {
            adjustFooter();
        }
    });
    
    // Smooth scroll for footer links
    const footerLinks = footer.querySelectorAll('a[href^="#"]');
    footerLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add loading state for external links
    const externalLinks = footer.querySelectorAll('a[href^="http"]');
    externalLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Add loading state if needed
            console.log('Opening external link:', this.href);
        });
    });
});
</script>