</div>
    </main>
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section about">
                    <h2>About Us</h2>
                    <p>ShoeStore offers the latest and greatest footwear for all occasions. From casual to formal, we've got you covered.</p>
                    <div class="contact">
                        <span><i class="fas fa-phone"></i> &nbsp; 123-456-789</span>
                        <span><i class="fas fa-envelope"></i> &nbsp; info@shoestore.com</span>
                    </div>
                    <div class="socials">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="footer-section links">
                    <h2>Quick Links</h2>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php?page=products">Products</a></li>
                        <li><a href="index.php?controller=about&action=index">About Us</a></li>
                        <li><a href="index.php?controller=qna&action=index">Q&A</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="footer-section newsletter">
                    <h2>Newsletter</h2>
                    <p>Subscribe to our newsletter for updates on new arrivals and special offers.</p>
                    <form action="index.php" method="post">
                        <input type="email" name="email" placeholder="Enter your email">
                        <button type="submit" class="btn-subscribe">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; <?php echo date('Y'); ?> ShoeStore | All rights reserved
            </div>
        </div>
    </footer>
    <?php require_once __DIR__ . '/chat-widget.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>

