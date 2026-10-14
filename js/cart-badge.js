// js/cart-badge.js
// Leest de winkelwagen uit localStorage en werkt de badge in de navbar bij.
// Wordt op ELKE pagina geladen, zodat de teller altijd klopt.


console.log('cart-badge.js is geladen');   // ← helemaal bovenaan het bestand




(function () {
    const STORAGE_KEY = 'kimBelsCart';

    /**
     * Telt het totaal aantal items in de opgeslagen winkelwagen.
     * @returns {number}
     */
    function getCartItemCount() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return 0;

            const parsed = JSON.parse(raw);
            if (typeof parsed !== 'object' || parsed === null) return 0;

            // Tel alleen geldige, positieve aantallen op
            let total = 0;
            for (const qty of Object.values(parsed)) {
                const n = Number(qty);
                if (Number.isFinite(n) && n > 0) total += n;
            }
            return total;
        } catch (err) {
            console.warn('Kon winkelwagen niet lezen:', err);
            return 0;
        }
    }

    /**
     * Werkt de badge in de navbar bij.
     */
function updateCartBadge() {
    console.log('updateCartBadge aangeroepen');

    const badge = document.getElementById('cart-count');
    if (!badge) return;

    const count = getCartItemCount();
    console.log('Cart count:', count);

    badge.textContent = count;
    badge.dataset.count = count;

    badge.style.display = count > 0 ? 'inline-flex' : 'none';
}

    // function updateCartBadge() {
    //     const badge = document.getElementById('cart-count');
    //     if (!badge) return;
    //     badge.textContent = getCartItemCount();
    // }


    // 1) Bij het laden van de pagina
    document.addEventListener('DOMContentLoaded', updateCartBadge);

    // 2) Als de winkelwagen in een ánder tabblad verandert
    window.addEventListener('storage', (e) => {
        if (e.key === STORAGE_KEY) updateCartBadge();
    });

    // 3) Ook als je via back/forward terugkomt op deze pagina
    window.addEventListener('pageshow', updateCartBadge);

// 4) Reageer op wijzigingen die het inline script doet
window.addEventListener('cartUpdated', updateCartBadge);

})();