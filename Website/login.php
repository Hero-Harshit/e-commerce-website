<?php
session_start();

// ── DEMO USERS (replace with a database in production) ──
$users = [
    'demo@pawfeast.com'  => ['password' => 'paws123',  'name' => 'Pet Lover'],
    'admin@pawfeast.com' => ['password' => 'admin123', 'name' => 'Admin'],
];

// If already logged in, redirect
if (!empty($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit;
}

// Generate a fresh math captcha if one doesn't exist
if (empty($_SESSION['captcha_answer'])) {
    $a = rand(1, 9);
    $b = rand(1, 9);
    $_SESSION['captcha_answer'] = $a + $b;
    $_SESSION['captcha_question'] = "$a + $b";
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password =      $_POST['password'] ?? '';
    $captcha  = trim($_POST['captcha']  ?? '');

    if ((int)$captcha !== (int)$_SESSION['captcha_answer']) {
        $error = '🐾 Oops! The captcha answer is wrong. Try again.';
        // Regenerate captcha
        $a = rand(1, 9); $b = rand(1, 9);
        $_SESSION['captcha_answer']   = $a + $b;
        $_SESSION['captcha_question'] = "$a + $b";
    } elseif (!isset($users[$email]) || $users[$email]['password'] !== $password) {
        $error = '🔒 Wrong email or password. Please check and retry.';
        // Regenerate captcha
        $a = rand(1, 9); $b = rand(1, 9);
        $_SESSION['captcha_answer']   = $a + $b;
        $_SESSION['captcha_question'] = "$a + $b";
    } else {
        $_SESSION['logged_in']   = true;
        $_SESSION['user_name']   = $users[$email]['name'];
        $_SESSION['user_email']  = $email;
        unset($_SESSION['captcha_answer'], $_SESSION['captcha_question']);
        header('Location: index.php');
        exit;
    }
}

$captcha_q = $_SESSION['captcha_question'] ?? '? + ?';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PawFeast | Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,300&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --moss:       #3d5a3e;
            --moss-lt:    #537055;
            --sage:       #a8bf9a;
            --cream:      #f7f2e8;
            --parchment:  #ede5d4;
            --amber:      #c97a2f;
            --amber-lt:   #e8a05a;
            --bark:       #2e2016;
            --warm-mid:   #7a5c3a;
            --border:     #e4d9c6;
            --card-bg:    #fffdf8;
            --error-bg:   #fff3f0;
            --error-text: #c0392b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* ─── LEFT PANEL ─── */
        .left-panel {
            background: linear-gradient(160deg, var(--moss) 0%, #4e7550 55%, #2c4a2e 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 60px 64px;
            position: relative;
            overflow: hidden;
        }

        /* decorative paw prints */
        .left-panel::before {
            content: '🐾';
            position: absolute;
            font-size: 11rem;
            opacity: 0.06;
            top: -30px; right: -30px;
            transform: rotate(20deg);
        }
        .left-panel::after {
            content: '🐾';
            position: absolute;
            font-size: 7rem;
            opacity: 0.05;
            bottom: 40px; left: -10px;
            transform: rotate(-15deg);
        }

        .left-blob {
            position: absolute;
            width: 380px; height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            bottom: -100px; right: -100px;
        }

        .brand-mark {
            margin-bottom: 40px;
        }
        .brand-mark .logo-text {
            font-family: 'Fraunces', serif;
            font-size: 2.6rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -1px;
            line-height: 1;
        }
        .brand-mark .logo-text span { color: var(--amber-lt); }
        .brand-mark .tagline {
            font-size: 0.72rem;
            color: var(--sage);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 6px;
        }

        .left-headline {
            font-family: 'Fraunces', serif;
            font-size: 2.1rem;
            font-weight: 300;
            font-style: italic;
            color: #fff;
            line-height: 1.3;
            margin-bottom: 18px;
            max-width: 360px;
        }
        .left-headline strong {
            font-weight: 600;
            font-style: normal;
            color: var(--amber-lt);
        }

        .left-sub {
            color: var(--sage);
            font-size: 0.95rem;
            line-height: 1.6;
            max-width: 320px;
            margin-bottom: 44px;
        }

        .pet-icons {
            display: flex;
            gap: 18px;
        }
        .pet-icon-chip {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(6px);
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 0.85rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 7px;
            font-weight: 500;
        }

        /* ─── RIGHT PANEL ─── */
        .right-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 28px;
            background: var(--cream);
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--card-bg);
            border-radius: 28px;
            padding: 48px 44px;
            border: 1.5px solid var(--border);
            box-shadow: 0 24px 60px -10px rgba(61,90,62,0.12), 0 8px 20px rgba(0,0,0,0.06);
            animation: cardIn 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-eyebrow {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--amber);
            margin-bottom: 8px;
        }
        .card-title {
            font-family: 'Fraunces', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--bark);
            margin-bottom: 6px;
            line-height: 1.2;
        }
        .card-sub {
            font-size: 0.9rem;
            color: var(--warm-mid);
            margin-bottom: 34px;
        }

        /* ─── ERROR BOX ─── */
        .error-box {
            background: var(--error-bg);
            border: 1.5px solid #f5c6bc;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 22px;
            font-size: 0.88rem;
            color: var(--error-text);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: shake 0.35s ease;
        }
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%      { transform: translateX(-6px); }
            40%      { transform: translateX(6px); }
            60%      { transform: translateX(-4px); }
            80%      { transform: translateX(4px); }
        }

        /* ─── FORM FIELDS ─── */
        .field {
            margin-bottom: 20px;
        }
        .field label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--bark);
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }
        .input-wrap {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            pointer-events: none;
            opacity: 0.55;
        }
        .field input[type="email"],
        .field input[type="password"],
        .field input[type="text"] {
            width: 100%;
            padding: 13px 16px 13px 44px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            background: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            color: var(--bark);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .field input:focus {
            border-color: var(--moss-lt);
            box-shadow: 0 0 0 3px rgba(83,112,85,0.15);
        }
        .field input::placeholder { color: #c4b49a; }

        /* ─── CAPTCHA ROW ─── */
        .captcha-row {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 20px;
        }
        .captcha-box {
            background: var(--moss);
            border-radius: 14px;
            padding: 13px 20px;
            font-family: 'Fraunces', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--amber-lt);
            letter-spacing: 3px;
            white-space: nowrap;
            flex-shrink: 0;
            text-align: center;
            min-width: 120px;
            user-select: none;
        }
        .captcha-row .field { flex: 1; margin-bottom: 0; }
        .captcha-row .field label { display: block; margin-bottom: 8px; }

        /* ─── SUBMIT BTN ─── */
        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--moss) 0%, var(--moss-lt) 100%);
            border: none;
            border-radius: 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            letter-spacing: 0.3px;
            margin-top: 6px;
            transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
            box-shadow: 0 6px 20px rgba(61,90,62,0.3);
            position: relative;
            overflow: hidden;
        }
        .submit-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(255,255,255,0.08), transparent);
        }
        .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(61,90,62,0.4); }
        .submit-btn:active { transform: scale(0.98); }

        /* ─── DEMO HINT ─── */
        .demo-hint {
            margin-top: 22px;
            padding: 14px 16px;
            background: var(--parchment);
            border-radius: 12px;
            font-size: 0.8rem;
            color: var(--warm-mid);
            line-height: 1.7;
            border: 1px dashed var(--border);
        }
        .demo-hint strong { color: var(--bark); }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 780px) {
            body { grid-template-columns: 1fr; }
            .left-panel { display: none; }
            .right-panel { padding: 30px 16px; }
            .login-card { padding: 36px 24px; }
        }
    </style>
</head>
<body>

<!-- LEFT PANEL -->
<div class="left-panel">
    <div class="left-blob"></div>
    <div class="brand-mark">
        <div class="logo-text">🐾 Paw<span>Feast</span></div>
        <div class="tagline">Premium Pet Nutrition</div>
    </div>
    <h2 class="left-headline">
        Every pet deserves<br>
        <strong>real food,</strong><br>
        real love.
    </h2>
    <p class="left-sub">
        Curated premium nutrition for dogs, cats &amp; fish —
        real ingredients, zero compromise, delivered with care.
    </p>
    <div class="pet-icons">
        <div class="pet-icon-chip">🐶 Dogs</div>
        <div class="pet-icon-chip">🐱 Cats</div>
        <div class="pet-icon-chip">🐠 Fish</div>
    </div>
</div>

<!-- RIGHT PANEL -->
<div class="right-panel">
    <div class="login-card">
        <div class="card-eyebrow">Welcome Back</div>
        <h1 class="card-title">Login to your<br>account</h1>
        <p class="card-sub">Discover premium nutrition for your beloved pets</p>

        <?php if ($error): ?>
        <div class="error-box"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">

            <div class="field">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <span class="input-icon">📧</span>
                    <input type="email" id="email" name="email"
                           placeholder="you@example.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required autocomplete="email">
                </div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••"
                           required autocomplete="current-password">
                </div>
            </div>

            <div class="captcha-row">
                <div class="captcha-box"><?= htmlspecialchars($captcha_q) ?> = ?</div>
                <div class="field">
                    <label for="captcha">Enter Captcha</label>
                    <div class="input-wrap">
                        <span class="input-icon">🧮</span>
                        <input type="text" id="captcha" name="captcha"
                               placeholder="Answer"
                               maxlength="3"
                               inputmode="numeric" required autocomplete="off">
                    </div>
                </div>
            </div>

            <button type="submit" class="submit-btn">Login to PawFeast 🐾</button>
        </form>

        <div class="demo-hint">
            <strong>🔑 Demo Credentials:</strong><br>
            Email: <strong>demo@pawfeast.com</strong> / Password: <strong>paws123</strong>
        </div>
    </div>
</div>

</body>
</html>
