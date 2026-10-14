// js/cart.js
(function () {
    const STORAGE_KEY = 'kimBelsCart';

    /* ---------- localStorage helpers ---------- */
    function loadCart() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return {};
            const parsed = JSON.parse(raw);
            if (typeof parsed !== 'object' || parsed === null) return {};
            const cleaned = {};
            const products = window.products || {};
            for (const [id, qty] of Object.entries(parsed)) {
                const n = Number(qty);
                if (products[id] && Number.isFinite(n) && n > 0) {
                    cleaned[id] = Math.min(99, Math.floor(n));
                }
            }
            return cleaned;
        } catch (err) {
            console.warn('Kon winkelwagen niet laden:', err);
            return {};
        }
    }

    function saveCart(cart) {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
        } catch (err) {
            console.warn('Kon winkelwagen niet opslaan:', err);
        }
    }

    /* ---------- State ---------- */
    let cart = loadCart();

    function getCart() {
        return { ...cart };
    }

    function setCart(newCart) {
        cart = newCart;
        saveCart(cart);
        window.dispatchEvent(new CustomEvent('cartUpdated', { detail: { cart } }));
    }

    function addToCart(productId, qty) {
        const products = window.products || {};
        const id = String(productId);
        if (!products[id]) return;
        const newCart = { ...cart };
        newCart[id] = Math.min(99, (newCart[id] || 0) + qty);
        setCart(newCart);
    }

    function removeFromCart(productId) {
        const id = String(productId);
        if (!cart[id]) return;
        const newCart = { ...cart };
        delete newCart[id];
        setCart(newCart);
    }

    function clearCart() {
        setCart({});
    }

    /* ---------- Publieke API ---------- */
    window.Cart = {
        getCart,
        setCart,
        addToCart,
        removeFromCart,
        clearCart,
        STORAGE_KEY,
    };

    /* ---------- Sync tussen tabbladen ---------- */
    window.addEventListener('storage', (e) => {
        if (e.key === STORAGE_KEY) {
            cart = loadCart();
            window.dispatchEvent(new CustomEvent('cartUpdated', { detail: { cart } }));
        }
    });
})();