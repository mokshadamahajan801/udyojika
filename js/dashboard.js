/**
 * UDYOJIKA DASHBOARDS - Vanilla JavaScript Logic
 * Handles mobile sidebar, confirmations, quick filters, tabs, chart interactivity
 */

document.addEventListener('DOMContentLoaded', () => {
  // Mobile Sidebar Toggle
  const toggleBtn = document.getElementById('sidebarToggleBtn');
  const sidebar = document.querySelector('.dashboard-sidebar');
  const backdrop = document.getElementById('sidebarBackdrop');

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('show');
      if (backdrop) backdrop.classList.toggle('show');
    });
  }

  if (backdrop && sidebar) {
    backdrop.addEventListener('click', () => {
      sidebar.classList.remove('show');
      backdrop.classList.remove('show');
    });
  }

  // Image Upload Previewer helper
  const imgInput = document.getElementById('imageUploadInput');
  const imgPreview = document.getElementById('imagePreviewContainer');
  if (imgInput && imgPreview) {
    imgInput.addEventListener('change', function () {
      imgPreview.innerHTML = '';
      if (this.files && this.files.length > 0) {
        Array.from(this.files).forEach(file => {
          if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
              const img = document.createElement('img');
              img.src = e.target.result;
              img.className = 'rounded-3 border shadow-sm me-2 mb-2';
              img.style.width = '80px';
              img.style.height = '80px';
              img.style.objectFit = 'cover';
              imgPreview.appendChild(img);
            };
            reader.readAsDataURL(file);
          }
        });
      }
    });
  }

  // Quick Table Search / Filter
  const tableSearchInput = document.getElementById('dashboardTableSearch');
  if (tableSearchInput) {
    tableSearchInput.addEventListener('keyup', function () {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('.dashboard-table tbody tr');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  }
});

// Toast notification trigger
function showDashboardToast(message, type = 'success', title = 'Notification') {
  if (window.udyojika && window.udyojika.showToast) {
    window.udyojika.showToast(message, type, title);
  } else {
    alert(title + ': ' + message);
  }
}
