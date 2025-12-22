<!-- Footer -->
<footer id="contact">
    <div class="container">
        <div class="footer-content">
            <div class="footer-about">
                <h3 class="footer-logo">PUSJAR SKMP</h3>
                <p class="footer-text">Sistem Informasi Profesional yang berkomitmen untuk menyediakan layanan
                    informasi dan publikasi berkualitas tinggi dengan teknologi terkini.</p>
                <div class="social-icons">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="footer-links-container">
                <h4 class="footer-title">Tautan Cepat</h4>
                <ul class="footer-links">
                    <li><a href="#home"><i class="fas fa-chevron-right"></i> Beranda</a></li>
                    <li><a href="#profile"><i class="fas fa-chevron-right"></i> Profil</a></li>
                    <li><a href="#publication"><i class="fas fa-chevron-right"></i> Publikasi</a></li>
                    <li><a href="#contact"><i class="fas fa-chevron-right"></i> Kontak</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h4 class="footer-title">Kontak Kami</h4>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt contact-icon"></i>
                        <span>Jl. Raya Baruga, No 48 Antang, Kota Makassar</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone contact-icon"></i>
                        <span>(0411) 490101</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope contact-icon"></i>
                        <span>info@sipena.co.id</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="copyright">
            <p>&copy; {{ date('Y') }} SIPENA. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</footer>

<style>
    /* Footer */
    footer {
        background: var(--gradient-primary);
        color: white;
        padding: 70px 0 20px;
        position: relative;
        overflow: hidden;
    }

    .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        margin-bottom: 40px;
    }

    .footer-logo {
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 15px;
        color: white;
    }

    .footer-text {
        opacity: 0.9;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .social-icons {
        display: flex;
        gap: 12px;
    }

    .social-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        color: white;
        text-decoration: none;
        transition: var(--transition);
    }

    .social-icon:hover {
        background-color: white;
        color: var(--primary-color);
        transform: translateY(-3px);
    }

    .footer-title {
        font-size: 1.2rem;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .footer-links {
        list-style: none;
    }

    .footer-links li {
        margin-bottom: 10px;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-links a:hover {
        color: white;
        padding-left: 5px;
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .contact-icon {
        color: var(--gold-color);
        margin-top: 3px;
        flex-shrink: 0;
    }

    .copyright {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        opacity: 0.8;
        font-size: 0.9rem;
    }
</style>