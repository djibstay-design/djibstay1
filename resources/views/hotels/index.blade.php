<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hôtels à {{ request('city', 'Djibouti') }} - DjibStay</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --purple: #4c1d95;
            --purple-light: #6d28d9;
            --purple-bg: #2e1065;
            --yellow: #febb02;
            --yellow-hover: #f5a623;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #fff; color: #1a1a2e; line-height: 1.5; }

        .header {
            background: var(--purple-bg);
            padding: 0 40px;
            min-height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .header-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #fff;
        }
        .header-logo .icon { width: 36px; height: 36px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .header-logo .icon svg { width: 20px; height: 20px; }
        .header-logo h1 { font-size: 20px; font-weight: 800; }
        .header-logo .tagline { font-size: 10px; font-weight: 600; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px; }
        .header-nav { display: flex; gap: 8px; align-items: center; }
        .header-nav a { color: rgba(255,255,255,0.9); text-decoration: none; font-size: 14px; font-weight: 500; padding: 8px 14px; border-radius: 4px; }
        .header-nav a:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .header-btns { display: flex; gap: 10px; align-items: center; }
        .header-btns a {
            padding: 8px 18px; border-radius: 4px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.2s;
        }
        .btn-partner { background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.4); }
        .btn-signin { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.6); }
        .btn-partner:hover, .btn-signin:hover { background: rgba(255,255,255,0.1); }

        .breadcrumb {
            background: #fff;
            padding: 12px 40px;
            font-size: 13px;
            color: #64748b;
            border-bottom: 1px solid #e5e7eb;
        }
        .breadcrumb a { color: var(--purple-light); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        .main-wrap { display: flex; max-width: 1400px; margin: 0 auto; min-height: calc(100vh - 64px - 42px - 280px); }
        .sidebar {
            width: 280px;
            flex-shrink: 0;
            padding: 24px 20px;
            border-right: 1px solid #e5e7eb;
            background: #fafafa;
        }
        .sidebar h3 { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 16px; }
        .sidebar-section { margin-bottom: 24px; }
        .sidebar-section label { font-size: 13px; font-weight: 600; color: #374151; display: block; margin-bottom: 8px; }
        .sidebar-section input[type="text"] {
            width: 100%;
            padding: 10px 12px 10px 36px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' viewBox='0 0 24 24'%3E%3Cpath d='M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z'/%3E%3C/svg%3E") no-repeat 10px center;
        }
        .sidebar-section input[type="checkbox"],
        .sidebar-section input[type="radio"] { margin-right: 10px; accent-color: var(--purple); cursor: pointer; }
        .sidebar-section .check-label { display: flex; align-items: center; font-size: 14px; color: #374151; cursor: pointer; margin-bottom: 8px; }
        .budget-range { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .budget-range input[type="number"] { width: 90px; padding: 8px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; }
        .budget-range span { font-size: 13px; color: #64748b; }

        .content { flex: 1; padding: 24px 32px; }
        .content-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .content-head h2 { font-size: 22px; font-weight: 800; color: #1a1a2e; }
        .sort-select {
            padding: 8px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            background: #fff;
            color: #374151;
            cursor: pointer;
        }

        .hotel-card {
            display: flex;
            gap: 24px;
            padding: 20px 0;
            border-bottom: 1px solid #e5e7eb;
            text-decoration: none;
            color: inherit;
            transition: background 0.2s;
        }
        .hotel-card:hover { background: #f8fafc; }
        .hotel-card-img-wrap {
            width: 320px;
            min-width: 280px;
            height: 220px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            background: #e2e8f0;
        }
        .hotel-card-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .hotel-card-heart {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            font-size: 18px;
            z-index: 2;
        }
        .hotel-card-body { flex: 1; min-width: 0; display: flex; flex-direction: column; }
        .hotel-card-name { font-size: 20px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .hotel-card-stars { color: #febb02; font-size: 14px; margin-bottom: 6px; letter-spacing: 1px; }
        .hotel-card-location { font-size: 14px; color: var(--purple-light); text-decoration: underline; margin-bottom: 8px; }
        .hotel-card-desc { font-size: 14px; color: #64748b; margin-bottom: 12px; line-height: 1.5; }
        .hotel-card-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }
        .hotel-card-tag {
            font-size: 12px;
            color: #64748b;
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 20px;
        }
        .hotel-card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
            gap: 16px;
            flex-wrap: wrap;
        }
        .hotel-card-rating {
            text-align: right;
        }
        .hotel-card-rating-label { font-size: 13px; color: #374151; font-weight: 600; }
        .hotel-card-rating-score {
            display: inline-block;
            background: var(--purple);
            color: #fff;
            font-size: 18px;
            font-weight: 800;
            padding: 8px 12px;
            border-radius: 8px;
            margin-top: 4px;
        }
        .hotel-card-rating-count { font-size: 12px; color: #64748b; margin-top: 2px; }
        .hotel-card-price-wrap { text-align: right; }
        .hotel-card-price-note { font-size: 12px; color: #64748b; margin-bottom: 2px; }
        .hotel-card-price { font-size: 24px; font-weight: 800; color: #1a1a2e; }
        .hotel-card-price-tax { font-size: 12px; color: #64748b; margin-top: 2px; }
        .btn-availability {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--yellow);
            color: #1a1a2e;
            font-weight: 700;
            font-size: 15px;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            margin-top: 12px;
            font-family: inherit;
            transition: background 0.2s;
        }
        .btn-availability:hover { background: var(--yellow-hover); }

        .pagination-wrap { margin-top: 24px; display: flex; justify-content: center; }
        .pagination-wrap nav a, .pagination-wrap nav span { padding: 8px 14px; margin: 0 2px; border-radius: 6px; font-size: 14px; }
        .pagination-wrap nav a { background: #f1f5f9; color: #374151; text-decoration: none; }
        .pagination-wrap nav a:hover { background: var(--purple-light); color: #fff; }
        .pagination-wrap nav span { background: var(--purple); color: #fff; }

        .footer {
            background: var(--purple-bg);
            color: rgba(255,255,255,0.85);
            padding: 48px 40px 24px;
            margin-top: 48px;
        }
        .footer-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
        }
        .footer-col h4 { color: #fff; font-size: 14px; font-weight: 700; margin-bottom: 16px; }
        .footer-col p, .footer-col a { font-size: 13px; color: rgba(255,255,255,0.8); text-decoration: none; line-height: 1.8; }
        .footer-col a:hover { color: #fff; }
        .footer-col ul { list-style: none; }
        .footer-col li { margin-bottom: 8px; }
        .footer-bottom {
            max-width: 1200px;
            margin: 40px auto 0;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        .footer-bottom a { color: rgba(255,255,255,0.7); font-size: 13px; }
        .btn-partner-footer {
            background: var(--yellow);
            color: #1a1a2e;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-family: inherit;
        }
        .btn-partner-footer:hover { background: var(--yellow-hover); }

        @media (max-width: 1024px) {
            .hotel-card { flex-direction: column; }
            .hotel-card-img-wrap { width: 100%; min-width: 100%; height: 220px; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            .sidebar { width: 100%; border-right: none; border-bottom: 1px solid #e5e7eb; }
            .main-wrap { flex-direction: column; }
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="{{ route('home') }}" class="header-logo">
            <div class="icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div>
                <h1>DjibStay</h1>
                <div class="tagline">OFFICIAL HOTEL BOOKING PLATFORM</div>
            </div>
        </a>
        <nav class="header-nav">
            <a href="{{ route('hotels.index') }}">Hotels</a>
            <a href="{{ url('/') }}#explore">Cities</a>
            <a href="{{ url('/') }}#deals">Deals</a>
            <a href="#">About Djibouti</a>
            <a href="#">Contact</a>
        </nav>
        <div class="header-btns">
            <a href="{{ route('login') }}" class="btn-partner">Partner Login</a>
            <a href="{{ route('login') }}" class="btn-signin">Sign in</a>
        </div>
    </header>

    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> &gt; Djibouti &gt; {{ request('city', 'Djibouti City') }} hotels
    </div>

    <form method="GET" action="{{ route('hotels.index') }}" id="filter-form">
        @if(request('city'))<input type="hidden" name="city" value="{{ request('city') }}">@endif
        <div class="main-wrap">
            <aside class="sidebar">
                <h3>Filter by:</h3>

                <div class="sidebar-section">
                    <label>Search property name</label>
                    <input type="text" name="search" placeholder="e.g. Sheraton" value="{{ request('search') }}">
                </div>

                <div class="sidebar-section">
                    <label>Your budget (per night in DJF)</label>
                    <div class="budget-range">
                        <input type="number" name="min_price" placeholder="0" value="{{ request('min_price') }}" min="0" step="1000">
                        <span>—</span>
                        <input type="number" name="max_price" placeholder="200000+" value="{{ request('max_price') }}" min="0" step="1000">
                        <span>DJF</span>
                    </div>
                </div>

                <div class="sidebar-section">
                    <label>Popular filters</label>
                    <label class="check-label"><input type="checkbox" name="breakfast" value="1" {{ request('breakfast') ? 'checked' : '' }}> Breakfast included</label>
                    <label class="check-label"><input type="checkbox" name="wifi" value="1" {{ request('wifi') ? 'checked' : '' }}> Free WiFi</label>
                    <label class="check-label"><input type="checkbox" name="pool" value="1" {{ request('pool') ? 'checked' : '' }}> Swimming Pool</label>
                </div>

                <div class="sidebar-section">
                    <label>Review score</label>
                    <label class="check-label"><input type="radio" name="min_rating" value="9" {{ request('min_rating') == '9' ? 'checked' : '' }}> Excellent 9+</label>
                    <label class="check-label"><input type="radio" name="min_rating" value="8" {{ request('min_rating') == '8' ? 'checked' : '' }}> Very Good 8+</label>
                    <label class="check-label"><input type="radio" name="min_rating" value="7" {{ request('min_rating') == '7' ? 'checked' : '' }}> Good 7+</label>
                    <label class="check-label"><input type="radio" name="min_rating" value="" {{ !request('min_rating') ? 'checked' : '' }}> Any</label>
                </div>

                <div class="sidebar-section">
                    <label>Facilities</label>
                    <label class="check-label"><input type="checkbox" name="fitness" value="1"> Fitness center</label>
                    <label class="check-label"><input type="checkbox" name="airport" value="1"> Airport shuttle</label>
                    <label class="check-label"><input type="checkbox" name="restaurant" value="1"> Restaurant</label>
                </div>

                <button type="submit" style="width:100%; padding: 10px; background: var(--purple); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">Apply filters</button>
            </aside>

            <main class="content">
                <div class="content-head">
                    <h2>{{ request('city', 'Djibouti City') }}: {{ $hotels->total() }} properties found</h2>
                    <select class="sort-select" name="sort" onchange="document.getElementById('filter-form').submit();">
                        <option value="recommended" {{ request('sort') == 'recommended' ? 'selected' : '' }}>Sort by: Recommended</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Sort by: Rating</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Sort by: Price (low first)</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Sort by: Price (high first)</option>
                    </select>
                </div>

                @if($hotels->isEmpty())
                    <p class="text-gray-600 py-8">Aucun hôtel ne correspond à vos critères.</p>
                @else
                    @php
                        $imgFallbackList = $hotelImagesFallback ?? ['ayla.jpg', 'kempinski.jpeg', 'sheraton.jpeg', 'escale.jpg', 'waafi.jpg', 'gadileh.jpg', 'hotel europe.jpg', 'best western.jpeg'];
                    @endphp
                    @foreach($hotels as $hotel)
                        @php
                            $nomLower = strtolower($hotel->nom);
                            $imgFile = null;
                            foreach ($hotelImageMap ?? [] as $key => $file) {
                                if (str_contains($nomLower, $key)) { $imgFile = $file; break; }
                            }
                            if ($imgFile === null) {
                                $imgFile = $imgFallbackList[abs((int) $hotel->id) % count($imgFallbackList)];
                            }
                            $minPrice = (int) ($hotel->typesChambre->min('prix_par_nuit') ?? 0);
                            $rating = round(($hotel->avis_avg_note ?? 4.5) * 10) / 10;
                            $reviewsCount = $hotel->avis->count() ?: rand(100, 1500);
                            $ratingLabel = $rating >= 9 ? 'Excellent' : ($rating >= 8 ? 'Very Good' : ($rating >= 7 ? 'Good' : 'Pleasant'));
                        @endphp
                        <a href="{{ route('hotels.show', $hotel) }}" class="hotel-card">
                            <div class="hotel-card-img-wrap">
                                <img src="{{ asset('images/' . $imgFile) }}" alt="{{ $hotel->nom }}" onerror="this.src='{{ asset('images/ayla.jpg') }}';">
                                <button type="button" class="hotel-card-heart" onclick="event.preventDefault(); event.stopPropagation(); this.textContent = this.textContent === '🤍' ? '❤️' : '🤍'">🤍</button>
                            </div>
                            <div class="hotel-card-body">
                                <h3 class="hotel-card-name">{{ $hotel->nom }}</h3>
                                <div class="hotel-card-stars">★★★★★</div>
                                <div class="hotel-card-location">{{ $hotel->ville ?? 'Djibouti City' }}</div>
                                <p class="hotel-card-desc">{{ Str::limit($hotel->description ?? 'Comfortable accommodation with modern amenities.', 120) }}</p>
                                <div class="hotel-card-tags">
                                    <span class="hotel-card-tag">FREE cancellation</span>
                                    <span class="hotel-card-tag">No prepayment needed</span>
                                    @if($minPrice < 20000)<span class="hotel-card-tag">Great Value</span>@endif
                                </div>
                                <div class="hotel-card-bottom">
                                    <div class="hotel-card-rating">
                                        <div class="hotel-card-rating-label">{{ $ratingLabel }}</div>
                                        <div class="hotel-card-rating-score">{{ number_format($rating, 1) }}</div>
                                        <div class="hotel-card-rating-count">{{ number_format($reviewsCount) }} reviews</div>
                                    </div>
                                    <div class="hotel-card-price-wrap">
                                        <div class="hotel-card-price-note">1 night, 2 adults</div>
                                        <div class="hotel-card-price">{{ number_format($minPrice, 0, ',', ' ') }} DJF</div>
                                        <div class="hotel-card-price-tax">+ {{ number_format((int)($minPrice * 0.14), 0, ',', ' ') }} DJF taxes and charges</div>
                                        <button type="button" class="btn-availability" onclick="event.preventDefault(); window.location.href='{{ route('hotels.show', $hotel) }}'">
                                            See availability →
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach

                    <div class="pagination-wrap">
                        {{ $hotels->withQueryString()->links() }}
                    </div>
                @endif
            </main>
        </div>
    </form>

    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>DjibStay</h4>
                <p>Official hotel booking platform in Djibouti. Compare prices and book with confidence.</p>
                <p style="margin-top:12px; font-size:12px;">© {{ date('Y') }} DjibStay. All rights reserved.</p>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="{{ route('hotels.index') }}?city=Djibouti City">Hotels in Djibouti City</a></li>
                    <li><a href="#">Beach Resorts</a></li>
                    <li><a href="#">Business Hotels</a></li>
                    <li><a href="{{ url('/') }}#deals">Seasonal Deals</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Contact Support</a></li>
                    <li><a href="#">Booking Policy</a></li>
                    <li><a href="#">FAQs</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Partner with us</h4>
                <p>List your property and reach thousands of travelers.</p>
                <a href="{{ route('login') }}" class="btn-partner-footer" style="display:inline-block; margin-top:12px; text-decoration:none; color:#1a1a2e;">Become a Partner</a>
            </div>
        </div>
        <div class="footer-bottom">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
    </footer>
</body>
</html>
