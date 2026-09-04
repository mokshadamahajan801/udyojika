/**
 * =======================================================
 * UDYOJIKA - Women Home Entrepreneurs Marketplace
 * Vanilla JavaScript Engine (Cart, Wishlist, UI Interactions)
 * =======================================================
 */

(function () {
  'use strict';

  // --- Cart State Management (localStorage) ---
  const CART_KEY = 'udyojika_cart';
  const WISHLIST_KEY = 'udyojika_wishlist';

  function getCart() {
    try {
      return JSON.parse(localStorage.getItem(CART_KEY)) || [];
    } catch (e) {
      return [];
    }
  }

  function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
    updateCartBadges();
  }

  function getWishlist() {
    try {
      return JSON.parse(localStorage.getItem(WISHLIST_KEY)) || [];
    } catch (e) {
      return [];
    }
  }

  function saveWishlist(wishlist) {
    localStorage.setItem(WISHLIST_KEY, JSON.stringify(wishlist));
    updateWishlistBadges();
  }

  // --- Toast Notification Helper ---
  function showToast(message, type = 'success', title = 'Udyojika Notice') {
    let container = document.getElementById('toast-notification-area');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-notification-area';
      container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
      container.style.zIndex = '3000';
      document.body.appendChild(container);
    }

    const toastId = 'toast_' + Date.now();
    const iconClass = type === 'success' ? 'fa-circle-check text-success' : 'fa-circle-info text-warning';
    
    const toastHtml = `
      <div id="${toastId}" class="toast align-items-center bg-white border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-cream-100 border-bottom border-light py-2">
          <i class="fa-solid ${iconClass} me-2 fs-5"></i>
          <strong class="me-auto text-maroon-800">${title}</strong>
          <small class="text-muted">Just now</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body py-3 text-dark fw-medium">
          ${message}
        </div>
      </div>
    `;

    container.insertAdjacentHTML('beforeend', toastHtml);
    const toastEl = document.getElementById(toastId);
    if (window.bootstrap && window.bootstrap.Toast) {
      const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
      toast.show();
      toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    } else {
      setTimeout(() => toastEl.remove(), 4000);
    }
  }

  // --- Badge Updates ---
  function updateCartBadges() {
    const cart = getCart();
    const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
    document.querySelectorAll('.cart-count-badge').forEach(badge => {
      badge.textContent = totalItems;
      badge.style.display = totalItems > 0 ? 'inline-flex' : 'none';
    });
  }

  function updateWishlistBadges() {
    const wishlist = getWishlist();
    document.querySelectorAll('.wishlist-count-badge').forEach(badge => {
      badge.textContent = wishlist.length;
      badge.style.display = wishlist.length > 0 ? 'inline-flex' : 'none';
    });
    // Update heart icons
    document.querySelectorAll('[data-wishlist-id]').forEach(btn => {
      const id = btn.getAttribute('data-wishlist-id');
      const isFav = wishlist.includes(id);
      btn.classList.toggle('active', isFav);
      const icon = btn.querySelector('i');
      if (icon) {
        icon.className = isFav ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart';
      }
    });
  }

  // --- Add to Cart Action ---
  window.addToCart = function (id, name, price, image, sellerName, unit, quantity = 1) {
    if (!window.isUserLoggedIn) {
        window.location.href = 'login.php';
        return;
    }
    const cart = getCart();
    const existingIndex = cart.findIndex(item => String(item.id) === String(id));
    if (existingIndex > -1) {
        cart[existingIndex].quantity += parseInt(quantity, 10);
    } else {
        cart.push({
            id: String(id),
            name: name,
            price: parseFloat(price),
            image: image,
            sellerName: sellerName || 'Home Maker',
            unit: unit || 'item',
            quantity: parseInt(quantity, 10)
        });
    }
    saveCart(cart);
    showToast(`<strong>${name}</strong> added to your cart!`, 'success', 'Cart Updated');
};

  // --- Toggle Wishlist Action ---
  window.toggleWishlist = function (id, name = 'Item') {
    let wishlist = getWishlist();
    const idStr = String(id);
    const index = wishlist.indexOf(idStr);
    if (index > -1) {
      wishlist.splice(index, 1);
      showToast(`Removed <strong>${name}</strong> from your wishlist.`, 'info', 'Wishlist');
    } else {
      wishlist.push(idStr);
      showToast(`Saved <strong>${name}</strong> to your wishlist!`, 'success', 'Saved');
    }
    saveWishlist(wishlist);
  };

  // --- DOM Ready Initializer ---
  document.addEventListener('DOMContentLoaded', function () {
    updateCartBadges();
    updateWishlistBadges();

    // Sticky Header Scroll Shadow
    const header = document.querySelector('.sticky-header');
    if (header) {
      window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
          header.classList.add('shadow');
        } else {
          header.classList.remove('shadow');
        }
      });
    }

    // Quantity Increment/Decrement
    document.addEventListener('click', function (e) {
      if (e.target.closest('.qty-btn-plus')) {
        const input = e.target.closest('.qty-control-group').querySelector('.qty-input');
        if (input) input.value = parseInt(input.value || 1, 10) + 1;
      }
      if (e.target.closest('.qty-btn-minus')) {
        const input = e.target.closest('.qty-control-group').querySelector('.qty-input');
        if (input && parseInt(input.value, 10) > 1) {
          input.value = parseInt(input.value, 10) - 1;
        }
      }
    });

    // Product Gallery Image Switcher
    document.querySelectorAll('.gallery-thumb-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const targetImgSrc = this.getAttribute('data-img-src');
        const mainImg = document.getElementById('main-product-gallery-img');
        if (mainImg && targetImgSrc) {
          mainImg.src = targetImgSrc;
          document.querySelectorAll('.gallery-thumb-btn').forEach(b => b.classList.remove('active', 'border-maroon-800'));
          this.classList.add('active', 'border-maroon-800');
        }
      });
    });

    // Handle Form Submissions (Demo feedback)
    document.querySelectorAll('form.needs-validation').forEach(form => {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      });
    });
  });

  window.udyojika = {
    getCart,
    saveCart,
    getWishlist,
    saveWishlist,
    showToast
  };
})();
