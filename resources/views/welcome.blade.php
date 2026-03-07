<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DjibStay - Official Hotel Booking Platform in Djibouti</title>
    <meta name="description" content="Trouvez et réservez les meilleurs hôtels à Djibouti. Comparez les prix, lisez les avis et profitez des meilleures offres.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --blue: #003580;
            --blue-light: #0071c2;
            --blue-hover: #005fa3;
            --yellow: #febb02;
            --yellow-hover: #f5a623;
            --gray-bg: #f2f2f2;
            --card-shadow: 0 2px 12px rgba(0,0,0,0.12);
            --card-hover-shadow: 0 8px 30px rgba(0,0,0,0.18);
            --radius: 8px;
            --transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: #fff;
            color: #1a1a2e;
            line-height: 1.5;
        }

        /* ─── NAVBAR ─── */
        .navbar {
            background: var(--blue);
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            min-height: 64px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .navbar-brand {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-brand .logo-icon {
            width: 36px;
            height: 36px;
            background: transparent;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .navbar-brand .logo-icon svg { width: 24px; height: 24px; }

        .navbar-tagline {
            font-size: 10px;
            font-weight: 600;
            color: rgba(255,255,255,0.8);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        .navbar-brand .logo-text { color: #febb02; font-weight: 800; }

        .navbar-nav {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .nav-link {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 4px;
            border: 1px solid transparent;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition);
        }

        .nav-link:hover, .nav-link.active {
            border-color: rgba(255,255,255,0.5);
            color: #fff;
            background: rgba(255,255,255,0.1);
        }

        .nav-link.active { background: rgba(255,255,255,0.15); }

        .navbar-auth {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn-nav {
            padding: 8px 18px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            border: none;
        }

        .btn-partner {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255,255,255,0.6);
        }

        .btn-partner:hover { background: rgba(255,255,255,0.1); }

        .btn-signin {
            background: #0071c2;
            color: #fff;
        }

        .btn-signin:hover { background: var(--blue-hover); }

        /* ─── HERO ─── */
        .hero {
            position: relative;
            min-height: 520px;
            z-index: 30;
            background: var(--blue);
            overflow: visible;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding-bottom: 60px;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background: url('/images/hero.png') center/cover no-repeat;
            opacity: 1;
            transform: scale(1.05);
            animation: heroPan 18s ease-in-out infinite alternate;
            border-radius: 0;
        }

        @keyframes heroPan {
            from { transform: scale(1.05) translateX(0); }
            to   { transform: scale(1.05) translateX(-3%); }
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.55) 70%, rgba(0,0,0,0.7) 100%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 0 40px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(254,187,2,0.15);
            border: 1px solid rgba(254,187,2,0.4);
            color: var(--yellow);
            font-size: 12px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 18px;
            animation: fadeInDown 0.6s ease both;
        }

        .hero-title {
            font-size: 44px;
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 10px;
            max-width: 560px;
            animation: fadeInDown 0.7s ease 0.1s both;
        }

        .hero-sub {
            font-size: 16px;
            color: rgba(255,255,255,0.82);
            margin-bottom: 36px;
            max-width: 440px;
            animation: fadeInDown 0.7s ease 0.2s both;
        }

        /* ─── SEARCH BAR (style image: bordure jaune #febb02, fond blanc, bouton bleu) ─── */
        .search-bar {
            display: flex;
            align-items: stretch;
            background: #fff;
            border-radius: 12px;
            border: 3px solid #febb02;
            overflow: visible;
            box-shadow: 0 4px 24px rgba(254, 187, 2, 0.25);
            max-width: 1100px;
            animation: fadeInUp 0.7s ease 0.3s both;
            position: relative;
        }

        .search-bar:focus-within,
        .search-bar.search-bar-active {
            box-shadow: 0 6px 28px rgba(254, 187, 2, 0.35);
        }

        /* ── Champs de la barre (fond blanc, séparateur vertical) ── */
        .search-bar .sf-wrap {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 12px 16px;
            border-right: 1px solid #e5e7eb;
            cursor: pointer;
            transition: background 0.2s;
            min-width: 0;
            background: #fff;
        }

        .search-bar .sf-wrap:hover { background: #f8fafc; }
        .search-bar .sf-wrap.active { background: #fff; }
        .search-bar .sf-wrap:last-of-type { border-right: none; }

        .sf-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 4px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sf-label svg { color: #64748b; flex-shrink: 0; }

        .sf-value {
            font-size: 14px;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sf-value input {
            border: none;
            background: transparent;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #1e293b;
            outline: none;
            width: 100%;
            min-width: 0;
        }

        .sf-value input::placeholder { color: #64748b; }

        /* Clear button on destination */
        .sf-clear {
            background: #e5e7eb;
            border: none;
            border-radius: 50%;
            width: 20px; height: 20px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 13px;
            color: #555;
            flex-shrink: 0;
            transition: var(--transition);
            line-height: 1;
        }

        .sf-clear:hover { background: #d1d5db; }
        .sf-clear.show { display: flex; }

        /* Chevron for guest field */
        .sf-chevron {
            margin-left: auto;
            font-size: 10px;
            color: #64748b;
            transition: transform 0.25s;
            flex-shrink: 0;
        }

        .sf-chevron.open { transform: rotate(180deg); }

        /* ── DATE placeholder / value ── */
        .date-placeholder { color: #64748b; font-size: 14px; }
        .date-value { color: #1e293b; font-size: 14px; font-weight: 500; }

        /* ── BOUTON RECHERCHER (bleu, intégré à la barre) ── */
        .search-btn {
            background: #003580;
            color: #fff;
            border: none;
            padding: 0 28px;
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            border-radius: 0 9px 9px 0;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .search-btn:hover { background: #002a5c; }

        .search-option { padding: 0 2px; }
        .search-checkbox { display: inline-flex; align-items: center; cursor: pointer; font-size: 14px; color: rgba(255,255,255,0.95); }
        .search-checkbox span { user-select: none; }

        /* ── CALENDAR POPUP (au-dessus de tout le contenu) ── */
        .cal-popup {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            z-index: 9999;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
            padding: 0;
            width: 660px;
            overflow: hidden;
        }

        .cal-popup.open { display: block; animation: popIn 0.2s ease; }

        .cal-tabs {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            background: #fff;
        }

        .cal-tab {
            flex: 1;
            padding: 14px;
            text-align: center;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            color: #6b7280;
            border-bottom: 3px solid transparent;
            transition: var(--transition);
        }

        .cal-tab.active { color: var(--blue-light); border-bottom-color: var(--blue-light); }

        .cal-body { padding: 20px 24px; display: flex; gap: 28px; }

        .cal-quick-days {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 12px 24px 16px;
            border-top: 1px solid #f0f0f0;
        }

        .cal-quick-days button {
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 500;
            color: var(--blue-light);
            background: #eff6ff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            transition: var(--transition);
        }

        .cal-quick-days button:hover { background: #dbeafe; }

        .cal-month { flex: 1; }

        .cal-month-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .cal-month-title { font-size: 15px; font-weight: 700; color: #1a1a2e; }

        .cal-nav {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #6b7280;
            padding: 4px 8px;
            border-radius: 4px;
            transition: var(--transition);
        }

        .cal-nav:hover { background: #f3f4f6; color: #1a1a2e; }
        .cal-nav:disabled { opacity: 0; pointer-events: none; }

        .cal-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            color: #9ca3af;
            padding-bottom: 6px;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 6px;
        }

        .cal-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }

        .cal-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            position: relative;
        }

        .cal-day:hover:not(.cal-day-empty):not(.cal-day-past) { background: #dbeafe; color: var(--blue); }
        .cal-day.cal-day-empty { cursor: default; }
        .cal-day.cal-day-past { color: #d1d5db; cursor: default; pointer-events: none; }
        .cal-day.cal-day-start { background: var(--blue); color: #fff; border-radius: 4px 0 0 4px; }
        .cal-day.cal-day-end   { background: var(--blue); color: #fff; border-radius: 0 4px 4px 0; }
        .cal-day.cal-day-range { background: #dbeafe; color: var(--blue); border-radius: 0; }
        .cal-day.cal-day-start.cal-day-end { border-radius: 4px; }

        .cal-footer {
            border-top: 1px solid #f0f0f0;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cal-footer-hint { font-size: 12px; color: #9ca3af; }

        .cal-clear {
            background: none;
            border: none;
            font-size: 13px;
            font-weight: 600;
            color: var(--blue-light);
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 4px;
            transition: var(--transition);
        }

        .cal-clear:hover { background: #eff6ff; }

        @keyframes popIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── GUEST POPUP ── */
        .guest-popup {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            z-index: 10000;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px 24px;
            min-width: 320px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
        }

        .guest-popup.open { display: block; animation: popIn 0.2s ease; }

        .gp-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .gp-row:last-of-type { border-bottom: none; }

        .gp-label { font-size: 15px; font-weight: 600; color: #1a1a2e; }
        .gp-sub   { font-size: 12px; color: #9ca3af; margin-top: 2px; }

        .gp-ctrl { display: flex; align-items: center; gap: 12px; }

        .gp-btn {
            width: 32px; height: 32px;
            border: 1.5px solid #d1d5db;
            border-radius: 50%;
            background: #fff;
            color: #374151;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: var(--transition);
            font-weight: 400;
        }

        .gp-btn:not(:disabled):hover { border-color: var(--blue-light); color: var(--blue-light); }
        .gp-btn:disabled { opacity: 0.3; cursor: not-allowed; }

        .gp-num { width: 32px; text-align: center; font-weight: 700; font-size: 16px; }

        .gp-done {
            width: 100%;
            margin-top: 16px;
            padding: 11px;
            background: var(--blue);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
        }

        .gp-done:hover { background: var(--blue-light); }

        /* ─── PROMO STRIP ─── */
        .promo-strip {
            background: #ebf3ff;
            padding: 14px 40px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: var(--blue);
            border-bottom: 1px solid #d0e4ff;
        }

        .promo-strip .genius-badge {
            background: var(--blue);
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 4px;
            letter-spacing: 0.5px;
        }

        /* ─── SECTIONS ─── */
        .section { padding: 48px 40px; max-width: 1200px; margin: 0 auto; }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 800;
            color: #1a1a2e;
        }

        .section-sub {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }

        .view-all {
            color: var(--blue-light);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: var(--transition);
        }

        .view-all:hover { color: var(--blue); text-decoration: underline; }

        /* ─── OFFERS CARDS ─── */
        .offers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .offer-card {
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        .offer-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--card-hover-shadow);
        }

        .offer-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }

        .offer-body { padding: 18px; flex: 1; display: flex; flex-direction: column; }

        .offer-tag {
            display: inline-block;
            background: #fef9e7;
            border: 1px solid #fde68a;
            color: #b45309;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .offer-title {
            font-size: 17px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        .offer-desc {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.6;
            flex: 1;
            margin-bottom: 16px;
        }

        .offer-btn {
            display: inline-block;
            background: var(--blue-light);
            color: #fff;
            padding: 9px 20px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            align-self: flex-start;
        }

        .offer-btn:hover { background: var(--blue-hover); transform: translateY(-1px); }

        /* ─── HOTEL CARDS ─── */
        .hotel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
            gap: 20px;
        }

        .hotel-card {
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        .hotel-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--card-hover-shadow);
        }

        .hotel-img-wrap {
            position: relative;
            height: 200px;
            overflow: hidden;
            background: #e0e7ef;
        }

        .hotel-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .hotel-card:hover .hotel-img { transform: scale(1.06); }

        .hotel-wishlist {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 32px; height: 32px;
            background: rgba(255,255,255,0.9);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            backdrop-filter: blur(4px);
        }

        .hotel-wishlist:hover { background: #fff; transform: scale(1.1); }

        .hotel-score-badge {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: var(--blue);
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            padding: 5px 9px;
            border-radius: 6px 6px 6px 0;
        }

        .hotel-body { padding: 14px 16px; flex: 1; display: flex; flex-direction: column; }

        .hotel-location {
            font-size: 11px;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .hotel-name {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .hotel-stars { color: var(--yellow); font-size: 12px; margin-bottom: 8px; }

        .hotel-amenities {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .amenity-tag {
            background: #f3f4f6;
            color: #374151;
            font-size: 11px;
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 4px;
        }

        .hotel-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #f0f0f0;
        }

        .hotel-price-area { display: flex; flex-direction: column; }

        .price-label { font-size: 11px; color: #9ca3af; }

        .price-val {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a2e;
        }

        .price-night { font-size: 11px; color: #9ca3af; font-weight: 400; }

        .book-btn {
            background: var(--blue-light);
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .book-btn:hover { background: var(--blue-hover); transform: translateY(-1px); }

        /* ─── EXPLORE SECTION ─── */
        .explore-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
        }

        .explore-card {
            border-radius: var(--radius);
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: var(--transition);
            height: 160px;
            display: block;
            text-decoration: none;
        }

        .explore-card:hover { transform: translateY(-5px); box-shadow: var(--card-hover-shadow); }

        .explore-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .explore-card:hover .explore-img { transform: scale(1.08); }

        .explore-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, transparent 55%);
        }

        .explore-label {
            position: absolute;
            bottom: 12px;
            left: 12px;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
        }

        .explore-count {
            position: absolute;
            bottom: 30px;
            left: 12px;
            color: rgba(255,255,255,0.75);
            font-size: 11px;
        }

        /* ─── STATS STRIP ─── */
        .stats-strip {
            background: linear-gradient(135deg, #003580 0%, #0071c2 100%);
            padding: 40px;
        }

        .stats-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            text-align: center;
        }

        .stat-item { color: #fff; }

        .stat-num {
            font-size: 40px;
            font-weight: 900;
            display: block;
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label { font-size: 14px; color: rgba(255,255,255,0.75); }

        /* ─── FILTER BAR ─── */
        .filter-bar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            border-top: 1px solid #e5e7eb;
            padding: 16px 40px;
        }

        .filter-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-label { font-size: 13px; font-weight: 700; color: #1a1a2e; margin-right: 4px; }

        .filter-input {
            padding: 8px 14px;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: var(--transition);
            min-width: 120px;
        }

        .filter-input:focus { border-color: var(--blue-light); box-shadow: 0 0 0 3px rgba(0,113,194,0.1); }

        .filter-btn {
            background: var(--blue-light);
            color: #fff;
            border: none;
            padding: 9px 22px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: var(--transition);
            margin-left: auto;
        }

        .filter-btn:hover { background: var(--blue-hover); }

        /* ─── NEWSLETTER (dark blue, rounded, yellow CTA) ─── */
        .newsletter-wrap {
            padding: 48px 40px 56px;
            background: #f8fafc;
        }

        .newsletter {
            max-width: 720px;
            margin: 0 auto;
            background: var(--blue);
            border-radius: 24px;
            padding: 48px 56px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 2px solid #fff;
            box-shadow: 0 4px 24px rgba(0,53,128,0.15);
        }

        .newsletter::before,
        .newsletter::after {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }

        .newsletter::before {
            top: -60px;
            left: -60px;
        }

        .newsletter::after {
            bottom: -80px;
            right: -80px;
            width: 220px;
            height: 220px;
        }

        .newsletter-inner { position: relative; z-index: 1; }

        .newsletter h2 {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 10px;
            letter-spacing: -0.02em;
        }

        .newsletter .newsletter-sub {
            font-size: 16px;
            color: rgba(255,255,255,0.9);
            margin-bottom: 28px;
        }

        .newsletter-form {
            display: flex;
            gap: 0;
            border-radius: 12px;
            overflow: hidden;
            max-width: 480px;
            margin: 0 auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .newsletter-input {
            flex: 1;
            padding: 16px 20px;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            outline: none;
            background: #fff;
            color: #1a1a2e;
        }

        .newsletter-input::placeholder { color: #94a3b8; }

        .newsletter-btn {
            background: var(--yellow);
            color: #1a1a2e;
            border: none;
            padding: 16px 32px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
        }

        .newsletter-btn:hover { background: #f5a623; color: #0f172a; }

        /* ─── FOOTER ─── */
        footer {
            background: #013;
            color: #9ca3af;
            padding: 56px 40px 32px;
        }

        .footer-grid {
            max-width: 1200px;
            margin: 0 auto 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 32px;
        }

        .footer-col h4 {
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 16px;
        }

        .footer-col ul { list-style: none; }

        .footer-col li { margin-bottom: 10px; }

        .footer-col a {
            color: #9ca3af;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }

        .footer-col a:hover { color: #fff; }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 28px;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .footer-bottom p { font-size: 12px; color: #6b7280; }

        .footer-logo { font-size: 18px; font-weight: 800; }
        .footer-logo .logo-text { color: #febb02; }

        /* ─── ANIMATIONS ─── */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ─── EMPTY STATE ─── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-state-icon { font-size: 56px; margin-bottom: 12px; }
        .empty-state p { font-size: 16px; color: #4b5563; }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 900px) {
            .navbar { padding: 0 20px; }
            .navbar-nav { display: none; }
            .hero { min-height: 440px; }
            .hero-title { font-size: 30px; }
            .search-bar { flex-direction: column; border-radius: 12px; overflow: visible; }
            .search-bar .sf-wrap { border-right: none; border-bottom: 1px solid #e5e7eb; }
            .search-bar .sf-wrap:last-of-type { border-bottom: none; }
            .search-btn { border-radius: 0 0 9px 9px; padding: 14px 20px; }
            .section { padding: 32px 20px; }
            .filter-bar { padding: 12px 20px; }
            .stats-inner { grid-template-columns: 1fr; gap: 16px; }
            .newsletter { padding: 40px 20px; }
            footer { padding: 40px 20px 28px; }
        }
    </style>
</head>
<body>

<!-- ═══════════════════════════════════ NAVBAR ═════════════════════════════════════ -->
<nav class="navbar">
    <div class="navbar-left">
        <a href="{{ url('/') }}" class="navbar-brand">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#febb02" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div>
                <span class="logo-text">DjibStay</span>
                <div class="navbar-tagline">PLATEFORME OFFICIELLE DE RÉSERVATION D'HÔTELS À DJIBOUTI</div>
            </div>
        </a>
        <div class="navbar-nav">
            <a href="{{ route('hotels.index') }}" class="nav-link {{ request()->routeIs('hotels.*') ? 'active' : '' }}">Hôtels</a>
            <a href="{{ url('/') }}#explore" class="nav-link">Villes</a>
            <a href="{{ url('/') }}#deals" class="nav-link">Offres</a>
            <a href="#" class="nav-link">À propos</a>
            <a href="#" class="nav-link">Contact</a>
        </div>
    </div>
    <div class="navbar-auth">
        @auth
            <a href="{{ route('admin.dashboard') }}" class="btn-nav btn-partner">Tableau de bord</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="btn-nav btn-signin">Déconnexion</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-nav btn-partner">Espace partenaire</a>
            <a href="{{ route('login') }}" class="btn-nav btn-signin">Connexion</a>
        @endauth
    </div>
</nav>

<!-- ═══════════════════════════════════ HERO ══════════════════════════════════════ -->
<div class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>

    <div class="hero-content">
        <h1 class="hero-title">Trouvez votre séjour idéal à Djibouti</h1>
        <p class="hero-sub">Comparez les hôtels, trouvez les meilleurs prix et réservez en toute confiance à travers le Golfe de Tadjoura.</p>

        <form method="GET" action="{{ url('/') }}" id="hero-search" class="search-bar">

            <!-- ── DESTINATION ── -->
            <div class="sf-wrap" id="sf-dest" style="flex:2;" onclick="document.getElementById('dest-input').focus()">
                <div class="sf-label">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Où allez-vous ?
                </div>
                <div class="sf-value">
                    <input type="text" id="dest-input" name="city"
                           placeholder="Où allez-vous ?"
                           value="{{ request('city', '') }}"
                           autocomplete="off">
                    <button type="button" class="sf-clear" id="dest-clear" title="Effacer">✕</button>
                </div>
            </div>

            <!-- ── DATES ── -->
            <div class="sf-wrap" id="sf-dates" style="flex:2; position:relative;" onclick="toggleCalendar()">
                <div class="sf-label">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Dates
                </div>
                <div class="sf-value">
                    <span id="date-display" class="date-placeholder">Date d'arrivée — Date de départ</span>
                    <input type="hidden" name="check_in"  id="checkin-val" value="{{ request('check_in') ?? request('checkin') ?? '' }}">
                    <input type="hidden" name="check_out" id="checkout-val" value="{{ request('check_out') ?? request('checkout') ?? '' }}">
                </div>

                <!-- Calendar popup -->
                <div class="cal-popup" id="cal-popup" onclick="event.stopPropagation()">
                    <div class="cal-tabs">
                        <div class="cal-tab active" id="tab-cal" onclick="event.stopPropagation(); switchTab('cal')">Calendrier</div>
                        <div class="cal-tab" id="tab-flex" onclick="event.stopPropagation(); switchTab('flex')">Dates flexibles</div>
                    </div>
                    <div class="cal-body" id="cal-body">
                        <div class="cal-month" id="cal-left"></div>
                        <div class="cal-month" id="cal-right"></div>
                    </div>
                    <div class="cal-quick-days">
                        <button type="button" onclick="quickStay(1)">+ 1 jour</button>
                        <button type="button" onclick="quickStay(2)">+ 2 jours</button>
                        <button type="button" onclick="quickStay(3)">+ 3 jours</button>
                        <button type="button" onclick="quickStay(7)">+ 1 semaine</button>
                    </div>
                    <div class="cal-footer">
                        <span class="cal-footer-hint" id="cal-hint">Sélectionnez une date d'arrivée</span>
                        <button type="button" class="cal-clear" onclick="clearDates()">Effacer les dates</button>
                    </div>
                </div>
            </div>

            <!-- ── VOYAGEURS & CHAMBRES ── -->
            <div class="sf-wrap" id="sf-guests" style="flex:1.2; position:relative;" onclick="toggleGuests(event)">
                <div class="sf-label">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Voyageurs
                </div>
                <div class="sf-value">
                    <span id="guest-summary">2 adultes · 0 enfant · 1 chambre</span>
                    <span class="sf-chevron" id="sf-chevron">&#9660;</span>
                </div>

                <!-- Guest popup -->
                <div class="guest-popup" id="guest-popup" onclick="event.stopPropagation()">
                    <div class="gp-row">
                        <div><div class="gp-label">Adultes</div><div class="gp-sub">18 ans et +</div></div>
                        <div class="gp-ctrl">
                            <button type="button" class="gp-btn" id="btn-adults-dec" onclick="adjustCount('adults',-1)">−</button>
                            <span class="gp-num" id="adults-count">2</span>
                            <button type="button" class="gp-btn" onclick="adjustCount('adults',1)">+</button>
                        </div>
                    </div>
                    <div class="gp-row">
                        <div><div class="gp-label">Enfants</div><div class="gp-sub">0–17 ans</div></div>
                        <div class="gp-ctrl">
                            <button type="button" class="gp-btn" id="btn-children-dec" onclick="adjustCount('children',-1)">−</button>
                            <span class="gp-num" id="children-count">0</span>
                            <button type="button" class="gp-btn" onclick="adjustCount('children',1)">+</button>
                        </div>
                    </div>
                    <div class="gp-row">
                        <div><div class="gp-label">Chambres</div></div>
                        <div class="gp-ctrl">
                            <button type="button" class="gp-btn" id="btn-rooms-dec" onclick="adjustCount('rooms',-1)">−</button>
                            <span class="gp-num" id="rooms-count">1</span>
                            <button type="button" class="gp-btn" onclick="adjustCount('rooms',1)">+</button>
                        </div>
                    </div>
                    <button type="button" class="gp-done" onclick="closeGuests()">Done</button>
                </div>
            </div>

            <!-- hidden inputs for guest counts (synced from JS) -->
            <input type="hidden" name="adults" id="input-adults" value="{{ request('adults', 2) }}">
            <input type="hidden" name="children" id="input-children" value="{{ request('children', 0) }}">
            <input type="hidden" name="rooms" id="input-rooms" value="{{ request('rooms', 1) }}">

            <button type="submit" class="search-btn">Rechercher</button>
        </form>
        <div class="search-option" style="margin-top: 12px; padding-left: 4px;">
            <label class="search-checkbox">
                <input type="checkbox" name="entire_property" value="1" style="margin-right: 10px; cursor: pointer;">
                <span>Je recherche un logement entier</span>
            </label>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════ SPECIAL DEALS (cliquable, au-dessus du hero) ── -->
<div id="deals" style="background:#f9fafb; padding: 48px 40px; position: relative; z-index: 5; pointer-events: auto;">
    <div class="section fade-in" style="padding:0;">
        <div class="section-head">
            <div class="section-title">Offres Spéciales</div>
            <a href="{{ route('hotels.index') }}" class="view-all">Voir toutes les offres &rsaquo;</a>
        </div>
        <div class="offers-grid" style="grid-template-columns: repeat(2, 1fr); gap: 24px;">
            <a href="{{ route('hotels.index') }}" class="offer-card offer-card-link" style="flex-direction: row; height: 220px; text-decoration: none; color: inherit;">
                <div style="width: 50%; height: 100%; min-height: 220px; overflow: hidden; pointer-events: none;">
                    <img src="/images/waafi.jpg" alt="Stay Longer Save More" class="offer-img" style="height: 100%; width: 100%; object-fit: cover; pointer-events: none;" onerror="this.style.background='#e0e7ef'">
                </div>
                <div class="offer-body" style="width: 50%; padding: 24px; display: flex; flex-direction: column; justify-content: center; pointer-events: none;">
                    <div class="offer-title" style="font-size: 20px; margin-bottom: 8px;">Séjournez plus, économisez plus</div>
                    <div class="offer-desc" style="margin-bottom: 16px;">Jusqu'à 20% de réduction pour les séjours de plus de 5 nuits.</div>
                    <span class="offer-btn">Réserver</span>
                </div>
            </a>
            <a href="{{ route('hotels.index') }}" class="offer-card offer-card-link" style="flex-direction: row; height: 220px; text-decoration: none; color: inherit;">
                <div style="width: 50%; height: 100%; min-height: 220px; overflow: hidden; pointer-events: none;">
                    <img src="{{ asset('images/best western.jpeg') }}" alt="Last Minute Escape" class="offer-img" style="height: 100%; width: 100%; object-fit: cover; pointer-events: none;" onerror="this.style.background='#e0e7ef'">
                </div>
                <div class="offer-body" style="width: 50%; padding: 24px; display: flex; flex-direction: column; justify-content: center; pointer-events: none;">
                    <div class="offer-title" style="font-size: 20px; margin-bottom: 8px;">Escapade de dernière minute</div>
                    <div class="offer-desc" style="margin-bottom: 16px;">Offres exclusives pour les réservations de ce soir à Djibouti.</div>
                    <span class="offer-btn">Découvrir</span>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════ STATS STRIP ═══════════════════════════════ -->
<div class="stats-strip fade-in">
    <div class="stats-inner">
        <div class="stat-item">
            <span class="stat-num" id="stat-hotels">{{ $stats['total_hotels'] }}</span>
            <span class="stat-label">Hébergements disponibles</span>
        </div>
        <div class="stat-item">
            <span class="stat-num" id="stat-bookings">{{ $stats['total_bookings'] }}</span>
            <span class="stat-label">Réservations effectuées</span>
        </div>
        <div class="stat-item">
            <span class="stat-num">{{ number_format($stats['avg_rating'], 1) }}★</span>
            <span class="stat-label">Note moyenne</span>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════ FILTER BAR ════════════════════════════════ -->
<div class="filter-bar">
    <form class="filter-inner" method="GET" action="{{ url('/') }}">
        <span class="filter-label">Filtrer :</span>
        <input class="filter-input" type="text" name="city" placeholder="Ville (Djibouti, Obock…)" value="{{ request('city') }}">
        <input class="filter-input" type="number" name="min_price" placeholder="Prix min" value="{{ request('min_price') }}" style="max-width:110px">
        <input class="filter-input" type="number" name="max_price" placeholder="Prix max" value="{{ request('max_price') }}" style="max-width:110px">
        <select class="filter-input" name="min_rating" style="max-width:130px">
            <option value="">Toutes les notes</option>
            <option value="3" {{ request('min_rating')=='3' ? 'selected':'' }}>3★ et plus</option>
            <option value="4" {{ request('min_rating')=='4' ? 'selected':'' }}>4★ et plus</option>
            <option value="4.5" {{ request('min_rating')=='4.5' ? 'selected':'' }}>4.5★ et plus</option>
        </select>
        <select class="filter-input" name="sort" style="max-width:160px">
            <option value="rating" {{ request('sort')=='rating' ? 'selected':'' }}>Best Rating</option>
            <option value="price_asc" {{ request('sort')=='price_asc' ? 'selected':'' }}>Price: Low → High</option>
            <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected':'' }}>Price: High → Low</option>
        </select>
        <button type="submit" class="filter-btn">Appliquer</button>
    </form>
</div>

<!-- ═══════════════════════════════════ FEATURED HOTELS ══════════════════════════ -->
<div class="section fade-in">
    <div class="section-head">
        <div class="section-title">Hôtels en vedette</div>
        <a href="{{ url('/') }}" class="view-all">Voir tous les hébergements →</a>
    </div>

    @if($hotels->count() > 0)
        @php
            $imgFallbackList = $hotelImagesFallback ?? ['ayla.jpg', 'kempinski.jpeg', 'sheraton.jpeg', 'escale.jpg', 'waafi.jpg', 'gadileh.jpg', 'hotel europe.jpg', 'best western.jpeg','acacias.jpg'];
        @endphp
        <div class="hotel-grid">
            @foreach($hotels as $hotel)
                @php
                    $nomLower = strtolower($hotel->nom);
                    $imgFile = null;
                    foreach ($hotelImageMap ?? [] as $key => $file) {
                        if (str_contains($nomLower, $key)) { $imgFile = $file; break; }
                    }
                    if ($imgFile === null) {
                        $idx = abs((int) $hotel->id) % count($imgFallbackList);
                        $imgFile = $imgFallbackList[$idx];
                    }
                @endphp
                <a href="{{ route('hotels.show', ['hotel' => $hotel, 'check_in' => request('check_in'), 'check_out' => request('check_out')]) }}" class="hotel-card">
                    <div class="hotel-img-wrap">
                        <img src="{{ asset('images/' . $imgFile) }}"
                             alt="{{ $hotel->nom }}"
                             class="hotel-img"
                             onerror="this.src='{{ asset('images/ayla.jpg') }}'; this.onerror=null;">
                        <button class="hotel-wishlist" type="button" onclick="event.preventDefault(); this.textContent = this.textContent === '🤍' ? '❤️' : '🤍'">🤍</button>
                        <div class="hotel-score-badge">{{ number_format($hotel->avis_avg_note ?? 4.5, 1) }}</div>
                    </div>
                    <div class="hotel-body">
                        <div class="hotel-location">{{ $hotel->ville ?? 'Djibouti City' }}</div>
                        <div class="hotel-name">{{ $hotel->nom }}</div>
                        <div class="hotel-reviews" style="font-size: 13px; color: #6b7280; margin-bottom: 10px;">
                            @php
                                $rating = $hotel->avis_avg_note ?? 4.5;
                                $reviewsCount = $hotel->avis->count() ?: rand(200, 1200);
                                $label = $rating >= 8.5 ? 'Fabuleux' : ($rating >= 8 ? 'Superbe' : ($rating >= 7.5 ? 'Très bien' : 'Bien'));
                            @endphp
                            {{ $label }} - {{ number_format($reviewsCount) }} avis
                        </div>
                        <div class="hotel-footer">
                            <div class="hotel-price-area">
                                <span class="price-label">À partir de</span>
                                <span class="price-val">{{ number_format((int) ($hotel->typesChambre->min('prix_par_nuit') ?? 100), 0, ',', ' ') }} DJF</span>
                                <span class="price-night">/ nuit</span>
                            </div>
                            <span class="book-btn">Détails</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">🏨</div>
            <p>Aucun hôtel trouvé pour votre recherche. Essayez d'autres filtres.</p>
        </div>
    @endif

    <div style="margin-top: 28px; text-align: center;">
        {{ $hotels->appends(request()->query())->links() }}
    </div>
</div>

<!-- ═══════════════════════════════════ EXPLORE BY CITY ═══════════════════════════ -->
<div id="explore" style="background:#f9fafb; padding: 48px 40px;">
    <div class="section fade-in" style="padding:0;">
        <div class="section-head">
            <div class="section-title">Explorer Djibouti par ville</div>
        </div>
        <div class="explore-grid" style="grid-template-columns: repeat(5, 1fr);">
            <a href="{{ url('/') }}?city=Djibouti City" class="explore-card">
                <img src="/images/kempinski.jpeg" alt="Djibouti City" class="explore-img" onerror="this.style.background='#bfdbfe'">
                <div class="explore-overlay"></div>
                <div class="explore-count">124 hébergements</div>
                <div class="explore-label">Djibouti Ville</div>
            </a>
            <a href="{{ url('/') }}?city=Tadjoura" class="explore-card">
                <img src="/images/escale.jpg" alt="Tadjoura" class="explore-img" onerror="this.style.background='#bfdbfe'">
                <div class="explore-overlay"></div>
                <div class="explore-count">18 hébergements</div>
                <div class="explore-label">Tadjoura</div>
            </a>
            <a href="{{ url('/') }}?city=Ali Sabieh" class="explore-card">
                <img src="/images/sheraton.jpeg" alt="Ali Sabieh" class="explore-img" onerror="this.style.background='#bfdbfe'">
                <div class="explore-overlay"></div>
                <div class="explore-count">12 hébergements</div>
                <div class="explore-label">Ali Sabieh</div>
            </a>
            <a href="{{ url('/') }}?city=Obock" class="explore-card">
                <img src="/images/gadileh.jpg" alt="Obock" class="explore-img" onerror="this.style.background='#bfdbfe'">
                <div class="explore-overlay"></div>
                <div class="explore-count">8 hébergements</div>
                <div class="explore-label">Obock</div>
            </a>
            <a href="{{ url('/') }}?city=Arta" class="explore-card">
                <img src="/images/ayla.jpg" alt="Arta" class="explore-img" onerror="this.style.background='#bfdbfe'">
                <div class="explore-overlay"></div>
                <div class="explore-count">15 hébergements</div>
                <div class="explore-label">Arta</div>
            </a>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════ NEWSLETTER ═══════════════════════════════ -->
<div class="newsletter-wrap fade-in">
    <div class="newsletter">
        <div class="newsletter-inner">
            <h2>Gagnez du temps, économisez !</h2>
            <p class="newsletter-sub">Inscrivez-vous et recevez nos meilleures offres</p>
            <form class="newsletter-form" action="#" method="post" onsubmit="event.preventDefault(); return false;">
                <input type="email" class="newsletter-input" name="email" placeholder="Votre adresse email" required>
                <button type="submit" class="newsletter-btn">S'inscrire</button>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════ FOOTER ═══════════════════════════════════ -->
<footer>
    <div class="footer-grid">
        <div class="footer-col">
            <h4>Assistance</h4>
            <ul>
                <li><a href="#">Service client</a></li>
                <li><a href="#">Centre de sécurité</a></li>
                <li><a href="#">Aide à la réservation</a></li>
                <li><a href="#">Nous contacter</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Découvrir</h4>
            <ul>
                <li><a href="#">Toutes les destinations</a></li>
                <li><a href="#">Offres saisonnières</a></li>
                <li><a href="#">Location de voitures</a></li>
                <li><a href="#">Recherche de vols</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Conditions &amp; Politique</h4>
            <ul>
                <li><a href="#">Politique de confidentialité</a></li>
                <li><a href="#">Conditions d'utilisation</a></li>
                <li><a href="#">Politique des cookies</a></li>
                <li><a href="#">Programme partenaire</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>À propos de DjibStay</h4>
            <ul>
                <li><a href="#">Notre histoire</a></li>
                <li><a href="#">Carrières</a></li>
                <li><a href="#">Office du tourisme</a></li>
                <li><a href="#">Application mobile</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-logo"><span class="logo-text">DjibStay</span></div>
        <p>© {{ date('Y') }} DjibStay. Tous droits réservés.</p>
        <div style="display: flex; align-items: center; gap: 16px;">
            <a href="#" aria-label="Facebook" style="color: #9ca3af;">📘</a>
            <a href="#" aria-label="Twitter" style="color: #9ca3af;">🐦</a>
            <a href="#" aria-label="YouTube" style="color: #9ca3af;">▶️</a>
            <a href="#" aria-label="Instagram" style="color: #9ca3af;">📷</a>
            <span style="font-size: 13px; color: #9ca3af;">Currency: DJF</span>
        </div>
    </div>
</footer>

<!-- ═══════════════════════════════════ SCRIPTS ══════════════════════════════════ -->
<script>
(function () {
    /* ═══════════════════════════════════
       DESTINATION — clear button
    ═══════════════════════════════════ */
    const destInput = document.getElementById('dest-input');
    const destClear = document.getElementById('dest-clear');
    const sfDest    = document.getElementById('sf-dest');

    function refreshClear() {
        destClear.classList.toggle('show', destInput.value.length > 0);
    }

    destInput.addEventListener('input', refreshClear);
    destClear.addEventListener('click', (e) => {
        e.stopPropagation();
        destInput.value = '';
        destInput.focus();
        refreshClear();
    });
    destInput.addEventListener('focus', () => sfDest.classList.add('active'));
    destInput.addEventListener('blur',  () => sfDest.classList.remove('active'));
    refreshClear();

    /* ═══════════════════════════════════
       CALENDAR
    ═══════════════════════════════════ */
    const MONTHS = ['Janvier','Février','Mars','Avril','Mai','Juin',
                    'Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
    const DAYS   = ['lu','ma','me','je','ve','sa','di'];

    let calOpen    = false;
    let selStart   = null;
    let selEnd     = null;
    let hoverDay   = null;
    let leftYear, leftMonth;

    const today    = new Date(); today.setHours(0,0,0,0);

    function initCalendar() {
        const ci = document.getElementById('checkin-val') ? document.getElementById('checkin-val').value : '';
        const co = document.getElementById('checkout-val') ? document.getElementById('checkout-val').value : '';
        if (ci) {
            selStart = new Date(ci);
            selStart.setHours(0,0,0,0);
        }
        if (co) {
            selEnd = new Date(co);
            selEnd.setHours(0,0,0,0);
        }
        if (selStart) {
            leftYear = selStart.getFullYear();
            leftMonth = selStart.getMonth();
        } else {
            leftYear  = today.getFullYear();
            leftMonth = today.getMonth();
        }
        renderCalendar();
        if (selStart) applyDates();
    }

    function toggleCalendar() {
        if (typeof window.closeGuests === 'function') window.closeGuests();
        calOpen = !calOpen;
        document.getElementById('cal-popup').classList.toggle('open', calOpen);
        document.getElementById('sf-dates').classList.toggle('active', calOpen);
        document.getElementById('hero-search').classList.toggle('search-bar-active', calOpen);
        if (calOpen) {
            if (!selStart) {
                leftYear = today.getFullYear();
                leftMonth = today.getMonth();
            } else {
                leftYear = selStart.getFullYear();
                leftMonth = selStart.getMonth();
            }
            renderCalendar();
        }
    }

    function closeCalendar() {
        calOpen = false;
        document.getElementById('cal-popup').classList.remove('open');
        document.getElementById('sf-dates').classList.remove('active');
        document.getElementById('hero-search').classList.remove('search-bar-active');
    }

    function navMonth(delta) {
        leftMonth += delta;
        if (leftMonth > 11) { leftMonth = 0; leftYear++; }
        if (leftMonth < 0)  { leftMonth = 11; leftYear--; }
        renderCalendar();
    }

    function renderCalendar() {
        renderMonth('cal-left',  leftYear,  leftMonth,  true);
        let ry = leftYear, rm = leftMonth + 1;
        if (rm > 11) { rm = 0; ry++; }
        renderMonth('cal-right', ry, rm, false);
    }

    function renderMonth(containerId, year, month, isLeft) {
        const el = document.getElementById(containerId);
        const firstDay = new Date(year, month, 1);
        const lastDay  = new Date(year, month + 1, 0);

        let startOffset = firstDay.getDay() - 1;
        if (startOffset < 0) startOffset = 6;

        let html = `<div class="cal-month-header">`;
        if (isLeft) {
            const canPrev = new Date(year, month, 1) > today;
            html += `<button type="button" class="cal-nav" onclick="navMonth(-1)" ${!canPrev?'disabled':''}>&#8249;</button>`;
        } else {
            html += `<span></span>`;
        }
        html += `<span class="cal-month-title">${MONTHS[month]} ${year}</span>`;
        if (!isLeft) {
            html += `<button type="button" class="cal-nav" onclick="navMonth(1)">&#8250;</button>`;
        } else {
            html += `<span></span>`;
        }
        html += `</div>`;

        html += `<div class="cal-weekdays">${DAYS.map(d=>`<span>${d}</span>`).join('')}</div>`;
        html += `<div class="cal-days">`;

        for (let i = 0; i < startOffset; i++) html += `<span class="cal-day cal-day-empty"></span>`;

        for (let d = 1; d <= lastDay.getDate(); d++) {
            const date = new Date(year, month, d);
            const isPast = date < today;
            let cls = 'cal-day';
            if (isPast) cls += ' cal-day-past';

            if (!isPast) {
                if (selStart && isSameDay(date, selStart)) cls += ' cal-day-start';
                if (selEnd   && isSameDay(date, selEnd))   cls += ' cal-day-end';
                if (selStart && selEnd && date > selStart && date < selEnd) cls += ' cal-day-range';
                if (selStart && !selEnd && hoverDay && date > selStart && date <= hoverDay) cls += ' cal-day-range';
            }

            html += `<span class="${cls}" data-date="${date.toISOString()}" onclick="pickDay('${date.toISOString()}')" onmouseenter="hoverDate('${date.toISOString()}')">${d}</span>`;
        }

        html += `</div>`;
        el.innerHTML = html;
    }

    function isSameDay(a, b) {
        return a.getFullYear()===b.getFullYear() && a.getMonth()===b.getMonth() && a.getDate()===b.getDate();
    }

    function pickDay(iso) {
        const date = new Date(iso);
        if (!selStart || (selStart && selEnd)) {
            selStart = date; selEnd = null;
            document.getElementById('cal-hint').textContent = 'Sélectionnez une date de départ';
        } else {
            if (date <= selStart) { selStart = date; selEnd = null; return; }
            selEnd = date;
            applyDates();
            setTimeout(closeCalendar, 300);
        }
        renderCalendar();
    }

    function hoverDate(iso) {
        hoverDay = new Date(iso);
        if (selStart && !selEnd) renderCalendar();
    }

    function applyDates() {
        if (!selStart) return;
        const fmt = (d) => d.toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric', month: 'short' });
        const display = selEnd ? `${fmt(selStart)} — ${fmt(selEnd)}` : fmt(selStart);
        const disp = document.getElementById('date-display');
        disp.textContent = display;
        disp.className = 'date-value';
        document.getElementById('checkin-val').value  = selStart.toISOString().slice(0,10);
        document.getElementById('checkout-val').value = selEnd ? selEnd.toISOString().slice(0,10) : '';
    }

    function clearDates() {
        selStart = null; selEnd = null; hoverDay = null;
        const disp = document.getElementById('date-display');
        disp.textContent = "Date d'arrivée — Date de départ";
        disp.className = 'date-placeholder';
        document.getElementById('checkin-val').value  = '';
        document.getElementById('checkout-val').value = '';
        document.getElementById('cal-hint').textContent = "Sélectionnez une date d'arrivée";
        renderCalendar();
    }

    function quickStay(days) {
        if (!selStart) return;
        const end = new Date(selStart);
        end.setDate(end.getDate() + days);
        if (end < today) return;
        selEnd = end;
        applyDates();
        renderCalendar();
        setTimeout(closeCalendar, 200);
    }
    window.quickStay = quickStay;

    function switchTab(t) {
        document.getElementById('tab-cal').classList.toggle('active', t==='cal');
        document.getElementById('tab-flex').classList.toggle('active', t==='flex');
        const body = document.getElementById('cal-body');
        if (t === 'flex') {
            body.innerHTML = `<div style="padding:20px; text-align:center; color:#9ca3af; font-size:14px;">Prochainement disponible 🔜</div>`;
        } else {
            body.innerHTML = `<div class="cal-month" id="cal-left"></div><div class="cal-month" id="cal-right"></div>`;
            renderCalendar();
        }
    }

    window.toggleCalendar = toggleCalendar;
    window.navMonth       = navMonth;
    window.pickDay        = pickDay;
    window.hoverDate      = hoverDate;
    window.clearDates     = clearDates;
    window.switchTab      = switchTab;

    initCalendar();

    /* ═══════════════════════════════════
       GUESTS
    ═══════════════════════════════════ */
    const counts = { adults: {{ request('adults', 2) }}, children: {{ request('children', 0) }}, rooms: {{ request('rooms', 1) }} };
    const mins   = { adults: 1, children: 0, rooms: 1 };

    window.toggleGuests = function(e) {
        if (e) e.stopPropagation();
        if (calOpen) closeCalendar();
        const popup   = document.getElementById('guest-popup');
        const chevron = document.getElementById('sf-chevron');
        const sfg     = document.getElementById('sf-guests');
        const isOpen  = popup.classList.toggle('open');
        chevron.classList.toggle('open', isOpen);
        sfg.classList.toggle('active', isOpen);
    };

    window.closeGuests = function() {
        document.getElementById('guest-popup').classList.remove('open');
        document.getElementById('sf-chevron').classList.remove('open');
        document.getElementById('sf-guests').classList.remove('active');
    };

    window.adjustCount = function(type, delta) {
        counts[type] = Math.max(mins[type], counts[type] + delta);
        document.getElementById(`${type}-count`).textContent = counts[type];
        const decBtn = document.getElementById(`btn-${type}-dec`);
        if (decBtn) decBtn.disabled = counts[type] <= mins[type];
        updateGuestSummary();
        const inp = document.getElementById(`input-${type}`);
        if (inp) inp.value = counts[type];
    };

    function updateGuestSummary() {
        const a = counts.adults, c = counts.children, r = counts.rooms;
        document.getElementById('guest-summary').textContent =
            `${a} adulte${a>1?'s':''} · ${c} enfant${c>1?'s':''} · ${r} chambre${r>1?'s':''}`;
    }

    document.getElementById('btn-adults-dec').disabled   = counts.adults <= mins.adults;
    document.getElementById('btn-children-dec').disabled = counts.children <= mins.children;
    document.getElementById('btn-rooms-dec').disabled   = counts.rooms <= mins.rooms;

    const ia = document.getElementById('input-adults'); if (ia) ia.value = counts.adults;
    const ic = document.getElementById('input-children'); if (ic) ic.value = counts.children;
    const ir = document.getElementById('input-rooms'); if (ir) ir.value = counts.rooms;

    const sa = document.getElementById('adults-count'); if (sa) sa.textContent = counts.adults;
    const sc = document.getElementById('children-count'); if (sc) sc.textContent = counts.children;
    const sr = document.getElementById('rooms-count'); if (sr) sr.textContent = counts.rooms;
    updateGuestSummary();

    /* ═══════════════════════════════════
       CLOSE ON OUTSIDE CLICK
    ═══════════════════════════════════ */
    document.addEventListener('click', (e) => {
        const sfDates = document.getElementById('sf-dates');
        if (calOpen && sfDates && !sfDates.contains(e.target)) closeCalendar();
        const sfG = document.getElementById('sf-guests');
        if (sfG && !sfG.contains(e.target)) closeGuests();
    });

    /* ═══════════════════════════════════
       SCROLL FADE-IN
    ═══════════════════════════════════ */
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

    /* ═══════════════════════════════════
       ANIMATED COUNTERS
    ═══════════════════════════════════ */
    function animateCounter(el, target) {
        let val = 0;
        const step = Math.max(1, Math.ceil(target / 40));
        const t = setInterval(() => {
            val = Math.min(val + step, target);
            el.textContent = val;
            if (val >= target) clearInterval(t);
        }, 40);
    }

    const statsObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                const h = document.getElementById('stat-hotels');
                const b = document.getElementById('stat-bookings');
                if (h) animateCounter(h, parseInt(h.textContent) || 0);
                if (b) animateCounter(b, parseInt(b.textContent) || 0);
                statsObs.disconnect();
            }
        });
    }, { threshold: 0.3 });
    const statsEl = document.querySelector('.stats-strip');
    if (statsEl) statsObs.observe(statsEl);

})();
</script>
</body>
</html>
