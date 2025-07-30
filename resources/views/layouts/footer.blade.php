<footer class="app-footer" id="appFooter">
    <div class="footer-container">
        <!-- Footer Main Content -->
        <div class="footer-main">
            <!-- Logo & Company Info - UPDATED -->
            <div class="footer-brand">
                <div class="footer-logo">
                    <!-- Logo PELNI yang ditambahkan -->
                    <div class="pelni-logo">
                        <img src="{{ asset('images/pelni_logo.png') }}" 
                             alt="PELNI Logo" 
                             style="height: 40px; width: auto;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    </div>
                    
                    <!-- Ship Icon sebagai fallback -->
                    <div class="ship-icon">
                        <i class="bi bi-ship" style="font-size: 40px; color: #2A3F8E;"></i>
                    </div>
                </div>
                <div class="footer-company-info">
                    <h3 class="footer-company-name">PT PELNI (Persero)</h3>
                    <p class="footer-address">
                        Sistem Manajemen Serah Terima Jabatan
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
                        <li><a href="#">Kelola ABK</a></li>
                        <li><a href="#">Monitoring</a></li>
                        <li><a href="#">Arsip Sertijab</a></li>
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
                        <li><a href="#">Profil Admin</a></li>
                        <li><a href="#">Pengaturan Sistem</a></li>
                        <li><a href="#">Backup Data</a></li>
                        <li><a href="#">Log Aktivitas</a></li>
                    </ul>
                </div>
            </div>

            <!-- Social Media -->
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
                    <a href="https://youtube.com/pelniofficial" target="_blank" class="social-link youtube">
                        <i class="bi bi-youtube"></i>
                    </a>
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
/* Footer dengan Warna E3EEFF - UPDATED dengan Logo PELNI */
:root {
    --footer-bg: #E3EEFF;
    --footer-bg-light: #D1DEFD;
    --footer-text: #1e293b;
    --footer-text-muted: #64748b;
    --footer-border: #cbd5e1;
    --primary-blue: #2A3F8E;
    --secondary-blue: #3b82f6;
    --light-blue: #8CB4F5;
    --footer-padding: 40px 24px 24px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Footer Container - DIPERBAIKI: FULL WIDTH */
.app-footer {
    background: var(--footer-bg);
    color: var(--footer-text);
    margin-left: 0 !important;
    width: 100% !important;
    position: relative;
    overflow: hidden;
    border-top: 3px solid var(--primary-blue);
    box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
}

.app-footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--primary-blue) 0%, var(--secondary-blue) 50%, var(--light-blue) 100%);
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: var(--footer-padding);
    width: 100%;
}

/* Footer Main Content */
.footer-main {
    display: grid;
    grid-template-columns: 1.5fr 2fr 1fr;
    gap: 40px;
    margin-bottom: 32px;
}

/* Footer Brand - UPDATED dengan Logo */
.footer-brand {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.footer-logo {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    position: relative;
}

/* Logo PELNI Styles - BARU */
.pelni-logo {
    transition: var(--transition);
    opacity: 1;
}

.pelni-logo:hover {
    transform: scale(1.05);
    filter: drop-shadow(0 4px 8px rgba(42, 63, 142, 0.3));
}

.pelni-logo svg {
    width: 120px;
    height: 40px;
    max-width: 100%;
    height: auto;
}

/* Ship Icon sebagai fallback */
.ship-icon {
    display: none; /* Hidden by default, shown if SVG fails */
    transition: var(--transition);
}

.ship-icon:hover {
    transform: scale(1.1);
    filter: drop-shadow(0 4px 8px rgba(42, 63, 142, 0.3));
}

/* Show ship icon if SVG fails to load */
@supports not (display: flex) {
    .pelni-logo {
        display: none;
    }
    .ship-icon {
        display: block;
    }
}

.footer-company-name {
    font-family: 'Poppins', sans-serif;
    font-size: 20px;
    font-weight: 700;
    color: var(--primary-blue);
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
    font-size: 13px;
    color: var(--footer-text);
}

.contact-item i {
    width: 16px;
    color: var(--primary-blue);
    font-size: 13px;
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
    color: var(--primary-blue);
    margin-bottom: 14px;
    line-height: 1.2;
}

.footer-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-menu li {
    margin-bottom: 6px;
}

.footer-menu a {
    color: var(--footer-text-muted);
    text-decoration: none;
    font-size: 14px;
    transition: var(--transition);
    line-height: 1.5;
}

.footer-menu a:hover {
    color: var(--primary-blue);
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
    gap: 10px;
    flex-wrap: wrap;
}

.social-link {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: var(--transition);
    font-size: 16px;
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

.social-link.youtube {
    background: #ff0000;
    color: white;
}

.social-link:hover {
    transform: translateY(-2px) scale(1.05);
    border-color: var(--primary-blue);
    box-shadow: 0 6px 16px rgba(42, 63, 142, 0.3);
}

/* Footer Bottom */
.footer-bottom {
    margin-top: 24px;
}

.footer-divider {
    height: 1px;
    background: var(--footer-border);
    margin-bottom: 20px;
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
    margin-top: 4px !important;
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
    color: var(--primary-blue);
}

.separator {
    color: var(--footer-text-muted);
    font-size: 12px;
}

/* Responsive Design - UPDATED untuk Logo */
@media (max-width: 992px) {
    .footer-main {
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }
    
    .footer-social {
        grid-column: 1 / -1;
        align-items: center;
        text-align: center;
        margin-top: 16px;
    }
    
    .footer-links {
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
    
    .pelni-logo svg {
        width: 100px;
        height: 33px;
    }
}

@media (max-width: 768px) {
    .footer-container {
        padding: 32px 16px 20px;
    }
    
    .footer-main {
        grid-template-columns: 1fr;
        gap: 24px;
        text-align: center;
    }
    
    .footer-links {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .footer-brand {
        align-items: center;
    }
    
    .footer-social {
        align-items: center;
    }
    
    .social-links {
        justify-content: center;
    }
    
    .footer-bottom-content {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .footer-company-name {
        font-size: 18px;
    }
    
    .pelni-logo svg {
        width: 90px;
        height: 30px;
    }
}

@media (max-width: 480px) {
    .footer-container {
        padding: 24px 12px 16px;
    }
    
    .footer-main {
        gap: 20px;
    }
    
    .footer-links {
        gap: 16px;
    }
    
    .contact-item {
        font-size: 12px;
    }
    
    .footer-menu a {
        font-size: 13px;
    }
    
    .pelni-logo svg {
        width: 80px;
        height: 27px;
    }
}

/* Animation untuk load */
.app-footer {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Logo Animation */
.pelni-logo svg {
    animation: logoFadeIn 1s ease-out 0.3s both;
}

@keyframes logoFadeIn {
    from {
        opacity: 0;
        transform: translateY(10px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Print Styles */
@media print {
    .app-footer {
        break-inside: avoid;
        page-break-inside: avoid;
    }
    
    .pelni-logo svg {
        width: 80px;
        height: 27px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const footer = document.getElementById('appFooter');
    const pelniLogo = footer.querySelector('.pelni-logo');
    const shipIcon = footer.querySelector('.ship-icon');
    
    // Handle SVG fallback
    if (pelniLogo && shipIcon) {
        const svg = pelniLogo.querySelector('svg');
        if (svg) {
            svg.addEventListener('error', function() {
                pelniLogo.style.display = 'none';
                shipIcon.style.display = 'block';
            });
        }
    }
    
    // Add subtle animation on scroll
    function handleScroll() {
        const footerRect = footer.getBoundingClientRect();
        const isVisible = footerRect.top < window.innerHeight && footerRect.bottom > 0;
        
        if (isVisible && pelniLogo) {
            pelniLogo.style.animationPlayState = 'running';
        }
    }
    
    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Initial check
    
    // Smooth scroll untuk footer links
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
    
    // Add loading state untuk external links
    const externalLinks = footer.querySelectorAll('a[href^="http"]');
    externalLinks.forEach(link => {
        link.addEventListener('click', function() {
            this.style.opacity = '0.7';
            setTimeout(() => {
                this.style.opacity = '1';
            }, 200);
        });
    });
    
    // Handle responsive social links
    function adjustSocialLinks() {
        const socialLinks = footer.querySelector('.social-links');
        if (socialLinks && window.innerWidth <= 480) {
            socialLinks.style.maxWidth = '250px';
        } else if (socialLinks) {
            socialLinks.style.maxWidth = 'none';
        }
    }
    
    window.addEventListener('resize', adjustSocialLinks);
    adjustSocialLinks(); // Initial call
    
    console.log('Footer with PELNI logo loaded successfully');
});
</script>