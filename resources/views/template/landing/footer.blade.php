<!-- FOOTER -->
<style>
    /* ===== FOOTER ===== */
    footer {
        background: var(--dark);
        color: white;
        padding: 0;
        position: relative;
        overflow: hidden;
    }

    /* Decorative top wave */
    footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--coral), #FFD4B8, var(--coral));
    }

    /* Subtle pattern overlay */
    footer::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: radial-gradient(circle at 20% 50%, rgba(255,155,122,0.03) 0%, transparent 50%),
                          radial-gradient(circle at 80% 20%, rgba(255,155,122,0.03) 0%, transparent 50%);
        pointer-events: none;
    }

    .footer-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 5rem 1.5rem 2rem;
        position: relative;
        z-index: 1;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr 1fr;
        gap: 3rem;
        margin-bottom: 4rem;
    }

    /* Brand Column */
    .footer-brand {
        padding-right: 2rem;
    }

    .footer-brand-logo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .footer-brand-logo .logo-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--coral), #FF7A5C);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        box-shadow: 0 4px 15px rgba(255,155,122,0.3);
    }

    .footer-brand h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        margin: 0;
        background: linear-gradient(135deg, #ffffff 0%, #cccccc 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .footer-brand > p {
        color: rgba(255, 255, 255, 0.55);
        font-size: 0.9rem;
        line-height: 1.8;
        margin-bottom: 1.5rem;
    }

    .footer-newsletter {
        display: flex;
        gap: 0;
        margin-top: 1.5rem;
        background: rgba(255, 255, 255, 0.06);
        border-radius: 10px;
        padding: 5px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        transition: border-color 0.3s;
    }

    .footer-newsletter:focus-within {
        border-color: rgba(255, 155, 122, 0.4);
    }

    .footer-newsletter input {
        flex: 1;
        padding: 0.8rem 1.25rem;
        border-radius: 10px;
        border: none;
        background: transparent;
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
        color: white;
        outline: none;
    }

    .footer-newsletter input::placeholder {
        color: rgba(255, 255, 255, 0.35);
    }

    .footer-newsletter button {
        background: linear-gradient(135deg, var(--coral), #FF7A5C);
        color: white;
        border: none;
        padding: 0.8rem 1.75rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.85rem;
        font-family: 'Inter', sans-serif;
        white-space: nowrap;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 15px rgba(255,155,122,0.25);
    }

    .footer-newsletter button:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255,155,122,0.35);
    }

    .footer-social-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 2rem;
    }

    .footer-social-row span {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.35);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .footer-social-row .social-line {
        flex: 1;
        height: 1px;
        background: rgba(255, 255, 255, 0.08);
    }

    .social-links {
        display: flex;
        gap: 0.625rem;
    }

    .social-links a {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .social-links a:hover {
        background: var(--coral);
        border-color: var(--coral);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255,155,122,0.3);
    }

    /* Links Columns */
    .footer-links h4 {
        font-family: 'Inter', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .footer-links h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 24px;
        height: 2px;
        background: var(--coral);
        border-radius: 2px;
    }

    .footer-links a {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.5);
        text-decoration: none;
        margin-bottom: 0.85rem;
        font-size: 0.875rem;
        transition: all 0.3s;
    }

    .footer-links a::before {
        content: '';
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.3s;
    }

    .footer-links a:hover {
        color: white;
        padding-left: 4px;
    }

    .footer-links a:hover::before {
        background: var(--coral);
    }

    /* Divider */
    .footer-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
        margin-bottom: 2rem;
    }

    /* Bottom Bar */
    .footer-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 1.5rem;
    }

    .footer-bottom-left {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .footer-copyright {
        color: rgba(255, 255, 255, 0.35);
        font-size: 0.8rem;
    }

    .footer-payments {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .footer-payments .pay-icon {
        width: 36px;
        height: 24px;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        color: rgba(255, 255, 255, 0.4);
        font-weight: 600;
        font-family: 'Inter', sans-serif;
    }

    /* Back to Top */
    .back-to-top {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.5);
        text-decoration: none;
        transition: all 0.3s;
        font-size: 0.8rem;
    }

    .back-to-top:hover {
        background: var(--coral);
        border-color: var(--coral);
        color: white;
        transform: translateY(-2px);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
        }
        .footer-brand {
            padding-right: 0;
        }
    }

    @media (max-width: 576px) {
        .footer-wrapper {
            padding: 3.5rem 1.25rem 1.5rem;
        }
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
        .footer-newsletter {
            flex-direction: column;
            border-radius: 16px;
            padding: 8px;
        }
        .footer-newsletter button {
            width: 100%;
            border-radius: 12px;
        }
        .footer-bottom {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        .footer-bottom-left {
            flex-direction: column;
            gap: 1rem;
        }
        .footer-brand {
            text-align: center;
        }
        .footer-brand-logo {
            justify-content: center;
        }
        .footer-social-row {
            justify-content: center;
        }
        .social-links {
            justify-content: center;
        }
    }
</style>

@php
    $rawContent = $layout->content ?? '';

    $website_name = $title;

    if (is_array($rawContent)) {
        $content = $rawContent;
    } elseif (is_string($rawContent) && !empty($rawContent)) {
        $cleanJson = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $rawContent);
        $cleanJson = preg_replace('/,\s*([\]}])/', '$1', $cleanJson);
        $content = json_decode($cleanJson, true) ?? json_decode($rawContent, true) ?? [];
    } else {
        $content = [];
    }

    $domain = $website->domain ?? '';

    $title = $content['title'] ?? $content['title_en'] ?? '';
    $title_color = $content['title_color'] ?? '#ffffff';

    $repeater = $content['repeater'] ?? $content['tagline'];
    if (is_array($repeater)) {
        $repeater = collect($repeater)->sortBy('sort')->values()->all();
    }

    $subtitle = $content['subtitle'] ?? $content['subtitle_en'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $desc = $content['desc'] ?? $content['desc_en'] ?? '';
    $desc_color = $content['desc_color'] ?? '#ffffff';

    $button_text = $content['button_text'] ?? $content['button_text_en'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

@endphp

<footer>
    <div class="footer-wrapper">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-brand-logo">
                    <h3>{{ $title }}</h3>
                </div>
                <p>{{ $subtitle }}</p>
                <div class="footer-newsletter">
                    <input type="email" placeholder="{{ $desc }}">
                    <button>{{ $button_text }}</button>
                </div>
                <div class="footer-social-row">
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-links">
            </div>
            <div class="footer-links">
                <h4>Suggestion</h4>
                @foreach($repeater as $item)
                    <a href="#">{{ $item['label'] }}</a>
                @endforeach
            </div>
            <div class="footer-links">
                <h4>{{ $footerPresets['title'] ?? 'Menu' }}</h4>
                @foreach($footerPresets['footer_menu'] ?? [] as $menu)
                    @php
                        $menuUrl = $menu['url'] ?? '#';
                        if (!empty($menuUrl) && $menuUrl !== '#' && !str_starts_with($menuUrl, 'http') && !str_starts_with($menuUrl, '/')) {
                            $menuUrl = '/' . ($website->domain ?? '') . '/' . ltrim($menuUrl, '/');
                        }
                    @endphp
                    <a href="{{ $menuUrl }}">{{ $menu['label'] }}</a>
                @endforeach
            </div>
        </div>

        <div class="footer-divider"></div>

        <div class="footer-bottom">
            <div class="footer-bottom-left">
                <span class="footer-copyright">&copy; {{ date('Y') }} {{ $website_name }}. All rights reserved.</span>
            </div>
        </div>
    </div>
</footer>