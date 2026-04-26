// ── PRODUCT DATA ──
const products = [
    // DOG
    { id: 1, pet: 'dog', name: 'Royal Canin Maxi Adult', price: 1850, weight: '3 kg', desc: 'Tailored nutrition for large breed dogs with joint support formula.', image: 'https://images.pexels.com/photos/6568942/pexels-photo-6568942.jpeg' },
    { id: 2, pet: 'dog', name: 'Pedigree Chicken & Veg', price: 490, weight: '1.2 kg', desc: 'Wholesome dry food packed with vitamins, minerals & antioxidants.', image: 'https://images.pexels.com/photos/7309474/pexels-photo-7309474.jpeg' },
    { id: 3, pet: 'dog', name: 'Drools Puppy Starter', price: 720, weight: '1.5 kg', desc: 'DHA-enriched formula for puppies up to 12 months — brain & bone growth.', image: 'https://images.pexels.com/photos/7310213/pexels-photo-7310213.jpeg' },
    { id: 4, pet: 'dog', name: 'Farmina Wild Salmon Wet Food', price: 280, weight: '400 g', desc: 'Grain-free wet food with wild Atlantic salmon, peas & blueberries.', image: 'https://images.pexels.com/photos/12001951/pexels-photo-12001951.jpeg' },
    // CAT
    { id: 5, pet: 'cat', name: 'Whiskas Ocean Fish Dry', price: 390, weight: '1.1 kg', desc: 'Crunchy kibble with real ocean fish and essential taurine for heart health.', image: 'https://images.pexels.com/photos/34952073/pexels-photo-34952073.jpeg' },
    { id: 6, pet: 'cat', name: 'Royal Canin Kitten Instinctive', price: 620, weight: '850 g', desc: 'Soft mousse with precise nutrients for kittens in their growth phase.', image: 'https://images.pexels.com/photos/6901802/pexels-photo-6901802.jpeg' },
    { id: 7, pet: 'cat', name: 'Orijen Tuna & Mackerel Pâté', price: 340, weight: '150 g', desc: 'High-protein, grain-free wet food with 80% animal ingredients.', image: 'https://images.pexels.com/photos/13499753/pexels-photo-13499753.jpeg' },
    { id: 8, pet: 'cat', name: 'Me-O Creamy Treats Tuna', price: 160, weight: '60 g', desc: 'Irresistible lickable treat — double as a supplement & reward.', image: 'https://images.pexels.com/photos/8121148/pexels-photo-8121148.jpeg' },
    // FISH
    { id: 9, pet: 'fish', name: 'Tetra Min Tropical Flakes', price: 320, weight: '100 g', desc: 'Complete staple food for all tropical fish — rich in Omega-3 & vitamins.', image: 'https://images.pexels.com/photos/19723918/pexels-photo-19723918.jpeg' },
    { id: 10, pet: 'fish', name: 'Hikari Gold Goldfish Pellets', price: 450, weight: '150 g', desc: 'Color-enhancing pellets with natural carotenoids for vivid goldfish.', image: 'https://images.pexels.com/photos/6994944/pexels-photo-6994944.jpeg' },
    { id: 11, pet: 'fish', name: 'Ocean Free Betta Premium Blend', price: 280, weight: '75 g', desc: 'Micro-pellets with bloodworm protein — designed for Betta fighters.', image: 'https://images.pexels.com/photos/36186523/pexels-photo-36186523.jpeg' },
    { id: 12, pet: 'fish', name: 'Sera Discus Granules', price: 680, weight: '250 g', desc: 'Sinking granules with spirulina & krill for South American cichlids.', image: 'https://images.pexels.com/photos/32063427/pexels-photo-32063427.jpeg' },
];

let cart = [];
let activeFilter = 'all';
let searchQuery = '';

// DOM refs
const productsGrid = document.getElementById('productsGrid');
const searchInput = document.getElementById('searchInput');
const cartBtn = document.getElementById('cartBtn');
const cartCount = document.getElementById('cartCount');
const cartSidebar = document.getElementById('cartSidebar');
const cartOverlay = document.getElementById('cartOverlay');
const closeCartBtn = document.getElementById('closeCartBtn');
const cartItemsList = document.getElementById('cartItemsList');
const cartTotal = document.getElementById('cartTotal');
const checkoutBtn = document.getElementById('checkoutBtn');
const toastEl = document.getElementById('toast');
const sectionLabel = document.getElementById('sectionLabel');
const pills = document.querySelectorAll('.pill');

// ── TOAST ──
let toastTimer;
function showToast(msg) {
    clearTimeout(toastTimer);
    toastEl.textContent = msg;
    toastEl.classList.add('show');
    toastTimer = setTimeout(() => toastEl.classList.remove('show'), 2400);
}

// ── CART OPEN/CLOSE ──
function openCart() {
    cartSidebar.classList.add('open');
    cartOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
    renderCartItems();
}
function closeCart() {
    cartSidebar.classList.remove('open');
    cartOverlay.classList.remove('active');
    document.body.style.overflow = '';
}

// ── CART LOGIC ──
function addToCart(product) {
    const petEmoji = product.pet === 'dog' ? '🐶' : product.pet === 'cat' ? '🐱' : '🐠';
    const existing = cart.find(i => i.id === product.id);
    if (existing) {
        existing.qty++;
        showToast(`➕ ${product.name} — quantity updated`);
    } else {
        cart.push({ id: product.id, name: product.name, price: product.price, emoji: petEmoji, qty: 1 });
        showToast(`🛒 ${product.name} added to cart`);
    }
    saveCart();
    updateCartCount();
}

function buyNow(product) {
    cart = [];
    addToCart(product);
    openCart();
}

function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) {
        cart = cart.filter(i => i.id !== id);
        showToast('🗑️ Item removed');
    } else {
        showToast('🔄 Cart updated');
    }
    saveCart();
    updateCartCount();
    renderCartItems();
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    saveCart();
    updateCartCount();
    renderCartItems();
    showToast('🗑️ Removed from cart');
}

function saveCart() {
    try { localStorage.setItem('pawfeast_cart', JSON.stringify(cart)); } catch (e) { }
}

function loadCart() {
    try {
        const saved = localStorage.getItem('pawfeast_cart');
        if (saved) { cart = JSON.parse(saved); }
    } catch (e) { cart = []; }
    updateCartCount();
}

function updateCartCount() {
    const total = cart.reduce((s, i) => s + i.qty, 0);
    cartCount.textContent = total;
}

// ── RENDER CART SIDEBAR ──
function renderCartItems() {
    if (cart.length === 0) {
        cartItemsList.innerHTML = '<p class="empty-cart-msg">Your bowl is empty 🍽️<br>Add some tasty treats!</p>';
        cartTotal.textContent = '₹0';
        return;
    }
    cartItemsList.innerHTML = '';
    let total = 0;
    cart.forEach(item => {
        total += item.price * item.qty;
        const div = document.createElement('div');
        div.className = 'cart-item';
        div.innerHTML = `
                <div class="cart-item-left">
                    <div class="cart-item-emoji">${item.emoji}</div>
                    <div class="cart-item-info">
                        <p>${item.name}</p>
                        <small>₹${item.price.toLocaleString('en-IN')}</small>
                    </div>
                </div>
                <div class="cart-item-right">
                    <button class="qty-btn decr" data-id="${item.id}">−</button>
                    <span class="qty-count">${item.qty}</span>
                    <button class="qty-btn incr" data-id="${item.id}">+</button>
                    <button class="remove-btn" data-id="${item.id}">🗑️</button>
                </div>
            `;
        cartItemsList.appendChild(div);
    });
    cartTotal.textContent = `₹${total.toLocaleString('en-IN')}`;

    // Attach listeners
    cartItemsList.querySelectorAll('.decr').forEach(b => b.addEventListener('click', () => changeQty(parseInt(b.dataset.id), -1)));
    cartItemsList.querySelectorAll('.incr').forEach(b => b.addEventListener('click', () => changeQty(parseInt(b.dataset.id), +1)));
    cartItemsList.querySelectorAll('.remove-btn').forEach(b => b.addEventListener('click', () => removeFromCart(parseInt(b.dataset.id))));
}

// ── CHECKOUT ──
function handleCheckout() {
    if (cart.length === 0) {
        showToast('🛒 Your cart is empty — add some products first!');
        return;
    }

    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);

    // Fill modal with order details
    modalOrderItems.innerHTML = cart.map(item => `
        <div class="order-summary-item">
            <span class="item-name">${item.emoji} ${item.name}</span>
            <span class="item-qty">×${item.qty}</span>
            <span class="item-price">₹${(item.price * item.qty).toLocaleString('en-IN')}</span>
        </div>
    `).join('');
    modalOrderTotal.textContent = `₹${total.toLocaleString('en-IN')}`;

    // Show modal
    orderOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';

    // Clear cart
    cart = [];
    saveCart();
    updateCartCount();
    closeCart();
}

// ── RENDER PRODUCTS ──
function renderProducts() {
    const lq = searchQuery.toLowerCase().trim();
    let filtered = products.filter(p => {
        const matchFilter = activeFilter === 'all' || p.pet === activeFilter;
        const matchSearch = !lq ||
            p.name.toLowerCase().includes(lq) ||
            p.pet.toLowerCase().includes(lq) ||
            p.desc.toLowerCase().includes(lq);
        return matchFilter && matchSearch;
    });

    // Update section label
    const labels = { all: 'All Products', dog: '🐶 Dog Food', cat: '🐱 Cat Food', fish: '🐠 Fish Food' };
    sectionLabel.textContent = labels[activeFilter] || 'All Products';
    if (lq) sectionLabel.textContent = `Search: "${lq}"`;

    productsGrid.innerHTML = '';

    if (filtered.length === 0) {
        productsGrid.innerHTML = `<div class="empty-message"><span>🐾</span>No products found.<br>Try "salmon", "kitten", or "betta".</div>`;
        return;
    }

    filtered.forEach(p => {
        const bgClass = `${p.pet}-bg`;
        const petEmoji = '📦';
        const card = document.createElement('div');
        card.className = 'product-card';
        const imgDiv = document.createElement('div');
        imgDiv.className = `product-img ${bgClass}`;

        const img = document.createElement('img');
        img.src = p.image;
        img.alt = p.name;
        img.style.display = 'none';

        const fallback = document.createElement('span');
        fallback.className = 'fallback-emoji';
        fallback.textContent = petEmoji;

        img.onload = function () {
            img.style.display = 'block';
            fallback.style.display = 'none';
        };

        img.onerror = function () {
            img.style.display = 'none';
            fallback.style.display = 'block';
        };

        imgDiv.appendChild(img);
        imgDiv.appendChild(fallback);

        const infoDiv = document.createElement('div');
        infoDiv.className = 'product-info';
        infoDiv.innerHTML = `
                <div class="product-title">${p.name}</div>
                <div class="product-desc">${p.desc}</div>
                <div class="product-weight">📦 ${p.weight}</div>
                <div class="product-price">₹${p.price.toLocaleString('en-IN')} <small>incl. taxes</small></div>
                <div class="button-group">
                    <button class="btn-buy"  data-id="${p.id}">Buy Now</button>
                    <button class="btn-cart" data-id="${p.id}">+ Cart</button>
                </div>
            `;

        card.innerHTML = `<span class="pet-badge ${p.pet}">${p.pet === 'dog' ? '🐶 Dog' : p.pet === 'cat' ? '🐱 Cat' : '🐠 Fish'}</span>`;
        card.appendChild(imgDiv);
        card.appendChild(infoDiv);
        productsGrid.appendChild(card);
    });

    productsGrid.querySelectorAll('.btn-buy').forEach(b => {
        b.addEventListener('click', () => {
            const prod = products.find(p => p.id === parseInt(b.dataset.id));
            if (prod) buyNow(prod);
        });
    });
    productsGrid.querySelectorAll('.btn-cart').forEach(b => {
        b.addEventListener('click', () => {
            const prod = products.find(p => p.id === parseInt(b.dataset.id));
            if (prod) addToCart(prod);
        });
    });
}

// ── EVENT LISTENERS ──
cartBtn.addEventListener('click', openCart);
closeCartBtn.addEventListener('click', closeCart);
cartOverlay.addEventListener('click', closeCart);
checkoutBtn.addEventListener('click', handleCheckout);
searchInput.addEventListener('input', () => {
    searchQuery = searchInput.value;
    renderProducts();
});
pills.forEach(pill => {
    pill.addEventListener('click', () => {
        pills.forEach(p => p.classList.remove('active'));
        pill.classList.add('active');
        activeFilter = pill.dataset.filter;
        searchQuery = '';
        searchInput.value = '';
        renderProducts();
    });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && cartSidebar.classList.contains('open')) closeCart();
});

// ── INIT ──
loadCart();
renderProducts();
