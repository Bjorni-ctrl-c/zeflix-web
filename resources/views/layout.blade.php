<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Zeflix — Streaming Film')</title>
  @hasSection('description')<meta name="description" content="@yield('description')">@endif
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    /* ==========================================================================
       DESIGN SYSTEM & TOKENS (Netflix Theme)
       ========================================================================== */
    :root {
      color-scheme: dark;
      --color-bg: #141414;
      --color-bg-alt: #181818;
      --color-surface: #1e1e1e;
      --color-surface-hover: #262626;
      --color-border: rgba(255, 255, 255, 0.12);
      --color-border-strong: rgba(255, 255, 255, 0.28);
      --color-text: #ffffff;
      --color-text-muted: #b3b3b3;
      --color-text-subtle: #757575;

      /* Netflix Brand Colors */
      --netflix-red: #E50914;
      --netflix-red-hover: #F40612;
      --netflix-red-dark: #b81d24;
      --netflix-green: #46D369;

      --color-accent: var(--netflix-red);
      --color-accent-hover: var(--netflix-red-hover);
      --color-accent-soft: rgba(229, 9, 20, 0.18);

      --color-success: #46d369;
      --color-warning: #ffa00a;
      --color-danger: #e50914;

      --font-display: 'Bebas Neue', Impact, sans-serif;
      --font-body: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;

      --radius-sm: 4px;
      --radius-md: 6px;
      --radius-lg: 10px;
      --shadow-card: 0 12px 36px rgba(0, 0, 0, 0.85);
      --shadow-glow: 0 0 25px rgba(229, 9, 20, 0.35);

      --content-width: 1320px;
      --nav-height: 68px;
    }

    *, *::before, *::after { box-sizing: border-box }
    html { min-width: 320px; scroll-behavior: smooth; scroll-padding-top: 5rem }
    body {
      min-width: 320px;
      margin: 0;
      background: var(--color-bg);
      color: var(--color-text);
      font: 15px/1.6 var(--font-body);
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    a { color: inherit; text-decoration: none }
    img, video, iframe { max-width: 100% }
    button, input, select, textarea { font: inherit }
    :focus-visible { outline: 2px solid var(--netflix-red); outline-offset: 2px }
    ::selection { background: var(--netflix-red); color: #fff }

    h1, h2, h3, h4, p { margin-top: 0 }
    h1, h2, h3 { color: var(--color-text); line-height: 1.15 }
    h1, .display-heading { font: 400 clamp(2.6rem, 6vw, 4.8rem)/.96 var(--font-display); letter-spacing: .02em }
    h2 { font-size: clamp(1.25rem, 2.5vw, 1.65rem); font-weight: 700; letter-spacing: -.01em }
    h3 { font-size: 1.05rem; font-weight: 650 }
    p { color: var(--color-text-muted) }

    .font-bold { font-weight: 700 }
    .sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; clip-path: inset(50%) }

    /* ==========================================================================
       HEADER & NAVIGATION
       ========================================================================== */
    .bar {
      position: fixed;
      z-index: 100;
      inset: 0 0 auto;
      min-height: var(--nav-height);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1.5rem;
      padding: 0 max(4%, calc((100% - var(--content-width)) / 2));
      background: linear-gradient(180deg, rgba(0, 0, 0, 0.88) 0%, rgba(0, 0, 0, 0.4) 65%, transparent 100%);
      transition: background .25s ease, box-shadow .25s ease;
    }
    .bar.solid {
      background: rgba(20, 20, 20, 0.98);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.85);
      backdrop-filter: blur(8px);
    }
    .bar-left { display: flex; align-items: center; gap: 2rem }
    .logo {
      color: var(--netflix-red);
      font: 800 2.2rem/1 var(--font-display);
      letter-spacing: .08em;
      text-transform: uppercase;
      text-shadow: 0 2px 10px rgba(229, 9, 20, 0.45);
      transition: transform .2s ease;
    }
    .logo:hover { transform: scale(1.02) }

    .bar-nav {
      display: flex;
      align-items: center;
      gap: 1.35rem;
      margin: 0;
      padding: 0;
      list-style: none;
    }
    .bar-nav a {
      color: #e5e5e5;
      font-size: .88rem;
      font-weight: 500;
      transition: color .18s ease;
      white-space: nowrap;
    }
    .bar-nav a:hover, .bar-nav a[aria-current="page"] {
      color: #ffffff;
      font-weight: 700;
    }
    .bar-nav a[aria-current="page"] {
      border-bottom: 2px solid var(--netflix-red);
      padding-bottom: 2px;
    }

    .nav-badge-count {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 18px;
      height: 18px;
      border-radius: 99px;
      background: var(--netflix-red);
      color: #fff;
      font-size: .65rem;
      font-weight: 700;
      padding: 0 5px;
      margin-left: 4px;
      vertical-align: middle;
    }

    .bar-right {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    /* Live Search Header Box */
    .nav-search {
      position: relative;
      display: flex;
      align-items: center;
    }
    /* Hide navbar search on catalog page — catalog has its own search in the header */
    .page-catalog .nav-search { display: none; }
    .nav-search__input {
      width: 38px;
      height: 38px;
      padding: 0 0 0 34px;
      border: 1px solid transparent;
      border-radius: 99px;
      background: transparent;
      color: #fff;
      font-size: .84rem;
      transition: width .28s cubic-bezier(0.4, 0, 0.2, 1), background .2s ease, border-color .2s ease;
      cursor: pointer;
    }
    .nav-search__icon {
      position: absolute;
      left: 10px;
      pointer-events: none;
      font-size: .92rem;
      opacity: .85;
    }
    .nav-search__input:focus,
    .nav-search__input:not(:placeholder-shown) {
      width: 210px;
      padding-left: 36px;
      padding-right: 12px;
      border-color: rgba(255, 255, 255, 0.35);
      background: rgba(0, 0, 0, 0.85);
      cursor: text;
    }

    /* Profile / Auth Dropdown */
    .profile-menu { position: relative }
    .profile-trigger {
      display: flex;
      align-items: center;
      gap: .5rem;
      background: transparent;
      border: 0;
      padding: 0;
      cursor: pointer;
      color: #fff;
    }
    .avatar-sm {
      width: 32px;
      height: 32px;
      border-radius: var(--radius-sm);
      background: var(--netflix-red);
      display: grid;
      place-items: center;
      font-weight: 700;
      font-size: .85rem;
      color: #fff;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .profile-dropdown {
      display: none;
      position: absolute;
      right: 0;
      top: calc(100% + 12px);
      width: 200px;
      border: 1px solid var(--color-border);
      border-radius: var(--radius-sm);
      background: rgba(22, 22, 22, 0.98);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.9);
      padding: .5rem 0;
      backdrop-filter: blur(10px);
      z-index: 150;
    }
    .profile-menu:hover .profile-dropdown,
    .profile-menu:focus-within .profile-dropdown {
      display: block;
    }
    .profile-dropdown a, .profile-dropdown button {
      width: 100%;
      display: flex;
      align-items: center;
      gap: .65rem;
      padding: .6rem 1rem;
      border: 0;
      background: transparent;
      color: #d1d1d1;
      font-size: .84rem;
      text-align: left;
      cursor: pointer;
      transition: background .15s ease, color .15s ease;
    }
    .profile-dropdown a:hover, .profile-dropdown button:hover {
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
    }
    .profile-dropdown hr {
      border: 0;
      border-top: 1px solid var(--color-border);
      margin: .4rem 0;
    }

    /* Mobile Quick Navigation Bar */
    .mobile-subnav {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 95;
      background: rgba(18, 18, 18, 0.96);
      border-top: 1px solid var(--color-border);
      padding: .4rem .5rem;
      justify-content: space-around;
      align-items: center;
      backdrop-filter: blur(8px);
    }
    .mobile-subnav a {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 2px;
      color: #a3a3a3;
      font-size: .72rem;
      font-weight: 500;
      padding: .35rem .6rem;
      min-width: 60px;
      min-height: 44px;
      justify-content: center;
    }
    .mobile-subnav a.is-active, .mobile-subnav a:hover {
      color: #ffffff;
      font-weight: 700;
    }
    .mobile-subnav .mob-icon { font-size: 1.1rem }

    /* ==========================================================================
       BUTTONS & CALL TO ACTIONS
       ========================================================================== */
    .btn {
      min-height: 44px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .55rem;
      border: 1px solid transparent;
      border-radius: var(--radius-sm);
      padding: .65rem 1.25rem;
      font-size: .92rem;
      font-weight: 700;
      line-height: 1.25;
      text-align: center;
      cursor: pointer;
      transition: background .18s ease, transform .15s ease, box-shadow .18s ease, border-color .18s ease;
      white-space: nowrap;
    }
    .btn:hover { transform: translateY(-1px) }
    .btn--primary {
      background: var(--netflix-red);
      color: #fff;
    }
    .btn--primary:hover {
      background: var(--netflix-red-hover);
      box-shadow: 0 4px 15px rgba(229, 9, 20, 0.45);
    }
    .btn--netflix-play {
      background: #ffffff;
      color: #000000;
      font-weight: 800;
      padding: .75rem 1.6rem;
      font-size: 1rem;
    }
    .btn--netflix-play:hover {
      background: rgba(255, 255, 255, 0.85);
      color: #000;
    }
    .btn--netflix-secondary {
      background: rgba(109, 109, 110, 0.7);
      color: #ffffff;
      backdrop-filter: blur(4px);
    }
    .btn--netflix-secondary:hover {
      background: rgba(109, 109, 110, 0.45);
      color: #ffffff;
    }
    .btn--netflix-secondary.is-active {
      background: var(--netflix-red);
      color: #ffffff;
    }
    .btn--outline {
      border-color: rgba(255, 255, 255, 0.35);
      background: rgba(0, 0, 0, 0.4);
      color: #ffffff;
    }
    .btn--outline:hover {
      border-color: #ffffff;
      background: rgba(255, 255, 255, 0.12);
    }
    .btn--small {
      min-height: 36px;
      padding: .4rem .85rem;
      font-size: .8rem;
    }
    .btn--wide { width: 100% }

    /* ==========================================================================
       HERO BILLBOARD (Netflix Style)
       ========================================================================== */
    .netflix-billboard {
      position: relative;
      min-height: 78vh;
      display: flex;
      align-items: center;
      padding: calc(var(--nav-height) + 2rem) max(4%, calc((100% - var(--content-width)) / 2)) 4.5rem;
      background-color: #141414;
      background-image: var(--billboard-bg, radial-gradient(ellipse at 75% 30%, #301015, #141414 70%));
      background-position: center 25%;
      background-size: cover;
      overflow: hidden;
    }
    .netflix-billboard__overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(77deg, rgba(20, 20, 20, 0.95) 0%, rgba(20, 20, 20, 0.82) 42%, rgba(20, 20, 20, 0.3) 75%, transparent 100%);
      pointer-events: none;
    }
    .netflix-billboard__vignette {
      position: absolute;
      inset: auto 0 0 0;
      height: 180px;
      background: linear-gradient(to top, #141414 0%, rgba(20, 20, 20, 0.8) 50%, transparent 100%);
      pointer-events: none;
    }
    .netflix-billboard__inner {
      width: min(100%, var(--content-width));
      position: relative;
      z-index: 2;
      margin: 0 auto;
    }
    .netflix-billboard__content { max-width: 650px }
    .billboard-badge {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      margin-bottom: .8rem;
    }
    .billboard-badge__n {
      display: inline-grid;
      place-items: center;
      width: 20px;
      height: 20px;
      border-radius: 2px;
      background: var(--netflix-red);
      color: #fff;
      font-size: .78rem;
      font-weight: 900;
      font-family: var(--font-display);
    }
    .billboard-badge__text {
      color: #dedede;
      font-size: .75rem;
      font-weight: 750;
      letter-spacing: .14em;
      text-transform: uppercase;
    }
    .netflix-billboard__title {
      font: 400 clamp(3.2rem, 7vw, 5.8rem)/.96 var(--font-display);
      margin: 0 0 1rem;
      letter-spacing: .02em;
      text-shadow: 0 4px 18px rgba(0, 0, 0, 0.8);
    }
    .netflix-billboard__meta {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: .75rem;
      margin-bottom: 1rem;
      font-size: .88rem;
    }
    .netflix-billboard__synopsis {
      font-size: 1.05rem;
      line-height: 1.7;
      color: #d1d1d1;
      margin: 0 0 1.8rem;
      text-shadow: 0 2px 6px rgba(0, 0, 0, 0.7);
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .netflix-billboard__actions {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: .75rem;
    }

    /* Landing Hero for Guests */
    .hero--landing {
      position: relative;
      min-height: 72vh;
      display: flex;
      align-items: center;
      padding: calc(var(--nav-height) + 3rem) max(4%, calc((100% - var(--content-width)) / 2)) 4rem;
      background: radial-gradient(ellipse at 80% 40%, rgba(229, 9, 20, 0.22) 0%, rgba(20, 20, 20, 0.8) 55%, #141414 85%);
      overflow: hidden;
    }
    .hero__inner {
      width: min(100%, var(--content-width));
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 2rem;
      margin: 0 auto;
    }
    .hero__copy { max-width: 660px }
    .hero__kicker {
      display: block;
      color: #bbb;
      font-size: .82rem;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-bottom: .6rem;
    }
    .hero h1 { margin: .3rem 0 .85rem }
    .hero__description {
      font-size: 1.05rem;
      line-height: 1.75;
      color: #ccc;
      margin-bottom: 1.8rem;
    }
    .hero__actions {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: .85rem;
    }
    .hero__poster--landing {
      width: min(32vw, 320px);
      aspect-ratio: 2 / 3;
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: var(--radius-md);
      background: radial-gradient(circle at center, rgba(229, 9, 20, 0.3) 0%, #1e1e1e 80%);
      box-shadow: var(--shadow-card);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: .8rem;
      text-align: center;
      padding: 1.5rem;
      transform: rotate(3deg);
    }
    .hero__poster-mark {
      font: 400 6.5rem/1 var(--font-display);
      color: rgba(255, 255, 255, 0.85);
      text-shadow: 0 8px 25px rgba(229, 9, 20, 0.4);
    }
    .landing-poster__label {
      border: 1px solid rgba(255, 255, 255, 0.4);
      border-radius: 99px;
      padding: .25rem .75rem;
      font-size: .7rem;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
    }
    .landing-poster__caption {
      color: #ddd;
      font-size: .95rem;
      font-weight: 600;
    }

    /* ==========================================================================
       BADGES & RATINGS
       ========================================================================== */
    .match-score { color: var(--netflix-green); font-weight: 700; font-size: .88rem }
    .match-score--inline { color: var(--netflix-green); font-weight: 700; font-size: .76rem }
    .age-badge {
      display: inline-flex;
      align-items: center;
      border: 1px solid rgba(255, 255, 255, 0.4);
      padding: 0 .4rem;
      font-size: .72rem;
      font-weight: 600;
      color: #fff;
      line-height: 1.5;
    }
    .quality-badge {
      display: inline-flex;
      align-items: center;
      border: 1px solid rgba(255, 255, 255, 0.35);
      border-radius: 2px;
      padding: 0 .35rem;
      font-size: .68rem;
      font-weight: 700;
      color: #fff;
      letter-spacing: .05em;
    }
    .badge {
      display: inline-flex;
      align-items: center;
      border-radius: 3px;
      padding: .15rem .45rem;
      font-size: .68rem;
      font-weight: 750;
      text-transform: uppercase;
      letter-spacing: .04em;
    }
    .badge--free {
      background: #1c4b2d;
      color: #98ffbe;
      border: 1px solid rgba(70, 211, 105, 0.35);
    }
    .badge--premium {
      background: var(--netflix-red-dark);
      color: #ffffff;
      border: 1px solid rgba(229, 9, 20, 0.4);
    }

    /* Status Pills */
    .status-pill {
      display: inline-flex;
      align-items: center;
      border-radius: 99px;
      padding: .2rem .65rem;
      font-size: .72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .04em;
    }
    .status-pill--active, .status-pill--paid, .status-pill--published {
      background: rgba(70, 211, 105, 0.18);
      color: #46d369;
      border: 1px solid rgba(70, 211, 105, 0.45);
    }
    .status-pill--pending, .status-pill--draft {
      background: rgba(255, 160, 10, 0.18);
      color: #ffa00a;
      border: 1px solid rgba(255, 160, 10, 0.45);
    }
    .status-pill--failed, .status-pill--expired, .status-pill--hidden, .status-pill--archived {
      background: rgba(229, 9, 20, 0.18);
      color: #ff757b;
      border: 1px solid rgba(229, 9, 20, 0.45);
    }
    .status-pill--muted {
      background: rgba(255, 255, 255, 0.08);
      color: var(--color-text-muted);
      border: 1px solid var(--color-border);
    }

    /* ==========================================================================
       CONTAINERS & LAYOUT
       ========================================================================== */
    .wrap {
      width: min(100% - 2rem, var(--content-width));
      min-height: 60vh;
      margin: 0 auto;
      padding: calc(var(--nav-height) + 2.5rem) 0 4.5rem;
    }
    .wrap--browse {
      width: 100%;
      max-width: 100%;
      padding: 1.5rem max(4%, calc((100% - var(--content-width)) / 2)) 4.5rem;
    }
    .wrap--home { padding-top: 0 }
    .wrap--flush { padding-top: 0 }

    .page-intro {
      max-width: 820px;
      margin: 0 0 1.8rem;
    }
    .page-intro--compact { margin-bottom: 1.25rem }
    .page-intro--split {
      max-width: none;
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      gap: 1.5rem;
    }
    .eyebrow {
      display: flex;
      align-items: center;
      gap: .55rem;
      margin: 0 0 .3rem;
      color: var(--netflix-red);
      font-size: .72rem;
      font-weight: 800;
      letter-spacing: .12em;
      text-transform: uppercase;
    }
    .eyebrow__line {
      width: 16px;
      height: 2px;
      background: var(--netflix-red);
    }
    .text-link, .back-link {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      color: #b3b3b3;
      font-size: .84rem;
      font-weight: 600;
      transition: color .18s ease;
    }
    .text-link:hover, .back-link:hover { color: #ffffff }
    .back-link { margin-bottom: 1.5rem }

    /* Surface Card */
    .surface-card, .form-panel {
      border: 1px solid var(--color-border);
      border-radius: var(--radius-md);
      background: var(--color-surface);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.45);
      padding: clamp(1.2rem, 3vw, 2rem);
    }
    .card-heading {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 1.15rem;
    }
    .card-heading h2 {
      display: flex;
      align-items: center;
      gap: .6rem;
      margin: 0;
      font-size: 1.25rem;
    }
    .heading-count {
      border: 1px solid var(--color-border-strong);
      border-radius: 99px;
      padding: .1rem .55rem;
      font-size: .72rem;
      font-weight: 600;
      color: var(--color-text-muted);
    }

    /* ==========================================================================
       NETFLIX ROWS & SLIDERS
       ========================================================================== */
    .netflix-rows {
      display: flex;
      flex-direction: column;
      gap: 2.75rem;
      margin-top: -3.5rem;
      position: relative;
      z-index: 10;
    }
    .rowbox { position: relative }
    .section-heading {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: .85rem;
    }
    .section-heading h2 {
      margin: 0;
      font-size: clamp(1.2rem, 2.2vw, 1.5rem);
      font-weight: 700;
    }

    .netflix-row-wrap { position: relative }
    .row {
      display: flex;
      gap: .85rem;
      overflow-x: auto;
      padding: 1rem 0;
      scroll-snap-type: x proximity;
      scrollbar-width: none;
      -webkit-overflow-scrolling: touch;
    }
    .row::-webkit-scrollbar { display: none }
    .row .film-card {
      flex: 0 0 205px;
      scroll-snap-align: start;
    }

    .slider-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 20;
      width: 44px;
      height: 90%;
      max-height: 260px;
      border: 0;
      background: rgba(20, 20, 20, 0.7);
      color: #fff;
      font-size: 2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      opacity: 0;
      transition: opacity .2s ease, background .2s ease;
    }
    .netflix-row-wrap:hover .slider-btn { opacity: 1 }
    .slider-btn:hover { background: rgba(20, 20, 20, 0.95); color: var(--netflix-red) }
    .slider-btn--left { left: 0; border-radius: 0 4px 4px 0 }
    .slider-btn--right { right: 0; border-radius: 4px 0 0 4px }

    /* Film Card */
    .film-card {
      position: relative;
      display: flex;
      flex-direction: column;
      width: 100%;
      min-width: 0;
      border-radius: var(--radius-sm);
      background: transparent;
      transition: transform .25s cubic-bezier(0.2, 0, 0.2, 1);
      cursor: pointer;
    }
    .film-card__poster {
      position: relative;
      display: block;
      width: 100%;
      aspect-ratio: 2 / 3;
      border-radius: var(--radius-sm);
      background-color: #1e1e1e;
      background-image: radial-gradient(circle at center, #2e2e2e 0%, #161616 100%);
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.5);
      transition: box-shadow .25s ease, border-color .25s ease;
    }
    .film-card__link {
      position: absolute;
      inset: 0;
      z-index: 1;
      display: block;
    }
    .film-card__monogram {
      position: absolute;
      inset: 0;
      display: grid;
      place-items: center;
      color: rgba(255, 255, 255, 0.12);
      font: 400 5.5rem/1 var(--font-display);
      pointer-events: none;
    }
    .film-card .badge {
      position: absolute;
      top: .5rem;
      left: .5rem;
      z-index: 3;
      pointer-events: none;
    }

    .film-card__overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(0deg, rgba(0, 0, 0, 0.94) 0%, rgba(0, 0, 0, 0.45) 55%, transparent 100%);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: .85rem;
      opacity: 0;
      transition: opacity .22s ease;
      z-index: 2;
    }
    .film-card:hover {
      transform: translateY(-6px) scale(1.04);
      z-index: 10;
    }
    .film-card:hover .film-card__poster {
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.95);
      border-color: rgba(255, 255, 255, 0.3);
    }
    .film-card:hover .film-card__overlay { opacity: 1 }

    .film-card__actions {
      display: flex;
      align-items: center;
      gap: .45rem;
      margin-bottom: .6rem;
      position: relative;
      z-index: 4;
    }
    .film-card__btn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 1px solid rgba(255, 255, 255, 0.65);
      background: rgba(20, 20, 20, 0.85);
      color: #ffffff;
      display: grid;
      place-items: center;
      font-size: .82rem;
      cursor: pointer;
      transition: transform .15s ease, background .15s ease, border-color .15s ease;
    }
    .film-card__btn:hover {
      transform: scale(1.15);
      border-color: #fff;
    }
    .film-card__btn--play {
      background: #ffffff;
      color: #000000;
      border-color: #ffffff;
    }
    .film-card__btn--play:hover { background: #e6e6e6 }
    .film-card__btn--list.is-active {
      background: var(--netflix-red);
      border-color: var(--netflix-red);
      color: #ffffff;
    }

    .film-card__hover-meta {
      display: flex;
      align-items: center;
      gap: .5rem;
      font-size: .72rem;
    }

    .film-card__progress-track {
      position: absolute;
      inset: auto 0 0 0;
      height: 4px;
      background: rgba(255, 255, 255, 0.3);
      z-index: 4;
    }
    .film-card__progress-bar {
      height: 100%;
      background: var(--netflix-red);
      box-shadow: 0 0 8px var(--netflix-red);
    }

    .film-card__info { padding: .6rem .2rem .2rem }
    .film-card__title {
      display: block;
      color: #fff;
      font-size: .9rem;
      font-weight: 650;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .film-card__meta {
      display: flex;
      align-items: center;
      gap: .45rem;
      color: var(--color-text-muted);
      font-size: .76rem;
      margin-top: .15rem;
    }

    /* Top 10 Styled Row */
    .row--top10 .top10-item {
      display: flex;
      align-items: center;
      flex: 0 0 250px;
      scroll-snap-align: start;
    }
    .top10-rank {
      font-family: var(--font-display);
      font-size: clamp(6.5rem, 9vw, 9.5rem);
      line-height: .8;
      font-weight: 900;
      color: #141414;
      -webkit-text-stroke: 4px #595959;
      margin-right: -24px;
      z-index: 1;
      pointer-events: none;
      user-select: none;
      letter-spacing: -.1em;
    }
    .top10-card {
      flex: 0 0 170px !important;
      z-index: 2;
    }

    /* ==========================================================================
       CATALOG & CONTROLS
       ========================================================================== */
    .catalog-search-form { min-width: 320px }
    .search-input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .search-input-wrap input {
      width: 100%;
      height: 44px;
      padding: .5rem 2.5rem .5rem 2.4rem;
      border: 1px solid var(--color-border-strong);
      border-radius: var(--radius-sm);
      background: rgba(25, 25, 25, 0.9);
      color: #fff;
      font-size: .9rem;
    }
    .search-input-wrap input:focus {
      border-color: var(--netflix-red);
      outline: none;
    }
    .search-input-wrap .search-icon {
      position: absolute;
      left: .8rem;
      color: #888;
      pointer-events: none;
    }
    .search-input-wrap .search-clear-btn {
      position: absolute;
      right: .8rem;
      color: #999;
      font-weight: 700;
      padding: .2rem .4rem;
    }

    .catalog-controls {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      flex-wrap: wrap;
      margin-bottom: 1.25rem;
    }
    .filter-list {
      display: flex;
      gap: .5rem;
      overflow-x: auto;
      padding: .2rem 0;
      scrollbar-width: none;
    }
    .filter-chip {
      min-height: 36px;
      display: inline-flex;
      align-items: center;
      border: 1px solid var(--color-border);
      border-radius: 99px;
      padding: .35rem .95rem;
      background: var(--color-surface);
      color: #c7c7c7;
      font-size: .83rem;
      font-weight: 500;
      white-space: nowrap;
      transition: background .18s ease, border-color .18s ease, color .18s ease;
    }
    .filter-chip:hover, .filter-chip.is-active {
      border-color: var(--netflix-red);
      background: var(--netflix-red);
      color: #fff;
      font-weight: 700;
    }
    .sort-selector select {
      min-height: 38px;
      border: 1px solid var(--color-border-strong);
      border-radius: var(--radius-sm);
      background: var(--color-surface);
      color: #eee;
      padding: .35rem .8rem;
      font-size: .84rem;
    }

    .catalog-meta-bar {
      display: flex;
      align-items: center;
      gap: .75rem;
      color: var(--color-text-muted);
      font-size: .85rem;
      margin-bottom: 1.5rem;
    }
    .catalog-genre-tag {
      background: rgba(255, 255, 255, 0.1);
      padding: .15rem .5rem;
      border-radius: 3px;
      color: #fff;
    }

    .catalog-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 1.4rem 1rem;
    }
    @media (min-width: 680px) {
      .catalog-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.6rem 1.1rem }
    }
    @media (min-width: 960px) {
      .catalog-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1.8rem 1.25rem }
    }
    @media (min-width: 1200px) {
      .catalog-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 2rem 1.35rem }
    }

    .empty-state {
      grid-column: 1 / -1;
      min-height: 240px;
      display: grid;
      justify-items: center;
      align-content: center;
      border: 1px dashed var(--color-border);
      border-radius: var(--radius-md);
      padding: 3rem 2rem;
      text-align: center;
      background: rgba(25, 25, 25, 0.4);
    }
    .empty-state__mark {
      font-size: 2.8rem;
      color: var(--netflix-red);
      margin-bottom: .8rem;
    }

    /* ==========================================================================
       FILM DETAIL & WATCH
       ========================================================================== */
    .film-hero {
      min-height: 72vh;
      position: relative;
      display: flex;
      align-items: flex-end;
      overflow: hidden;
      padding: calc(var(--nav-height) + 3rem) max(4%, calc((100% - var(--content-width)) / 2)) 5rem;
      background-color: #141414;
      background-image: var(--hero-image, radial-gradient(ellipse at 75% 30%, #351218, #141414 70%));
      background-position: center;
      background-size: cover;
    }
    .film-hero::before {
      position: absolute;
      inset: 0;
      background: linear-gradient(90deg, rgba(20, 20, 20, 0.98) 0%, rgba(20, 20, 20, 0.88) 45%, rgba(20, 20, 20, 0.4) 80%, transparent 100%),
                  linear-gradient(0deg, #141414 0%, transparent 50%);
      content: "";
    }
    .film-hero__inner {
      width: min(100%, var(--content-width));
      position: relative;
      z-index: 2;
      margin: 0 auto;
    }
    .film-hero__content { max-width: 680px }
    .film-hero h1 {
      margin: .4rem 0 1rem;
      font: 400 clamp(3.2rem, 7vw, 5.6rem)/.96 var(--font-display);
      letter-spacing: .02em;
    }
    .film-meta {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: .75rem;
      color: #dedede;
      font-size: .88rem;
      margin-bottom: 1.25rem;
    }
    .film-hero__synopsis {
      font-size: 1.05rem;
      line-height: 1.8;
      color: #d8d8d8;
      margin: 0 0 2rem;
    }
    .film-hero__actions {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: .85rem;
    }
    .film-hero__notice {
      margin-top: 1.1rem;
      color: #bbb;
      font-size: .85rem;
    }

    .film-details-container {
      width: min(100% - 2rem, var(--content-width));
      margin: 0 auto;
      padding: 2.5rem 0 4.5rem;
    }
    .film-specs-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.25rem;
      border-top: 1px solid var(--color-border);
      border-bottom: 1px solid var(--color-border);
      padding: 1.8rem 0;
    }
    .spec-item { display: flex; flex-direction: column; gap: .3rem }
    .spec-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .08em; color: var(--color-text-subtle); font-weight: 600 }
    .spec-value { font-size: .95rem; font-weight: 600; color: #fff }

    .watch-page {
      max-width: 1100px;
      margin: 0 auto;
      padding-top: calc(var(--nav-height) + 1rem);
    }
    .watch-top-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 1rem;
    }
    .watch-back-btn {
      color: #ddd;
      font-size: .88rem;
      font-weight: 600;
    }
    .watch-back-btn:hover { color: var(--netflix-red) }
    .watch-title-meta { text-align: right }
    .watch-title { display: block; font-weight: 700; color: #fff }
    .watch-meta { font-size: .78rem; color: #999 }

    .player {
      aspect-ratio: 16 / 9;
      border-radius: var(--radius-md);
      overflow: hidden;
      background: #000;
      box-shadow: 0 15px 45px rgba(0, 0, 0, 0.9);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .watch-footer-info { margin-top: 1.8rem }
    .watch-badges {
      display: flex;
      align-items: center;
      gap: .75rem;
      margin: .6rem 0 1.1rem;
    }
    .watch-synopsis {
      font-size: 1rem;
      line-height: 1.8;
      color: #cfcfcf;
      max-width: 820px;
    }

    /* ==========================================================================
       NETFLIX QUICK VIEW MODAL
       ========================================================================== */
    .netflix-modal-backdrop {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 200;
      background: rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(6px);
      place-items: center;
      padding: 1rem;
      overflow-y: auto;
    }
    .netflix-modal-backdrop.is-open { display: grid }
    .netflix-modal {
      position: relative;
      width: min(100%, 780px);
      border-radius: var(--radius-md);
      background: #181818;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.95);
      border: 1px solid rgba(255, 255, 255, 0.15);
      overflow: hidden;
      animation: modalZoom .22s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes modalZoom {
      from { transform: scale(0.92); opacity: 0 }
      to { transform: scale(1); opacity: 1 }
    }
    .netflix-modal__close {
      position: absolute;
      top: 1rem;
      right: 1rem;
      z-index: 10;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 0;
      background: rgba(20, 20, 20, 0.85);
      color: #fff;
      font-size: 1.1rem;
      display: grid;
      place-items: center;
      cursor: pointer;
      transition: background .15s ease;
    }
    .netflix-modal__close:hover { background: #000 }
    .netflix-modal__backdrop-img {
      position: relative;
      height: 340px;
      background-position: center;
      background-size: cover;
      background-color: #242424;
      display: flex;
      align-items: flex-end;
      padding: 2rem;
    }
    .netflix-modal__backdrop-img::before {
      position: absolute;
      inset: 0;
      background: linear-gradient(0deg, #181818 0%, rgba(24, 24, 24, 0.6) 50%, transparent 100%);
      content: "";
    }
    .netflix-modal__hero-info {
      position: relative;
      z-index: 2;
    }
    .netflix-modal__title {
      font: 400 clamp(2.2rem, 5vw, 3.6rem)/1 var(--font-display);
      margin: 0 0 .8rem;
    }
    .netflix-modal__hero-actions {
      display: flex;
      align-items: center;
      gap: .75rem;
    }
    .netflix-modal__body { padding: 1.8rem 2rem 2.2rem }
    .netflix-modal__meta-row {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: .75rem;
      margin-bottom: 1.2rem;
    }
    .netflix-modal__synopsis {
      font-size: .98rem;
      line-height: 1.75;
      color: #cfcfcf;
      margin: 0;
    }

    /* Toast Notification */
    .toast-notice {
      position: fixed;
      bottom: 2rem;
      left: 50%;
      transform: translateX(-50%) translateY(100px);
      background: rgba(30, 30, 30, 0.95);
      color: #fff;
      border: 1px solid var(--netflix-red);
      border-radius: var(--radius-sm);
      padding: .85rem 1.6rem;
      font-size: .88rem;
      font-weight: 600;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
      z-index: 300;
      opacity: 0;
      transition: transform .25s ease, opacity .25s ease;
      pointer-events: none;
    }
    .toast-notice.show {
      transform: translateX(-50%) translateY(0);
      opacity: 1;
    }

    /* ==========================================================================
       ADMIN PANEL STYLES
       ========================================================================== */
    .admin-nav {
      display: flex;
      gap: .5rem;
      overflow-x: auto;
      margin: 0 0 1.5rem;
      padding: .4rem;
      border: 1px solid var(--color-border);
      border-radius: var(--radius-md);
      background: var(--color-bg-alt);
      scrollbar-width: thin;
    }
    .admin-nav a {
      min-height: 42px;
      display: inline-flex;
      flex: 0 0 auto;
      align-items: center;
      border-radius: var(--radius-sm);
      padding: .55rem 1rem;
      color: var(--color-text-muted);
      font-size: .85rem;
      font-weight: 600;
      text-decoration: none;
      transition: background .18s ease, color .18s ease;
    }
    .admin-nav a:hover, .admin-nav a.is-active {
      background: var(--netflix-red);
      color: #ffffff;
      font-weight: 700;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }
    @media (min-width: 760px) {
      .stats-grid { grid-template-columns: repeat(4, minmax(0, 1fr)) }
    }
    .stat-card {
      min-width: 0;
      display: grid;
      gap: .35rem;
      border: 1px solid var(--color-border);
      border-radius: var(--radius-md);
      padding: 1.25rem;
      background: var(--color-surface);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.35);
    }
    .stat-card--accent {
      background: radial-gradient(circle at 100% 0%, rgba(229, 9, 20, 0.22) 0%, var(--color-surface) 75%);
      border-color: rgba(229, 9, 20, 0.4);
    }
    .stat-card__label { color: var(--color-text-muted); font-size: .82rem; font-weight: 500 }
    .stat-card strong {
      color: #fff;
      font-size: clamp(1.4rem, 3.5vw, 2.1rem);
      font-weight: 800;
      letter-spacing: -.03em;
      line-height: 1.2;
    }
    .stat-card__detail { color: var(--color-text-subtle); font-size: .74rem }

    .admin-section { margin-top: 1rem }

    /* Tables */
    .table-scroll {
      width: 100%;
      overflow-x: auto;
      border-radius: var(--radius-sm);
      scrollbar-color: var(--color-border-strong) transparent;
      scrollbar-width: thin;
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      color: var(--color-text-muted);
      font-size: .86rem;
      text-align: left;
    }
    .data-table th {
      padding: .85rem .8rem;
      border-bottom: 1px solid var(--color-border-strong);
      color: #e5e5e5;
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      white-space: nowrap;
    }
    .data-table td {
      padding: .95rem .8rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      vertical-align: middle;
    }
    .data-table tbody tr:hover { background: rgba(255, 255, 255, 0.035) }
    .data-table td strong { color: #ffffff; font-weight: 650 }
    .data-table .table-empty {
      padding: 2.5rem 1rem;
      color: var(--color-text-muted);
      text-align: center;
    }
    .table-action { text-align: right }
    .table-action form { display: inline-flex; margin-left: .35rem }

    .pagination-wrap {
      margin-top: 1.25rem;
      display: flex;
      justify-content: center;
    }
    .pagination-wrap nav { display: flex; gap: .4rem }
    .pagination-wrap a, .pagination-wrap span {
      min-width: 38px;
      min-height: 38px;
      display: inline-grid;
      place-items: center;
      border: 1px solid var(--color-border);
      border-radius: var(--radius-sm);
      padding: .35rem .65rem;
      background: var(--color-surface);
      color: var(--color-text-muted);
      font-size: .84rem;
      text-decoration: none;
    }
    .pagination-wrap a:hover { border-color: var(--netflix-red); color: #fff }
    .pagination-wrap span[aria-current="page"], .pagination-wrap .is-active {
      border-color: var(--netflix-red);
      background: var(--netflix-red);
      color: #fff;
      font-weight: 700;
    }

    /* Forms */
    .form-stack { display: grid; gap: 1.25rem }
    .form-inline {
      display: flex;
      align-items: center;
      gap: .6rem;
      min-width: 260px;
    }
    .form-inline input { min-width: 0; flex: 1 }
    .form-panel { max-width: 960px }
    .form-panel__heading {
      padding-bottom: 1.1rem;
      margin-bottom: 1.25rem;
      border-bottom: 1px solid var(--color-border);
    }
    .form-panel__heading .eyebrow { margin-bottom: .35rem }
    .form-panel__heading p:last-child { margin: 0; font-size: .84rem }
    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 1.15rem;
    }
    .form-grid__wide { grid-column: 1 / -1 }
    label {
      min-width: 0;
      display: grid;
      gap: .45rem;
      color: #e5e5e5;
      font-size: .86rem;
      font-weight: 600;
    }
    .field-hint { color: var(--color-text-subtle); font-size: .75rem; font-weight: 450 }
    .required-mark { color: var(--netflix-red) }
    input:not([type="checkbox"]), select, textarea {
      width: 100%;
      min-height: 44px;
      border: 1px solid var(--color-border-strong);
      border-radius: var(--radius-sm);
      padding: .65rem .85rem;
      background: #111;
      color: var(--color-text);
      font-size: .91rem;
      line-height: 1.45;
    }
    textarea { min-height: 110px; resize: vertical }
    input::placeholder, textarea::placeholder { color: #666 }
    input:focus, select:focus, textarea:focus {
      border-color: var(--netflix-red);
      outline: 2px solid rgba(229, 9, 20, 0.25);
      outline-offset: 1px;
    }
    input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--netflix-red) }
    .check-label {
      display: flex;
      align-items: center;
      gap: .65rem;
      min-height: 30px;
      font-weight: 500;
      cursor: pointer;
    }
    .check-label input { flex: 0 0 auto; margin: 0 }
    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: .75rem;
      padding-top: 1.25rem;
      border-top: 1px solid var(--color-border);
    }
    .form-footnote { margin-top: .75rem; color: var(--color-text-muted); font-size: .84rem; text-align: center }
    .form-footnote a { color: var(--netflix-red); font-weight: 700 }
    .form-footnote a:hover { text-decoration: underline }

    /* Alerts & Notes */
    .note, .notice {
      display: flex;
      align-items: flex-start;
      gap: .75rem;
      margin: 0 0 1.2rem;
      border: 1px solid var(--color-border);
      border-left: 4px solid #757575;
      border-radius: var(--radius-sm);
      padding: .9rem 1.15rem;
      background: var(--color-surface);
      color: #f0f0f0;
      font-size: .9rem;
    }
    .note[role="status"] { border-left-color: var(--color-success); background: rgba(70, 211, 105, 0.12) }
    .note.err, .notice--error { border-left-color: var(--color-danger); background: rgba(229, 9, 20, 0.12); color: #ffd6d8 }
    .notice--draft { border-left-color: var(--color-warning); background: rgba(255, 160, 10, 0.12); color: #ffe6ba }

    /* ==========================================================================
       AUTH, ACCOUNT, STATUS & LEGAL
       ========================================================================== */
    .auth-layout {
      min-height: 540px;
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(340px, .85fr);
      overflow: hidden;
      border: 1px solid var(--color-border);
      border-radius: var(--radius-lg);
      background: #181818;
      box-shadow: var(--shadow-card);
      margin: 1.5rem auto;
      max-width: 960px;
    }
    .auth-aside {
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: clamp(2rem, 5vw, 4rem);
      background: radial-gradient(circle at 80% 50%, rgba(229, 9, 20, 0.25) 0%, #121212 70%);
      overflow: hidden;
    }
    .auth-aside h1 {
      position: relative;
      z-index: 1;
      margin: .5rem 0 1rem;
      font-size: clamp(3rem, 6vw, 4.5rem);
    }
    .auth-aside p { position: relative; z-index: 1; max-width: 380px }
    .auth-aside__monogram {
      position: absolute;
      right: -2rem;
      bottom: -3rem;
      font: 900 18rem/1 var(--font-display);
      color: rgba(255, 255, 255, 0.03);
      pointer-events: none;
    }
    .auth-card {
      padding: clamp(2rem, 5vw, 3.5rem);
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: #1c1c1c;
    }
    .auth-card h2 {
      font: 400 2.4rem/1 var(--font-display);
      margin: .2rem 0 .4rem;
      letter-spacing: .02em;
    }
    .auth-card__intro { margin-bottom: 1.5rem; font-size: .88rem }

    /* Account Grid */
    .account-grid {
      display: grid;
      grid-template-columns: minmax(0, .85fr) minmax(0, 1.15fr);
      gap: 1.25rem;
    }
    .account-profile { display: flex; align-items: center; gap: 1rem }
    .account-avatar {
      width: 56px;
      height: 56px;
      display: grid;
      place-items: center;
      border-radius: var(--radius-sm);
      background: var(--netflix-red);
      color: #fff;
      font-size: 1.3rem;
      font-weight: 800;
    }

    /* Checkout & Landing Offer */
    .subscription-panel {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(280px, .8fr);
      gap: 2rem;
      align-items: center;
      border: 1px solid rgba(229, 9, 20, 0.35);
      border-radius: var(--radius-md);
      padding: clamp(1.8rem, 4vw, 3rem);
      background: radial-gradient(circle at 100% 0%, rgba(229, 9, 20, 0.15) 0%, #1a1a1a 70%);
      margin-top: 3rem;
    }
    .subscription-panel__price {
      font-size: clamp(2rem, 4vw, 2.8rem);
      font-weight: 800;
      color: #fff;
      margin: .3rem 0 1rem;
    }
    .subscription-panel__price span { font-size: .85rem; color: #aaa; font-weight: 400 }

    .landing-section { padding: clamp(3rem, 6vw, 5rem) 0 }
    .landing-steps {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 1rem;
      padding: 0;
      list-style: none;
    }
    .landing-step, .landing-feature {
      border: 1px solid var(--color-border);
      border-radius: var(--radius-md);
      background: #181818;
      padding: 1.8rem;
    }
    .landing-step__number, .landing-feature__index {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 40px;
      height: 32px;
      border-radius: 4px;
      background: var(--netflix-red);
      color: #fff;
      font-weight: 800;
      font-size: .75rem;
      margin-bottom: 1.2rem;
    }
    .landing-features {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 1rem;
    }

    /* Status Page */
    .status-page {
      max-width: 620px;
      display: grid;
      justify-items: start;
      margin: 2rem auto;
      border: 1px solid var(--color-border);
      border-radius: var(--radius-lg);
      padding: clamp(1.5rem, 5vw, 3rem);
      background: radial-gradient(circle at 100% 0%, rgba(229, 9, 20, 0.15) 0%, var(--color-surface) 60%);
      box-shadow: var(--shadow-card);
    }
    .status-mark {
      width: 54px;
      height: 54px;
      display: grid;
      place-items: center;
      margin-bottom: 1.4rem;
      border: 1px solid var(--color-border-strong);
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.05);
      font-size: 1.5rem;
      font-weight: 800;
    }
    .status-mark--success {
      border-color: rgba(70, 211, 105, 0.5);
      background: rgba(70, 211, 105, 0.2);
      color: var(--color-success);
    }
    .status-mark--pending {
      border-color: rgba(255, 160, 10, 0.5);
      background: rgba(255, 160, 10, 0.2);
      color: var(--color-warning);
    }
    .status-mark--failed {
      border-color: rgba(229, 9, 20, 0.5);
      background: rgba(229, 9, 20, 0.2);
      color: var(--color-danger);
    }
    .status-reference {
      width: 100%;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: .4rem;
      margin: 1.5rem 0 0;
      border-top: 1px solid var(--color-border);
      padding-top: 1rem;
      color: var(--color-text-subtle);
      font-size: .8rem;
    }
    .status-reference strong { color: #fff }

    /* Legal Page */
    .legal-page { max-width: 820px; margin: 0 auto }
    .legal-page h1 { margin: .2rem 0 1.25rem }
    .legal-copy {
      margin-top: 1.25rem;
      border-top: 1px solid var(--color-border);
      padding-top: 1.25rem;
    }
    .legal-copy p { font-size: 1rem; line-height: 1.9 }

    /* Footer */
    footer {
      min-height: 90px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1.5rem;
      flex-wrap: wrap;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      padding: 2.2rem max(4%, calc((100% - var(--content-width)) / 2));
      background: #0f0f0f;
      color: #808080;
      font-size: .82rem;
    }
    .footer-links { display: flex; gap: 1.25rem; flex-wrap: wrap }
    .footer-links a { color: #808080 }
    .footer-links a:hover { color: #fff; text-decoration: underline }

    /* ==========================================================================
       RESPONSIVE BREAKPOINTS
       ========================================================================== */
    @media (max-width: 820px) {
      .bar-nav { display: none }
      .mobile-subnav { display: flex }
      body { padding-bottom: 60px }
      .landing-steps, .landing-features { grid-template-columns: 1fr }
      .subscription-panel { grid-template-columns: 1fr }
      .account-grid { grid-template-columns: 1fr }
      .auth-layout { grid-template-columns: 1fr }
      .form-grid { grid-template-columns: 1fr }
      .page-intro--split { align-items: flex-start; flex-direction: column; gap: 1rem }
      .catalog-search-form { width: 100% }
      .hero__inner { flex-direction: column; text-align: left }
      .hero__poster--landing { display: none }
      .row .film-card { flex-basis: 155px }
      .top10-card { flex-basis: 140px !important }
      .top10-rank { font-size: 5.5rem; margin-right: -16px }
      .slider-btn { display: none }
    }
  </style>
</head>
<body class="{{ request()->routeIs('catalog') ? 'page-catalog' : '' }}">
  <a class="sr-only" href="#main-content">Lewati ke konten</a>

  {{-- Netflix Sticky Header --}}
  <header class="bar" id="bar">
    <div class="bar-left">
      <a class="logo" href="{{ route('home') }}">{{ config('app.name') }}</a>
      <ul class="bar-nav" aria-label="Navigasi Utama">
        <li><a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Beranda</a></li>
        <li><a href="{{ route('catalog') }}" @if(request()->routeIs('catalog')) aria-current="page" @endif>Katalog</a></li>
        @auth
          @if(auth()->user()->isAdmin() || auth()->user()->hasActiveSubscription())
          <li>
            <a href="{{ route('watchlist') }}" @if(request()->routeIs('watchlist')) aria-current="page" @endif>
              Daftar Saya
              @php $wlCount = auth()->user()->watchlistFilms()->count(); @endphp
              @if($wlCount > 0)
              <span class="nav-badge-count" id="js-nav-wl-count">{{ $wlCount }}</span>
              @endif
            </a>
          </li>
          @endif
        @endauth
      </ul>
    </div>

    <div class="bar-right">
      {{-- Real-time Search Box in Navbar --}}
      <form method="get" action="{{ route('catalog') }}" class="nav-search" id="nav-search-form">
        <span class="nav-search__icon" aria-hidden="true">🔍</span>
        <input type="text"
               name="q"
               class="nav-search__input"
               placeholder="Cari film..."
               value="{{ request('q', '') }}"
               autocomplete="off"
               aria-label="Cari film">
      </form>

      @auth
        <div class="profile-menu">
          <button type="button" class="profile-trigger" aria-label="Menu Akun">
            <div class="avatar-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <span style="font-size: .7rem;" aria-hidden="true">▼</span>
          </button>
          <div class="profile-dropdown">
            <div style="padding: .5rem 1rem; font-size: .82rem; color: #aaa;">
              Halo, <strong style="color: #fff;">{{ auth()->user()->name }}</strong>
            </div>
            <hr>
            <a href="{{ route('account') }}">Pengaturan Akun</a>
            @if(auth()->user()->isAdmin() || auth()->user()->hasActiveSubscription())
            <a href="{{ route('watchlist') }}">Daftar Saya</a>
            @endif
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" style="color: #ff9e9e;">Panel Admin</a>
            @endif
            <hr>
            <form method="post" action="{{ route('logout') }}" style="margin: 0;">
              @csrf
              <button type="submit">Keluar dari Zeflix</button>
            </form>
          </div>
        </div>
      @else
        <a href="{{ route('login') }}" class="btn btn--small btn--outline">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn--small btn--primary">Daftar</a>
      @endauth
    </div>
  </header>

  {{-- Hero Section --}}
  @yield('hero')

  {{-- Main Content Container --}}
  <main class="wrap @yield('wrapclass')" id="main-content" tabindex="-1">
    @if(session('ok'))<div class="note" role="status">{{ session('ok') }}</div>@endif
    @if(session('err'))<div class="note err" role="alert">{{ session('err') }}</div>@endif
    @if($errors->any())<div class="note err" role="alert">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
    @yield('content')
  </main>

  {{-- Mobile Bottom Navigation --}}
  <nav class="mobile-subnav" aria-label="Navigasi Ponsel">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">
      <span class="mob-icon" aria-hidden="true">🏠</span>
      <span>Beranda</span>
    </a>
    <a href="{{ route('catalog') }}" class="{{ request()->routeIs('catalog') ? 'is-active' : '' }}">
      <span class="mob-icon" aria-hidden="true">🎬</span>
      <span>Katalog</span>
    </a>
    @auth
      @if(auth()->user()->isAdmin() || auth()->user()->hasActiveSubscription())
      <a href="{{ route('watchlist') }}" class="{{ request()->routeIs('watchlist') ? 'is-active' : '' }}">
        <span class="mob-icon" aria-hidden="true">🔖</span>
        <span>Daftar Saya</span>
      </a>
      @endif
      <a href="{{ route('account') }}" class="{{ request()->routeIs('account') ? 'is-active' : '' }}">
        <span class="mob-icon" aria-hidden="true">👤</span>
        <span>Akun</span>
      </a>
      @if(auth()->user()->isAdmin())
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'is-active' : '' }}">
        <span class="mob-icon" aria-hidden="true">⚙</span>
        <span>Admin</span>
      </a>
      @endif
    @else
      <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'is-active' : '' }}">
        <span class="mob-icon" aria-hidden="true">🔑</span>
        <span>Masuk</span>
      </a>
    @endauth
  </nav>

  {{-- Netflix Quick View Modal --}}
  <div class="netflix-modal-backdrop" id="quick-modal-backdrop" aria-hidden="true">
    <div class="netflix-modal" role="dialog" aria-modal="true" aria-labelledby="modal-film-title">
      <button type="button" class="netflix-modal__close" id="modal-close-btn" aria-label="Tutup jendela">✕</button>
      <div class="netflix-modal__backdrop-img" id="modal-backdrop">
        <div class="netflix-modal__hero-info">
          <h2 class="netflix-modal__title" id="modal-film-title">Memuat...</h2>
          <div class="netflix-modal__hero-actions">
            <a href="#" class="btn btn--netflix-play" id="modal-play-btn">
              <span aria-hidden="true">▶</span> Putar
            </a>
            <button type="button" class="btn btn--netflix-secondary js-watchlist-toggle" id="modal-watchlist-btn" data-slug="">
              <span aria-hidden="true">＋</span> Daftar Saya
            </button>
          </div>
        </div>
      </div>
      <div class="netflix-modal__body">
        <div class="netflix-modal__meta-row">
          <span class="match-score font-bold" id="modal-match-rate">98% Cocok</span>
          <span class="age-badge" id="modal-age-rating">16+</span>
          <span class="quality-badge" id="modal-quality">4K UHD</span>
          <span id="modal-duration">24m</span>
          <span class="badge badge--premium" id="modal-genre">Aksi</span>
        </div>
        <p class="netflix-modal__synopsis" id="modal-synopsis"></p>
        <div style="margin-top: 1.5rem; text-align: right;">
          <a href="#" id="modal-detail-link" class="text-link" style="color: var(--netflix-red); font-weight: 700;">
            Lihat halaman lengkap <span aria-hidden="true">→</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Toast notification banner --}}
  <div class="toast-notice" id="toast-notice" role="status" aria-live="polite"></div>

  {{-- Footer --}}
  <footer>
    <div>
      <span class="logo" style="font-size: 1.4rem;">{{ config('app.name') }}</span>
      <p style="margin: .3rem 0 0; color: #666;">Platform streaming film pendek karya sineas &amp; kreator mahasiswa.</p>
    </div>
    <nav class="footer-links" aria-label="Informasi Kebijakan">
      <a href="{{ route('legal', 'privasi') }}">Privasi</a>
      <a href="{{ route('legal', 'ketentuan') }}">Ketentuan</a>
      <a href="{{ route('legal', 'refund') }}">Refund</a>
      <a href="{{ route('home') }}#cara-kerja">Cara Kerja</a>
    </nav>
  </footer>

  <script>
    // Header scroll background effect
    const bar = document.getElementById('bar');
    const syncNav = () => bar.classList.toggle('solid', window.scrollY > 30);
    window.addEventListener('scroll', syncNav, { passive: true });
    syncNav();

    // Toast helper
    function showToast(msg) {
      const toast = document.getElementById('toast-notice');
      if (!toast) return;
      toast.textContent = msg;
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 2800);
    }

    // Watchlist 1-Click AJAX Toggle
    const csrfToken = @json(csrf_token());
    document.addEventListener('click', async function(e) {
      const btn = e.target.closest('.js-watchlist-toggle');
      if (!btn) return;
      e.preventDefault();
      e.stopPropagation();

      const slug = btn.getAttribute('data-slug');
      if (!slug) return;

      try {
        const res = await fetch(`/daftar-saya/toggle/${slug}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        });

        if (res.status === 401) {
          window.location.href = "{{ route('login') }}";
          return;
        }

        const data = await res.json();
        if (data.status === 'ok') {
          // Update all buttons for this slug on page
          document.querySelectorAll(`.js-watchlist-toggle[data-slug="${slug}"]`).forEach(b => {
            b.classList.toggle('is-active', data.in_watchlist);
            const icon = b.querySelector('span[aria-hidden="true"]');
            if (icon) icon.textContent = data.in_watchlist ? '✓' : '＋';
            if (b.innerText.includes('Daftar Saya')) {
              b.innerHTML = `<span aria-hidden="true">${data.in_watchlist ? '✓' : '＋'}</span> ${data.in_watchlist ? 'Ada di Daftar Saya' : 'Daftar Saya'}`;
            }
          });

          // Update navbar count
          let badge = document.getElementById('js-nav-wl-count');
          if (data.count > 0) {
            if (!badge) {
              const wlLink = document.querySelector('a[href="{{ route('watchlist') }}"]');
              if (wlLink) {
                badge = document.createElement('span');
                badge.id = 'js-nav-wl-count';
                badge.className = 'nav-badge-count';
                wlLink.appendChild(badge);
              }
            }
            if (badge) badge.textContent = data.count;
          } else if (badge) {
            badge.remove();
          }

          showToast(data.message);
        }
      } catch (err) {
        showToast('Terjadi kesalahan koneksi.');
      }
    });

    // Netflix Row Horizontal Slider Controls
    document.querySelectorAll('.netflix-row-wrap').forEach(wrap => {
      const row = wrap.querySelector('.js-slider-row');
      const leftBtn = wrap.querySelector('.js-slide-left');
      const rightBtn = wrap.querySelector('.js-slide-right');
      if (!row || !leftBtn || !rightBtn) return;

      leftBtn.addEventListener('click', () => {
        row.scrollBy({ left: -600, behavior: 'smooth' });
      });
      rightBtn.addEventListener('click', () => {
        row.scrollBy({ left: 600, behavior: 'smooth' });
      });
    });

    // Netflix Quick View Modal
    const modalBackdrop = document.getElementById('quick-modal-backdrop');
    const modalCloseBtn = document.getElementById('modal-close-btn');

    async function openQuickView(slug) {
      if (!slug) return;
      try {
        const res = await fetch(`/film/${slug}/quick`, {
          headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) return;
        const film = await res.json();

        document.getElementById('modal-film-title').textContent = film.title;
        document.getElementById('modal-match-rate').textContent = `${film.match_rate}% Cocok`;
        document.getElementById('modal-age-rating').textContent = film.age_rating;
        document.getElementById('modal-quality').textContent = film.quality;
        document.getElementById('modal-duration').textContent = film.duration || '';
        document.getElementById('modal-genre').textContent = film.genre;
        document.getElementById('modal-synopsis').textContent = film.synopsis;

        const backdropEl = document.getElementById('modal-backdrop');
        if (film.poster_url) {
          backdropEl.style.backgroundImage = `url('${film.poster_url}')`;
        } else {
          backdropEl.style.backgroundImage = 'none';
        }

        const playBtn = document.getElementById('modal-play-btn');
        playBtn.href = film.can_watch ? film.watch_url : film.checkout_url;
        playBtn.innerHTML = `<span aria-hidden="true">${film.can_watch ? '▶' : '🔓'}</span> ${film.can_watch ? 'Putar' : 'Buka Akses'}`;

        const wlBtn = document.getElementById('modal-watchlist-btn');
        wlBtn.setAttribute('data-slug', film.slug);
        wlBtn.classList.toggle('is-active', film.in_watchlist);
        wlBtn.innerHTML = `<span aria-hidden="true">${film.in_watchlist ? '✓' : '＋'}</span> ${film.in_watchlist ? 'Ada di Daftar Saya' : 'Daftar Saya'}`;

        document.getElementById('modal-detail-link').href = film.detail_url;

        modalBackdrop.classList.add('is-open');
        modalBackdrop.setAttribute('aria-hidden', 'false');
      } catch (err) {
        // error
      }
    }

    document.addEventListener('click', function(e) {
      const qBtn = e.target.closest('.js-quick-view');
      if (qBtn) {
        e.preventDefault();
        openQuickView(qBtn.getAttribute('data-slug'));
      }
    });

    if (modalCloseBtn) {
      modalCloseBtn.addEventListener('click', () => {
        modalBackdrop.classList.remove('is-open');
        modalBackdrop.setAttribute('aria-hidden', 'true');
      });
    }

    if (modalBackdrop) {
      modalBackdrop.addEventListener('click', (e) => {
        if (e.target === modalBackdrop) {
          modalBackdrop.classList.remove('is-open');
          modalBackdrop.setAttribute('aria-hidden', 'true');
        }
      });
    }

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modalBackdrop && modalBackdrop.classList.contains('is-open')) {
        modalBackdrop.classList.remove('is-open');
        modalBackdrop.setAttribute('aria-hidden', 'true');
      }
    });
  </script>
</body>
</html>
