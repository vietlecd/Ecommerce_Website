</div>
    </main>
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <section class="footer-panel footer-brand">
                    <p class="footer-eyebrow">V.AShoes atelier</p>
                    <h2>Crafted for motion, curated with heart.</h2>
                    <p>Limited footwear capsules, concierge care, and radical transparency for movers who treat every stride like a story.</p>
                    <ul class="footer-contact">
                        <li><i class="fas fa-phone"></i> +81 92 555 8080</li>
                        <li><i class="fas fa-envelope"></i> atelier@V.AShoes.jp</li>
                        <li><i class="fas fa-map-marker-alt"></i> 43-13 Shiohara, Minami-ku, Fukuoka</li>
                    </ul>
                    <div class="socials">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-behance"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </section>

                <section class="footer-panel footer-links">
                    <div>
                        <h3>Shop</h3>
                        <ul>
                            <li><a href="/index.php">New arrivals</a></li>
                            <li><a href="/index.php?controller=products&action=index">All footwear</a></li>
                            <li><a href="/index.php?controller=promotional-products&action=index">In promotion</a></li>
                            <li><a href="/index.php?controller=cart&action=index">Your cart</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3>Support</h3>
                        <ul>
                            <li><a href="/index.php?controller=qna&action=index">Q&A hub</a></li>
                            <li><a href="/index.php?controller=about&action=index#loopback">Loopback repairs</a></li>
                            <li><a href="#">Shipping & returns</a></li>
                            <li><a href="#">Size & fit guide</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3>Studio</h3>
                        <ul>
                            <li><a href="/index.php?controller=about&action=index">About V.AShoes</a></li>
                            <li><a href="/index.php?controller=news&action=index">Newsroom</a></li>
                            <li><a href="#">Wholesale</a></li>
                            <li><a href="#">Careers</a></li>
                        </ul>
                    </div>
                </section>

                <section class="footer-panel footer-service">
                    <h3>Concierge updates</h3>
                    <p>Request early access to atelier logs, research journals, and invite-only drops tailored to your city.</p>
                    <form action="/" method="post" class="footer-newsletter">
                        <input type="email" name="newsletter_email" placeholder="Email address">
                        <button type="submit">Join the list</button>
                    </form>
                    <div class="footer-avail">
                        <div>
                            <p class="label">Atelier hours</p>
                            <strong>Tue – Sun, 10:00 – 20:00 JST</strong>
                        </div>
                        <div>
                            <p class="label">Live chat</p>
                            <strong>24/7 concierge</strong>
                        </div>
                    </div>
                </section>
            </div>

            <div class="footer-meta">
                <div>
                    <p class="label">Loopback Program</p>
                    <strong>72% of returned materials stay in circulation.</strong>
                </div>
                <div>
                    <p class="label">Carbon disclosure</p>
                    <strong>Track every pair via NFC-enabled packaging.</strong>
                </div>
                <div>
                    <p class="label">© <?php echo date('Y'); ?> V.AShoes</p>
                    <strong>Designed in Fukuoka • Built for global movers.</strong>
                </div>
            </div>
        </div>
    </footer>
    <?php require_once __DIR__ . '/chat-widget.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>

