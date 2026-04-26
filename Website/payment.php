<?php
session_start();

// ── SESSION GUARD: redirect to login if not authenticated ──
if (empty($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$user_name  = htmlspecialchars($_SESSION['user_name']  ?? 'Pet Lover');
$user_email = htmlspecialchars($_SESSION['user_email'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>PawFeast | Premium Pet Nutrition</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,300&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --moss:    #3d5a3e;
            --moss-lt: #537055;
            --sage:    #a8bf9a;
            --cream:   #f7f2e8;
            --parchment: #ede5d4;
            --amber:   #c97a2f;
            --amber-lt:#e8a05a;
            --bark:    #2e2016;
            --warm-mid:#7a5c3a;
            --card-bg: #fffdf8;
            --border:  #e4d9c6;
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            background: var(--cream);
            color: var(--bark);
            font-family: 'DM Sans', sans-serif;
            line-height: 1.5;
            min-height: 100vh;
        }

        /* scrollbar */
        ::-webkit-scrollbar { width: 7px; }
        ::-webkit-scrollbar-track { background: var(--parchment); }
        ::-webkit-scrollbar-thumb { background: var(--sage); border-radius: 10px; }

        /* ─── HEADER ─── */
        .header {
            background: var(--moss);
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 72px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .brand {
            display: flex;
            align-items: baseline;
            gap: 10px;
        }
        .brand h1 {
            font-family: 'Fraunces', serif;
            font-size: 1.9rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .brand h1 span { color: var(--amber-lt); }
        .brand-tag {
            font-size: 0.72rem;
            color: var(--sage);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* User chip */
        .user-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 50px;
            padding: 6px 14px 6px 10px;
        }
        .user-avatar {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: var(--amber);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
        }
        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: #fff;
        }

        .logout-btn {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 7px 14px;
            border-radius: 50px;
            color: rgba(255,255,255,0.85);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }
        .logout-btn:hover { background: rgba(255,255,255,0.2); color: #fff; }

        .cart-btn {
            background: var(--amber);
            border: none;
            padding: 10px 22px;
            border-radius: 50px;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .cart-btn:hover { background: #b0681f; transform: scale(1.03); }
        .cart-badge {
            background: #fff;
            color: var(--amber);
            font-size: 0.8rem;
            font-weight: 700;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ─── HERO STRIP ─── */
        .hero {
            background: linear-gradient(135deg, var(--moss) 0%, #4e7550 50%, #3a5c3b 100%);
            padding: 48px 40px 52px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 320px; height: 320px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-inner { max-width: 1260px; margin: 0 auto; }
        .hero h2 {
            font-family: 'Fraunces', serif;
            font-size: 2.6rem;
            font-weight: 600;
            color: #fff;
            line-height: 1.2;
            max-width: 540px;
            margin-bottom: 10px;
        }
        .hero h2 em {
            font-style: italic;
            font-weight: 300;
            color: var(--amber-lt);
        }
        .hero p { color: var(--sage); font-size: 1rem; }

        /* ─── MAIN WRAPPER ─── */
        .main { max-width: 1260px; margin: 0 auto; padding: 40px 28px 80px; }

        /* ─── FILTER BAR ─── */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            align-items: center;
            margin-bottom: 40px;
        }
        .search-box {
            flex: 1;
            min-width: 200px;
            max-width: 400px;
            padding: 13px 20px;
            border-radius: 50px;
            border: 1.5px solid var(--border);
            background: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            color: var(--bark);
        }
        .search-box::placeholder { color: #b8a88a; }
        .search-box:focus {
            border-color: var(--moss-lt);
            box-shadow: 0 0 0 3px rgba(83,112,85,0.15);
        }
        .filter-pills {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .pill {
            padding: 8px 18px;
            border-radius: 50px;
            border: 1.5px solid var(--border);
            background: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.18s;
            color: var(--warm-mid);
        }
        .pill:hover { border-color: var(--moss); color: var(--moss); }
        .pill.active {
            background: var(--moss);
            border-color: var(--moss);
            color: #fff;
        }

        /* ─── SECTION LABEL ─── */
        .section-label {
            font-family: 'Fraunces', serif;
            font-size: 1.7rem;
            font-weight: 600;
            color: var(--moss);
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 1.5px;
            background: var(--border);
            border-radius: 4px;
        }

        /* ─── PRODUCT GRID ─── */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
            gap: 26px;
            margin-bottom: 50px;
        }
        .product-card {
            background: var(--card-bg);
            border-radius: 22px;
            overflow: hidden;
            border: 1.5px solid var(--border);
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s;
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.13);
            border-color: var(--sage);
        }
        .pet-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            background: var(--moss);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 30px;
        }
        .pet-badge.cat  { background: #7b5ea7; }
        .pet-badge.fish { background: #2e7fa8; }
        .pet-badge.dog  { background: var(--amber); }

        .product-img {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4.5rem;
            position: relative;
            overflow: hidden;
        }
        .product-img.dog-bg  { background: linear-gradient(135deg, #fdf0e0 0%, #f7e0c4 100%); }
        .product-img.cat-bg  { background: linear-gradient(135deg, #f0eafa 0%, #e4d8f5 100%); }
        .product-img.fish-bg { background: linear-gradient(135deg, #dff0fa 0%, #c8e4f5 100%); }
        .product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: drop-shadow(0 2px 12px rgba(0,0,0,0.12));
        }
        .product-img .fallback-emoji {
            font-size: 5rem;
            filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.1));
        }

        .product-info { padding: 18px 18px 22px; }
        .product-title {
            font-family: 'Fraunces', serif;
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: -0.2px;
            margin-bottom: 5px;
            line-height: 1.3;
        }
        .product-desc {
            font-size: 0.82rem;
            color: #8a7456;
            margin-bottom: 12px;
            line-height: 1.4;
        }
        .product-weight {
            display: inline-block;
            background: var(--parchment);
            color: var(--warm-mid);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 12px;
        }
        .product-price {
            font-size: 1.55rem;
            font-weight: 700;
            color: var(--amber);
            margin-bottom: 16px;
        }
        .product-price small {
            font-size: 0.8rem;
            font-weight: 400;
            color: #a08a6e;
        }
        .button-group { display: flex; gap: 10px; }
        .btn-buy {
            flex: 1;
            background: var(--moss);
            border: none;
            padding: 10px 0;
            border-radius: 40px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
            font-size: 0.85rem;
            transition: background 0.18s;
        }
        .btn-buy:hover { background: var(--moss-lt); }
        .btn-cart {
            flex: 1;
            background: transparent;
            border: 1.5px solid var(--border);
            padding: 10px 0;
            border-radius: 40px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            color: var(--warm-mid);
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.18s;
        }
        .btn-cart:hover { background: var(--parchment); border-color: var(--amber-lt); color: var(--amber); }

        /* ─── EMPTY STATE ─── */
        .empty-message {
            grid-column: 1 / -1;
            text-align: center;
            padding: 70px 20px;
            background: #fefcf7;
            border-radius: 24px;
            color: #b0976e;
            font-family: 'Fraunces', serif;
            font-size: 1.4rem;
            font-weight: 300;
            font-style: italic;
            border: 1.5px dashed var(--border);
        }
        .empty-message span { display: block; font-size: 3rem; margin-bottom: 12px; }

        /* ─── CART SIDEBAR ─── */
        .cart-overlay {
            position: fixed; inset: 0;
            background: rgba(30,20,10,0.3);
            backdrop-filter: blur(3px);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s, visibility 0.25s;
        }
        .cart-overlay.active { opacity: 1; visibility: visible; }

        .cart-sidebar {
            position: fixed;
            top: 0; right: -440px;
            width: 420px;
            max-width: 95vw;
            height: 100vh;
            background: var(--card-bg);
            z-index: 999;
            display: flex;
            flex-direction: column;
            box-shadow: -12px 0 40px rgba(0,0,0,0.12);
            border-left: 1.5px solid var(--border);
            transition: right 0.3s cubic-bezier(0.25, 0.9, 0.35, 1.05);
        }
        .cart-sidebar.open { right: 0; }

        .cart-head {
            padding: 22px 22px 16px;
            border-bottom: 1.5px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .cart-head h2 {
            font-family: 'Fraunces', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--moss);
        }
        .close-cart {
            background: var(--parchment);
            border: none;
            width: 34px; height: 34px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1rem;
            color: var(--warm-mid);
            display: flex; align-items: center; justify-content: center;
            transition: background 0.18s;
        }
        .close-cart:hover { background: var(--border); }

        .cart-items-list {
            flex: 1;
            overflow-y: auto;
            padding: 14px 20px;
        }
        .empty-cart-msg {
            text-align: center;
            color: #b0966e;
            padding: 50px 10px;
            font-family: 'Fraunces', serif;
            font-style: italic;
            font-size: 1rem;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
            gap: 10px;
        }
        .cart-item-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }
        .cart-item-emoji { font-size: 2rem; flex-shrink: 0; }
        .cart-item-info { min-width: 0; }
        .cart-item-info p {
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cart-item-info small { color: var(--warm-mid); font-size: 0.82rem; }
        .cart-item-right {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }
        .qty-btn {
            background: var(--parchment);
            border: none;
            width: 26px; height: 26px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--bark);
            display: flex; align-items: center; justify-content: center;
            transition: background 0.15s;
        }
        .qty-btn:hover { background: var(--border); }
        .qty-count {
            font-size: 0.9rem;
            font-weight: 700;
            min-width: 18px;
            text-align: center;
        }
        .remove-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            color: #c07070;
            padding: 2px 4px;
            transition: transform 0.15s;
        }
        .remove-btn:hover { transform: scale(1.2); }

        .cart-footer {
            padding: 18px 22px 28px;
            border-top: 1.5px solid var(--border);
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 16px;
        }
        .total-row span:last-child { color: var(--amber); }
        .checkout-btn {
            width: 100%;
            background: var(--moss);
            border: none;
            padding: 14px;
            border-radius: 50px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
        }
        .checkout-btn:hover { background: var(--moss-lt); transform: scale(1.01); }

        /* ─── TOAST ─── */
        .toast {
            position: fixed;
            bottom: 30px; left: 50%;
            transform: translateX(-50%) translateY(10px);
            background: var(--bark);
            color: #f5e9d8;
            padding: 12px 28px;
            border-radius: 50px;
            font-size: 0.88rem;
            font-weight: 500;
            z-index: 1200;
            opacity: 0;
            transition: opacity 0.22s, transform 0.22s;
            pointer-events: none;
            white-space: nowrap;
            box-shadow: 0 8px 24px rgba(0,0,0,0.22);
            max-width: 90vw;
        }
        .toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

        /* ─── FOOTER ─── */
        footer {
            text-align: center;
            font-size: 0.78rem;
            color: #a08a6e;
            border-top: 1.5px solid var(--border);
            padding: 28px 20px;
            letter-spacing: 0.3px;
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 640px) {
            .header { padding: 0 16px; }
            .hero { padding: 36px 16px; }
            .hero h2 { font-size: 2rem; }
            .main { padding: 28px 14px 60px; }
            .brand h1 { font-size: 1.5rem; }
            .button-group { flex-direction: column; }
            .user-chip .user-name { display: none; }
        }

        /* ════════════════════════════════════════════════
           ORDER SUCCESS MODAL
        ════════════════════════════════════════════════ */
        .order-overlay {
            position: fixed; inset: 0;
            background: rgba(20, 14, 6, 0.55);
            backdrop-filter: blur(8px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .order-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .order-modal {
            background: var(--card-bg);
            border-radius: 32px;
            padding: 48px 44px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 40px 80px -16px rgba(0,0,0,0.3);
            border: 1.5px solid var(--border);
            transform: scale(0.85) translateY(20px);
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
            position: relative;
            overflow: hidden;
            text-align: center;
        }
        .order-overlay.active .order-modal {
            transform: scale(1) translateY(0);
        }

        /* confetti paw prints */
        .confetti-paw {
            position: absolute;
            font-size: 1.4rem;
            animation: confettiFall 3s ease-in-out infinite;
            opacity: 0;
            pointer-events: none;
        }
        .confetti-paw:nth-child(1)  { left: 8%;  top: -20px; animation-delay: 0.0s; }
        .confetti-paw:nth-child(2)  { left: 25%; top: -20px; animation-delay: 0.3s; }
        .confetti-paw:nth-child(3)  { left: 50%; top: -20px; animation-delay: 0.6s; }
        .confetti-paw:nth-child(4)  { left: 72%; top: -20px; animation-delay: 0.9s; }
        .confetti-paw:nth-child(5)  { left: 88%; top: -20px; animation-delay: 1.2s; }
        .confetti-paw:nth-child(6)  { left: 40%; top: -20px; animation-delay: 1.5s; }
        .confetti-paw:nth-child(7)  { left: 60%; top: -20px; animation-delay: 0.4s; }
        .confetti-paw:nth-child(8)  { left: 15%; top: -20px; animation-delay: 1.8s; }

        @keyframes confettiFall {
            0%   { opacity: 0;   transform: translateY(-10px) rotate(0deg); }
            15%  { opacity: 1; }
            80%  { opacity: 0.6; }
            100% { opacity: 0;   transform: translateY(520px) rotate(360deg); }
        }

        /* checkmark ring */
        .success-ring {
            width: 90px; height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e8f5e4 0%, #d5edd0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            border: 3px solid var(--sage);
            animation: ringPop 0.5s cubic-bezier(0.22, 1, 0.36, 1) 0.15s both;
            position: relative;
        }
        .success-ring::after {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 2px dashed var(--sage);
            animation: spin 8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        @keyframes ringPop {
            from { transform: scale(0.5); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }

        .check-icon {
            font-size: 2.6rem;
            animation: iconBounce 0.45s cubic-bezier(0.34, 1.56, 0.64, 1) 0.35s both;
        }
        @keyframes iconBounce {
            from { transform: scale(0); }
            to   { transform: scale(1); }
        }

        .modal-title {
            font-family: 'Fraunces', serif;
            font-size: 1.9rem;
            font-weight: 700;
            color: var(--moss);
            margin-bottom: 6px;
            animation: fadeUp 0.4s ease 0.4s both;
        }
        .modal-sub {
            font-size: 0.95rem;
            color: var(--warm-mid);
            margin-bottom: 28px;
            animation: fadeUp 0.4s ease 0.5s both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* order summary box */
        .order-summary-box {
            background: var(--cream);
            border: 1.5px solid var(--border);
            border-radius: 18px;
            padding: 18px 20px;
            margin-bottom: 20px;
            text-align: left;
            animation: fadeUp 0.4s ease 0.55s both;
        }
        .order-summary-box .summary-head {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--warm-mid);
            margin-bottom: 12px;
        }
        .order-summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.88rem;
            padding: 5px 0;
            border-bottom: 1px dashed var(--border);
            color: var(--bark);
        }
        .order-summary-item:last-child { border-bottom: none; }
        .order-summary-item .item-name { flex: 1; }
        .order-summary-item .item-qty {
            color: var(--warm-mid);
            margin: 0 12px;
            font-size: 0.82rem;
        }
        .order-summary-item .item-price {
            font-weight: 600;
            color: var(--amber);
        }

        .order-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            background: var(--moss);
            border-radius: 14px;
            margin-bottom: 24px;
            animation: fadeUp 0.4s ease 0.6s both;
        }
        .order-total-label {
            font-weight: 700;
            color: var(--sage);
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }
        .order-total-amount {
            font-family: 'Fraunces', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--amber-lt);
        }

        .order-thanks {
            font-size: 0.88rem;
            color: var(--warm-mid);
            margin-bottom: 28px;
            line-height: 1.6;
            animation: fadeUp 0.4s ease 0.65s both;
        }
        .order-thanks strong { color: var(--moss); }

        .order-close-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--amber) 0%, #e8922a 100%);
            border: none;
            border-radius: 50px;
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(201,122,47,0.35);
            transition: transform 0.15s, box-shadow 0.2s;
            animation: fadeUp 0.4s ease 0.7s both;
            letter-spacing: 0.3px;
        }
        .order-close-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(201,122,47,0.45);
        }
        .order-close-btn:active { transform: scale(0.98); }
    </style>
</head>
<body>

<!-- ─── HEADER ─── -->
<header class="header">
    <div class="brand">
        <h1>🐾 Paw<span>Feast</span></h1>
        <span class="brand-tag">Premium Pet Nutrition</span>
    </div>
    <div class="header-right">
        <div class="user-chip">
            <div class="user-avatar"><?= strtoupper(substr($user_name, 0, 1)) ?></div>
            <span class="user-name"><?= $user_name ?></span>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
        <button class="cart-btn" id="cartBtn">
            🛒 Cart <span class="cart-badge" id="cartCount">0</span>
        </button>
    </div>
</header>

<!-- ─── HERO ─── -->
<section class="hero">
    <div class="hero-inner">
        <h2>Nutrition your pets <em>deserve</em>, from people who care</h2>
        <p>Curated premium food for dogs, cats & fish — real ingredients, zero compromise.</p>
    </div>
</section>

<!-- ─── MAIN ─── -->
<main class="main">
    <div class="filter-bar">
        <input type="text" id="searchInput" class="search-box" placeholder="🔍 Search by pet, brand, or ingredient…">
        <div class="filter-pills">
            <button class="pill active" data-filter="all">All</button>
            <button class="pill" data-filter="dog">🐶 Dog</button>
            <button class="pill" data-filter="cat">🐱 Cat</button>
            <button class="pill" data-filter="fish">🐠 Fish</button>
        </div>
    </div>

    <div class="section-label" id="sectionLabel">All Products</div>
    <div class="products-grid" id="productsGrid"></div>

    <footer>
        🐾 PawFeast — Made with love for your furry (and scaly) companions &nbsp;·&nbsp; Free delivery on orders above ₹999
    </footer>
</main>

<!-- ─── CART SIDEBAR ─── -->
<div class="cart-overlay" id="cartOverlay"></div>
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-head">
        <h2>🛒 Your Bowl</h2>
        <button class="close-cart" id="closeCartBtn">✕</button>
    </div>
    <div class="cart-items-list" id="cartItemsList">
        <p class="empty-cart-msg">Your bowl is empty 🍽️<br>Add some tasty treats!</p>
    </div>
    <div class="cart-footer">
        <div class="total-row">
            <span>Total</span>
            <span id="cartTotal">₹0</span>
        </div>
        <button class="checkout-btn" id="checkoutBtn">Place Order 🐾</button>
    </div>
</div>

<!-- ════════════════════════════════════════════════
     ORDER SUCCESS MODAL
════════════════════════════════════════════════ -->
<div class="order-overlay" id="orderOverlay">
    <div class="order-modal" id="orderModal">
        <!-- Confetti paw prints -->
        <span class="confetti-paw">🐾</span>
        <span class="confetti-paw">🐾</span>
        <span class="confetti-paw">🌟</span>
        <span class="confetti-paw">🐾</span>
        <span class="confetti-paw">✨</span>
        <span class="confetti-paw">🐾</span>
        <span class="confetti-paw">🌟</span>
        <span class="confetti-paw">🐾</span>

        <div class="success-ring">
            <span class="check-icon">✅</span>
        </div>

        <h2 class="modal-title">Order Placed!</h2>
        <p class="modal-sub">Your furry friends are going to love this 🐶🐱🐠</p>

        <div class="order-summary-box">
            <div class="summary-head">📦 Order Summary</div>
            <div id="modalOrderItems"><!-- filled by JS --></div>
        </div>

        <div class="order-total-row">
            <span class="order-total-label">Total Paid</span>
            <span class="order-total-amount" id="modalOrderTotal">₹0</span>
        </div>

        <p class="order-thanks">
            Thank you, <strong><?= $user_name ?>!</strong> Your order is confirmed and will be delivered soon.
            We'll send a confirmation to <strong><?= $user_email ?></strong>.
        </p>

        <button class="order-close-btn" id="orderCloseBtn">Continue Shopping 🛍️</button>
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
    // ── PRODUCT DATA ──
    const products = [
        { id: 1,  pet: 'dog',  name: 'Royal Canin Maxi Adult',       price: 1850, weight: '3 kg',  desc: 'Tailored nutrition for large breed dogs with joint support formula.', image: 'https://images.pexels.com/photos/6568942/pexels-photo-6568942.jpeg' },
        { id: 2,  pet: 'dog',  name: 'Pedigree Chicken & Veg',        price: 490,  weight: '1.2 kg',desc: 'Wholesome dry food packed with vitamins, minerals & antioxidants.', image: 'https://images.pexels.com/photos/7309474/pexels-photo-7309474.jpeg' },
        { id: 3,  pet: 'dog',  name: 'Drools Puppy Starter',          price: 720,  weight: '1.5 kg',desc: 'DHA-enriched formula for puppies up to 12 months — brain & bone growth.', image: 'https://images.pexels.com/photos/7310213/pexels-photo-7310213.jpeg' },
        { id: 4,  pet: 'dog',  name: 'Farmina Wild Salmon Wet Food',  price: 280,  weight: '400 g', desc: 'Grain-free wet food with wild Atlantic salmon, peas & blueberries.', image: 'https://images.pexels.com/photos/12001951/pexels-photo-12001951.jpeg' },
        { id: 5,  pet: 'cat',  name: 'Whiskas Ocean Fish Dry',        price: 390,  weight: '1.1 kg',desc: 'Crunchy kibble with real ocean fish and essential taurine for heart health.', image: 'https://images.pexels.com/photos/34952073/pexels-photo-34952073.jpeg' },
        { id: 6,  pet: 'cat',  name: 'Royal Canin Kitten Instinctive',price: 620,  weight: '850 g', desc: 'Soft mousse with precise nutrients for kittens in their growth phase.', image: 'https://images.pexels.com/photos/6901802/pexels-photo-6901802.jpeg' },
        { id: 7,  pet: 'cat',  name: 'Orijen Tuna & Mackerel Pâté',  price: 340,  weight: '150 g', desc: 'High-protein, grain-free wet food with 80% animal ingredients.', image: 'https://images.pexels.com/photos/13499753/pexels-photo-13499753.jpeg' },
        { id: 8,  pet: 'cat',  name: 'Me-O Creamy Treats Tuna',       price: 160,  weight: '60 g',  desc: 'Irresistible lickable treat — double as a supplement & reward.', image: 'https://images.pexels.com/photos/8121148/pexels-photo-8121148.jpeg' },
        { id: 9,  pet: 'fish', name: 'Tetra Min Tropical Flakes',     price: 320,  weight: '100 g', desc: 'Complete staple food for all tropical fish — rich in Omega-3 & vitamins.', image: 'https://images.pexels.com/photos/19723918/pexels-photo-19723918.jpeg' },
        { id: 10, pet: 'fish', name: 'Hikari Gold Goldfish Pellets',   price: 450,  weight: '150 g', desc: 'Color-enhancing pellets with natural carotenoids for vivid goldfish.', image: 'https://images.pexels.com/photos/6994944/pexels-photo-6994944.jpeg' },
        { id: 11, pet: 'fish', name: 'Ocean Free Betta Premium Blend', price: 280,  weight: '75 g',  desc: 'Micro-pellets with bloodworm protein — designed for Betta fighters.', image: 'https://images.pexels.com/photos/36186523/pexels-photo-36186523.jpeg' },
        { id: 12, pet: 'fish', name: 'Sera Discus Granules',           price: 680,  weight: '250 g', desc: 'Sinking granules with spirulina & krill for South American cichlids.', image: 'https://images.pexels.com/photos/32063427/pexels-photo-32063427.jpeg' },
    ];

    let cart = [];
    let activeFilter = 'all';
    let searchQuery = '';

    const productsGrid  = document.getElementById('productsGrid');
    const searchInput   = document.getElementById('searchInput');
    const cartBtn       = document.getElementById('cartBtn');
    const cartCount     = document.getElementById('cartCount');
    const cartSidebar   = document.getElementById('cartSidebar');
    const cartOverlay   = document.getElementById('cartOverlay');
    const closeCartBtn  = document.getElementById('closeCartBtn');
    const cartItemsList = document.getElementById('cartItemsList');
    const cartTotal     = document.getElementById('cartTotal');
    const checkoutBtn   = document.getElementById('checkoutBtn');
    const toastEl       = document.getElementById('toast');
    const sectionLabel  = document.getElementById('sectionLabel');
    const pills         = document.querySelectorAll('.pill');

    // ── ORDER MODAL REFS ──
    const orderOverlay   = document.getElementById('orderOverlay');
    const orderCloseBtn  = document.getElementById('orderCloseBtn');
    const modalOrderItems = document.getElementById('modalOrderItems');
    const modalOrderTotal = document.getElementById('modalOrderTotal');

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
        try { localStorage.setItem('pawfeast_cart', JSON.stringify(cart)); } catch(e) {}
    }

    function loadCart() {
        try {
            const saved = localStorage.getItem('pawfeast_cart');
            if (saved) { cart = JSON.parse(saved); }
        } catch(e) { cart = []; }
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

        cartItemsList.querySelectorAll('.decr').forEach(b => b.addEventListener('click', () => changeQty(parseInt(b.dataset.id), -1)));
        cartItemsList.querySelectorAll('.incr').forEach(b => b.addEventListener('click', () => changeQty(parseInt(b.dataset.id), +1)));
        cartItemsList.querySelectorAll('.remove-btn').forEach(b => b.addEventListener('click', () => removeFromCart(parseInt(b.dataset.id))));
    }

    // ── CHECKOUT → ORDER MODAL ──
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

    function closeOrderModal() {
        orderOverlay.classList.remove('active');
        document.body.style.overflow = '';
        showToast('🎉 Happy feeding time for your pets!');
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
            fallback.textContent = '📦';

            img.onload = () => { img.style.display = 'block'; fallback.style.display = 'none'; };
            img.onerror = () => { img.style.display = 'none'; fallback.style.display = 'block'; };

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
    orderCloseBtn.addEventListener('click', closeOrderModal);
    orderOverlay.addEventListener('click', e => {
        if (e.target === orderOverlay) closeOrderModal();
    });
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
        if (e.key === 'Escape') {
            if (orderOverlay.classList.contains('active')) closeOrderModal();
            else if (cartSidebar.classList.contains('open')) closeCart();
        }
    });

    // ── INIT ──
    loadCart();
    renderProducts();
</script>
</body>
</html>
