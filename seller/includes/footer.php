        </main>
        
        <!-- Dashboard Footer -->
        <footer class="py-3 px-4 bg-white border-top text-center text-md-start d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small text-muted">
            <div>
                &copy; <?php echo date('Y'); ?> <strong>Udyojika Maker Portal</strong>. Made with love for Indian Home Entrepreneurs.
            </div>
            <div>
                <span class="badge bg-warning text-dark"><i class="fa-solid fa-heart text-danger me-1"></i> 100% Homemade Verified</span>
            </div>
        </footer>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const button = document.getElementById("notificationButton");
    const menu = document.getElementById("notificationMenu");

    if (!button || !menu) return;

    button.addEventListener("click", function (e) {

        e.preventDefault();
        e.stopPropagation();

        menu.classList.toggle("show");

    });

    document.addEventListener("click", function (e) {

        if (!button.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove("show");
        }

    });

});
</script>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Dashboard Script -->
<script src="../js/dashboard.js"></script>

</body>
</html>
