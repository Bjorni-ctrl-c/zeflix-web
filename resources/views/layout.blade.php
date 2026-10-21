<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', config('app.name'))</title>
  @hasSection('description')<meta name="description" content="@yield('description')">@endif
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
  <style>
    :root {
      color-scheme: dark;
      --color-bg: #100f10;
      --color-bg-raised: #171516;
      --color-surface: #1d1a1b;
      --color-surface-raised: #262122;
      --color-border: #393334;
      --color-border-strong: #51494b;
      --color-text: #f8f5f5;
      --color-text-muted: #c1b8ba;
      --color-text-subtle: #a39a9c;
      --color-accent: #e50914;
      --color-accent-hover: #f3212c;
      --color-accent-soft: #40191d;
      --color-success: #7bd4a5;
      --color-warning: #f1c77d;
      --color-danger: #ff9191;
      --font-display: 'Bebas Neue', Impact, sans-serif;
      --font-body: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      --space-1: .25rem;
      --space-2: .5rem;
      --space-3: .75rem;
      --space-4: 1rem;
      --space-5: 1.5rem;
      --space-6: 2rem;
      --space-7: 3rem;
      --space-8: 4rem;
      --radius-sm: 3px;
      --radius-md: 6px;
      --radius-lg: 10px;
      --shadow-card: 0 18px 55px rgba(0, 0, 0, .28);
      --content-width: 1240px;
      --nav-height: 76px
    }

    *, *::before, *::after { box-sizing: border-box }
    html { min-width: 320px; scroll-behavior: smooth; scroll-padding-top: 6rem }
    body { min-width: 320px; margin: 0; background: var(--color-bg); color: var(--color-text); font: 16px/1.6 var(--font-body); -webkit-font-smoothing: antialiased }
    body::before { position: fixed; z-index: -1; inset: 0; background: radial-gradient(ellipse at 78% 0%, rgba(120, 25, 32, .12), transparent 38%); content: "" }
    a { color: inherit }
    img, video, iframe { max-width: 100% }
    button, input, select, textarea { font: inherit }
    :focus-visible { outline: 3px solid #ff7b82; outline-offset: 3px }
    ::selection { background: rgba(229, 9, 20, .55); color: #fff }
    h1, h2, h3, p { margin-top: 0 }
    h1, h2, h3 { color: var(--color-text); line-height: 1.12 }
    h1, .display-heading { font: 400 clamp(2.8rem, 7vw, 5.6rem)/.98 var(--font-display); letter-spacing: .015em }
    h2 { font-size: clamp(1.4rem, 3vw, 2rem); font-weight: 650; letter-spacing: -.025em }
    h3 { font-size: 1.05rem; font-weight: 650 }
    p { color: var(--color-text-muted) }
    .sr-only { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; clip-path: inset(50%) }
    .sr-only:focus { z-index: 100; width: auto; height: auto; position: fixed; top: .75rem; left: .75rem; clip: auto; clip-path: none; border-radius: var(--radius-sm); padding: .7rem 1rem; background: #fff; color: #171315 }

    .bar { position: fixed; z-index: 50; inset: 0 0 auto; min-height: var(--nav-height); display: flex; align-items: center; justify-content: space-between; gap: var(--space-4); padding: .8rem max(4%, calc((100% - var(--content-width)) / 2)); border-bottom: 1px solid transparent; background: linear-gradient(180deg, rgba(10, 9, 10, .97), rgba(10, 9, 10, .78)); transition: background .2s ease, border-color .2s ease }
    .bar.solid { border-color: rgba(255, 255, 255, .08); background: rgba(16, 15, 16, .97) }
    .logo { flex: 0 0 auto; color: var(--color-accent); font: 400 2.15rem/1 var(--font-display); letter-spacing: .055em; text-decoration: none; text-transform: uppercase }
    .bar nav { min-width: 0; display: flex; align-items: center; justify-content: flex-end; gap: .35rem; overflow-x: auto; scrollbar-width: none }
    .bar nav::-webkit-scrollbar { display: none }
    .bar nav > a, .bar nav button { min-height: 42px; display: inline-flex; align-items: center; justify-content: center; border: 0; border-radius: var(--radius-sm); padding: .5rem .7rem; background: transparent; color: #e5dfe0; font: 500 .88rem/1.2 var(--font-body); text-decoration: none; white-space: nowrap; cursor: pointer; transition: background .18s ease, color .18s ease }
    .bar nav > a:hover, .bar nav button:hover { background: rgba(255, 255, 255, .08); color: #fff }
    .bar nav > a[aria-current="page"] { color: #fff }
    .bar nav form { display: inline-flex; margin: 0 }
    .bar nav > a.btn { min-height: 40px; padding: .55rem 1rem }

    .btn { min-height: 46px; display: inline-flex; align-items: center; justify-content: center; gap: .55rem; border: 1px solid transparent; border-radius: var(--radius-sm); padding: .7rem 1.1rem; background: var(--color-accent); color: #fff; font-size: .9rem; font-weight: 700; line-height: 1.25; text-align: center; text-decoration: none; cursor: pointer; transition: background .18s ease, border-color .18s ease, color .18s ease, transform .18s ease }
    .btn:hover { background: var(--color-accent-hover); color: #fff; transform: translateY(-1px) }
    .btn--primary { background: var(--color-accent); color: #fff }
    .btn--light { background: #f7f4f4; color: #171314 }
    .btn--light:hover { background: #fff; color: #171314 }
    .btn--outline { border-color: var(--color-border-strong); background: rgba(16, 15, 16, .35); color: var(--color-text) }
    .btn--outline:hover { border-color: #93878a; background: rgba(255, 255, 255, .07) }
    .btn--small { min-height: 36px; padding: .45rem .75rem; font-size: .8rem }
    .btn--wide { width: 100% }

    .wrap { width: min(100% - 2rem, var(--content-width)); min-height: 55vh; margin: 0 auto; padding: calc(var(--nav-height) + 3.25rem) 0 4.5rem }
    .wrap--flush { padding-top: 0 }
    .wrap--home { padding-top: 0 }
    main { outline: none }
    .page-intro { max-width: 760px; margin: 0 0 var(--space-6) }
    .page-intro--compact { margin-bottom: var(--space-5) }
    .page-intro--split { max-width: none; display: flex; align-items: flex-end; justify-content: space-between; gap: var(--space-5) }
    .page-intro h1 { margin: .25rem 0 .5rem }
    .page-intro > p:last-child { max-width: 620px; margin-bottom: 0; color: var(--color-text-muted) }
    .eyebrow { display: flex; align-items: center; gap: .65rem; margin: 0 0 .8rem; color: #e8babc; font-size: .7rem; font-weight: 750; letter-spacing: .16em; line-height: 1.45; text-transform: uppercase }
    .eyebrow__line { width: 22px; height: 1px; flex: 0 0 auto; background: var(--color-accent) }
    .text-link, .back-link { display: inline-flex; align-items: center; gap: .55rem; color: #f3eded; font-size: .86rem; font-weight: 650; text-decoration: none }
    .text-link:hover, .back-link:hover { color: #ff777e }
    .back-link { margin-bottom: var(--space-5); color: var(--color-text-muted) }

    .hero { min-height: 660px; position: relative; display: flex; align-items: center; overflow: hidden; padding: calc(var(--nav-height) + 4rem) max(4%, calc((100% - var(--content-width)) / 2)) 4rem; background: #131011 }
    .hero::before { position: absolute; inset: 0; background-image: linear-gradient(90deg, #100f10 0%, rgba(16, 15, 16, .92) 32%, rgba(16, 15, 16, .62) 68%, rgba(16, 15, 16, .3)), linear-gradient(0deg, #100f10 0%, transparent 45%), var(--hero-image, radial-gradient(ellipse at 72% 32%, #402329, #151214 55%)); background-position: center; background-size: cover; content: "" }
    .hero__inner { width: min(100%, var(--content-width)); position: relative; min-height: 390px; display: flex; align-items: center; margin: 0 auto }
    .hero__copy { width: min(100%, 640px); position: relative; z-index: 2 }
    .hero__copy .eyebrow { margin-bottom: 1.25rem }
    .hero__kicker { display: flex; align-items: center; gap: .7rem; margin-bottom: .7rem; color: #ded4d5; font-size: .8rem; font-weight: 650; letter-spacing: .04em; text-transform: uppercase }
    .hero h1 { max-width: 650px; margin: .2rem 0 .65rem; font-size: clamp(3.4rem, 8vw, 6.7rem); text-wrap: balance }
    .hero__meta { margin: 0 0 .9rem; color: #e0d8d9; font-size: .87rem }
    .hero__description { max-width: 520px; margin: 0 0 1.5rem; color: #d4ccce; font-size: 1rem; line-height: 1.75 }
    .hero__actions { display: flex; flex-wrap: wrap; gap: .65rem }
    .hero__poster { width: min(30vw, 330px); aspect-ratio: 2 / 3; position: absolute; right: 2%; top: 50%; border: 1px solid rgba(255, 255, 255, .19); border-radius: var(--radius-md); background-color: var(--color-surface); background-position: center; background-size: cover; box-shadow: var(--shadow-card); transform: translateY(-50%) rotate(3deg) }
    .hero__poster-mark { width: 100%; height: 100%; display: grid; place-items: center; background: radial-gradient(ellipse at 50% 42%, rgba(229, 9, 20, .24), transparent 48%), linear-gradient(145deg, rgba(255, 255, 255, .05), transparent 68%); color: rgba(255, 255, 255, .22); font: 400 clamp(5rem, 12vw, 9rem)/1 var(--font-display); letter-spacing: -.09em }
    .hero--home .hero__copy { padding-right: min(28vw, 300px) }
    .film-hero { min-height: 640px; position: relative; display: flex; align-items: flex-end; overflow: hidden; padding: calc(var(--nav-height) + 4rem) max(4%, calc((100% - var(--content-width)) / 2)) 5rem }
    .film-hero::before { position: absolute; inset: 0; background-image: linear-gradient(90deg, rgba(13, 11, 12, .97), rgba(13, 11, 12, .83) 42%, rgba(13, 11, 12, .4)), linear-gradient(0deg, #100f10 0%, transparent 48%), var(--hero-image, linear-gradient(135deg, #38242b, #151214)); background-position: center; background-size: cover; content: "" }
    .film-hero__inner { width: min(100%, var(--content-width)); position: relative; margin: 0 auto }
    .film-hero__content { max-width: 700px }
    .film-hero__content .back-link { margin-bottom: 2rem }
    .film-hero h1 { margin: .2rem 0 1rem; font-size: clamp(3.5rem, 8vw, 6.4rem); overflow-wrap: anywhere }
    .film-meta { display: flex; align-items: center; flex-wrap: wrap; gap: .65rem; color: var(--color-text-muted); font-size: .86rem }
    .film-hero__synopsis { max-width: 620px; margin: 1rem 0 1.5rem; color: #e1d9da; font-size: 1rem; line-height: 1.8 }
    .film-hero__actions { display: flex; align-items: center; flex-wrap: wrap; gap: .65rem }
    .film-hero__actions form { margin: 0 }
    .film-hero__notice { margin: .9rem 0 0; color: #ddd3d4; font-size: .84rem }

    .badge { width: fit-content; display: inline-flex; align-items: center; border: 1px solid var(--color-border-strong); border-radius: 99px; padding: .18rem .58rem; color: #f2e9e9; font-size: .7rem; font-weight: 700; line-height: 1.5; white-space: nowrap }
    .badge--free { border-color: rgba(123, 212, 165, .5); background: rgba(29, 82, 55, .45); color: #b4f0cb }
    .badge--premium { border-color: rgba(229, 9, 20, .52); background: rgba(93, 22, 27, .46); color: #ffb5b9 }
    .meta { color: var(--color-text-muted); font-size: .88rem }

    .home-content { width: min(100%, var(--content-width)); margin: 0 auto }
    .rowbox { margin: 0 0 3rem }
    .section-heading, .card-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: var(--space-4); margin-bottom: 1rem }
    .section-heading .eyebrow, .card-heading .eyebrow { margin-bottom: .35rem }
    .section-heading h2, .card-heading h2 { margin: 0 }
    .row { display: flex; gap: 1rem; overflow-x: auto; padding: .5rem .1rem 1.1rem; scroll-snap-type: x proximity; scrollbar-color: var(--color-border-strong) transparent; scrollbar-width: thin }
    .row .film-card { flex: 0 0 160px; scroll-snap-align: start }
    .film-card { min-width: 0; display: block; color: var(--color-text); text-decoration: none }
    .film-card__poster { aspect-ratio: 2 / 3; position: relative; display: grid; place-items: center; overflow: hidden; border: 1px solid rgba(255, 255, 255, .09); border-radius: var(--radius-sm); background: radial-gradient(ellipse at 52% 34%, rgba(190, 68, 58, .52), transparent 30%), linear-gradient(145deg, #48272d, #21191e 60%, #141214); background-position: center; background-size: cover; box-shadow: 0 9px 24px rgba(0, 0, 0, .2); transition: border-color .2s ease, transform .2s ease }
    .film-card:nth-child(3n + 2) .film-card__poster { background-image: radial-gradient(ellipse at 54% 34%, rgba(137, 133, 166, .52), transparent 29%), linear-gradient(145deg, #373541, #211d25 62%, #131214) }
    .film-card:nth-child(3n) .film-card__poster { background-image: radial-gradient(ellipse at 54% 35%, rgba(203, 140, 91, .49), transparent 29%), linear-gradient(145deg, #493429, #221b1d 62%, #131214) }
    .film-card__poster::after { position: absolute; inset: 35% 0 0; background: linear-gradient(0deg, rgba(0, 0, 0, .45), transparent); content: "" }
    .film-card__monogram { color: rgba(255, 255, 255, .19); font: 400 5rem/1 var(--font-display); letter-spacing: -.08em; transform: rotate(-8deg) }
    .film-card .badge { position: absolute; z-index: 1; top: .65rem; left: .65rem }
    .film-card__play { position: absolute; z-index: 1; top: 50%; left: 50%; width: 44px; height: 44px; display: grid; place-items: center; border: 1px solid rgba(255, 255, 255, .8); border-radius: 50%; background: rgba(15, 12, 13, .55); color: #fff; font-size: .8rem; opacity: 0; transform: translate(-50%, -40%); transition: opacity .18s ease, transform .18s ease }
    .film-card__title, .film-card__meta { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap }
    .film-card__title { margin-top: .7rem; font-size: .9rem; font-weight: 650 }
    .film-card__meta { margin-top: .15rem; color: var(--color-text-muted); font-size: .77rem }
    .film-card:hover .film-card__poster, .film-card:focus-visible .film-card__poster { border-color: rgba(255, 255, 255, .5); transform: translateY(-3px) }
    .film-card:hover .film-card__play, .film-card:focus-visible .film-card__play { opacity: 1; transform: translate(-50%, -50%) }
    .catalog-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.4rem .9rem }
    .filter-list { display: flex; gap: .55rem; overflow-x: auto; margin: 0 0 var(--space-5); padding: .2rem .1rem .65rem; scrollbar-width: thin }
    .filter-chip { min-height: 40px; display: inline-flex; flex: 0 0 auto; align-items: center; justify-content: center; border: 1px solid var(--color-border); border-radius: 99px; padding: .45rem .9rem; background: var(--color-surface); color: var(--color-text-muted); font-size: .84rem; text-decoration: none; transition: background .18s ease, border-color .18s ease, color .18s ease }
    .filter-chip:hover, .filter-chip.is-active { border-color: rgba(229, 9, 20, .65); background: var(--color-accent-soft); color: #fff }
    .empty-state { grid-column: 1 / -1; min-height: 210px; display: grid; justify-items: center; align-content: center; border: 1px dashed var(--color-border-strong); border-radius: var(--radius-md); padding: 2rem; text-align: center }
    .empty-state__mark { margin-bottom: .5rem; color: #75696c; font: 400 3rem/1 var(--font-display) }
    .empty-state h2 { margin: 0 0 .35rem; font-size: 1.2rem }
    .empty-state p { margin: 0; font-size: .9rem }
    .subscription-panel { display: grid; grid-template-columns: minmax(0, 1fr) minmax(260px, .72fr); align-items: center; gap: var(--space-6); margin-top: 3.5rem; border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: clamp(1.4rem, 4vw, 3rem); background: radial-gradient(ellipse at 100% 0%, rgba(229, 9, 20, .17), transparent 45%), linear-gradient(135deg, #211a1c, #151315 70%) }
    .subscription-panel h2 { margin: 0 0 .7rem; font: 400 clamp(2.3rem, 5vw, 3.8rem)/1 var(--font-display); letter-spacing: .02em }
    .subscription-panel__copy > p:last-child { max-width: 540px; margin: 0; font-size: .92rem }
    .subscription-panel__offer { border-left: 1px solid var(--color-border); padding-left: var(--space-6) }
    .subscription-panel__label { color: #f1dfe0; font-size: .84rem; font-weight: 650 }
    .subscription-panel__price { margin: .4rem 0 1rem; color: #fff; font-size: clamp(2rem, 4vw, 2.8rem); font-weight: 750; letter-spacing: -.05em; line-height: 1.15 }
    .subscription-panel__price span { color: var(--color-text-muted); font-size: .85rem; font-weight: 450; letter-spacing: 0 }

    .player { aspect-ratio: 16 / 9; overflow: hidden; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: #050505; box-shadow: var(--shadow-card) }
    .player iframe, .player video { width: 100%; height: 100%; display: block; border: 0 }
    .watch-page { max-width: 1040px; margin: 0 auto }
    .watch-heading { margin-bottom: var(--space-4) }
    .watch-heading h1 { margin: 0; font-size: clamp(2.7rem, 6vw, 4.5rem) }
    .watch-synopsis { max-width: 780px; margin: 1.25rem 0 0; line-height: 1.8 }

    .surface-card, .form-panel { border: 1px solid var(--color-border); border-radius: var(--radius-md); background: linear-gradient(145deg, rgba(31, 27, 28, .98), rgba(23, 21, 22, .98)); box-shadow: 0 12px 40px rgba(0, 0, 0, .16) }
    .surface-card { padding: clamp(1.15rem, 3vw, 1.8rem) }
    .account-grid { display: grid; grid-template-columns: minmax(0, .82fr) minmax(0, 1.18fr); gap: 1rem }
    .account-profile { display: flex; align-items: center; gap: 1rem }
    .account-avatar { width: 52px; height: 52px; display: grid; flex: 0 0 auto; place-items: center; border: 1px solid rgba(229, 9, 20, .45); border-radius: 50%; background: var(--color-accent-soft); color: #ffc5c8; font-size: 1.1rem; font-weight: 700 }
    .account-profile h2 { margin: 0 0 .2rem; font-size: 1.08rem }
    .account-profile p { margin: 0; font-size: .84rem; overflow-wrap: anywhere }
    .subscription-status { grid-column: 2; grid-row: 1 / span 2 }
    .subscription-status > p { margin: 0 0 1rem; font-size: .91rem; line-height: 1.8 }
    .subscription-status form { margin: 0 }
    .account-history { grid-column: 1 / -1 }
    .status-pill { width: fit-content; display: inline-flex; align-items: center; border: 1px solid var(--color-border-strong); border-radius: 99px; padding: .2rem .6rem; background: rgba(255, 255, 255, .035); color: #e5dcdd; font-size: .72rem; font-weight: 700; line-height: 1.5; white-space: nowrap }
    .status-pill--active, .status-pill--paid, .status-pill--published { border-color: rgba(123, 212, 165, .42); background: rgba(36, 94, 62, .27); color: #a9e8c2 }
    .status-pill--pending, .status-pill--draft { border-color: rgba(241, 199, 125, .42); background: rgba(103, 75, 29, .24); color: #f2d495 }
    .status-pill--failed, .status-pill--expired, .status-pill--hidden, .status-pill--archived { border-color: rgba(255, 145, 145, .35); background: rgba(116, 42, 44, .24); color: #ffc1c1 }
    .status-pill--muted { color: var(--color-text-muted) }
    .card-heading { align-items: center; margin-bottom: 1rem }
    .card-heading h2 { display: flex; align-items: center; gap: .6rem; font-size: 1.2rem }
    .heading-count { border: 1px solid var(--color-border); border-radius: 99px; padding: .1rem .5rem; color: var(--color-text-muted); font-size: .72rem; font-weight: 600 }
    .table-scroll { width: 100%; overflow-x: auto; border-radius: var(--radius-sm); scrollbar-color: var(--color-border-strong) transparent; scrollbar-width: thin }
    .data-table { width: 100%; border-collapse: collapse; color: var(--color-text-muted); font-size: .84rem; text-align: left }
    .data-table th { padding: .8rem .75rem; border-bottom: 1px solid var(--color-border-strong); color: #ded6d7; font-size: .7rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; white-space: nowrap }
    .data-table td { padding: .9rem .75rem; border-bottom: 1px solid rgba(255, 255, 255, .07); vertical-align: middle }
    .data-table tbody tr:last-child td { border-bottom: 0 }
    .data-table tbody tr:hover { background: rgba(255, 255, 255, .025) }
    .data-table td strong { color: #f4eeee; font-weight: 650 }
    .data-table .table-empty { padding: 2rem 1rem; color: var(--color-text-muted); text-align: center }
    .table-action { text-align: right }
    .stats-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .8rem; margin-bottom: 1.5rem }
    .stat-card { min-width: 0; display: grid; gap: .25rem; border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1.1rem; background: var(--color-surface) }
    .stat-card--accent { background: radial-gradient(ellipse at 100% 0%, rgba(229, 9, 20, .16), transparent 58%), var(--color-surface) }
    .stat-card__label { color: var(--color-text-muted); font-size: .8rem }
    .stat-card strong { overflow-wrap: anywhere; color: #fff; font-size: clamp(1.35rem, 4vw, 2rem); font-weight: 750; letter-spacing: -.04em; line-height: 1.2 }
    .stat-card__detail { color: var(--color-text-subtle); font-size: .72rem }
    .admin-section { margin-top: 1rem }
    .admin-nav { display: flex; gap: .4rem; overflow-x: auto; margin: 0 0 1.2rem; padding: .35rem; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: #151314; scrollbar-width: thin }
    .admin-nav a { min-height: 42px; display: inline-flex; flex: 0 0 auto; align-items: center; border-radius: var(--radius-sm); padding: .55rem .85rem; color: var(--color-text-muted); font-size: .83rem; font-weight: 600; text-decoration: none; transition: background .18s ease, color .18s ease }
    .admin-nav a:hover, .admin-nav a.is-active { background: var(--color-accent-soft); color: #fff }
    .pagination-wrap { margin-top: 1rem; overflow-x: auto; color: var(--color-text-muted) }
    .pagination-wrap nav { display: flex; justify-content: center }
    .pagination-wrap nav > div { display: flex; gap: .35rem }
    .pagination-wrap a, .pagination-wrap span[aria-current="page"] span, .pagination-wrap span[aria-disabled="true"] span { min-width: 38px; min-height: 38px; display: inline-grid; place-items: center; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: .35rem .6rem; background: var(--color-surface); color: var(--color-text-muted); font-size: .82rem; text-decoration: none }
    .pagination-wrap span[aria-current="page"] span { border-color: var(--color-accent); background: var(--color-accent); color: #fff }

    .form-stack { display: grid; gap: 1rem }
    .form-panel { max-width: 920px; padding: clamp(1.1rem, 3vw, 2rem) }
    .form-panel__heading { padding-bottom: 1rem; border-bottom: 1px solid var(--color-border) }
    .form-panel__heading .eyebrow { margin-bottom: .35rem }
    .form-panel__heading p:last-child { margin: 0; font-size: .83rem }
    .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem }
    .form-grid__wide { grid-column: 1 / -1 }
    label { min-width: 0; display: grid; gap: .4rem; color: #e3dcdc; font-size: .84rem; font-weight: 600 }
    .field-hint { color: var(--color-text-subtle); font-size: .74rem; font-weight: 450 }
    .required-mark { color: #ff9095 }
    input:not([type="checkbox"]), select, textarea { width: 100%; min-height: 46px; border: 1px solid var(--color-border-strong); border-radius: var(--radius-sm); padding: .7rem .8rem; background: #111011; color: var(--color-text); font-size: .91rem; line-height: 1.45 }
    textarea { min-height: 110px; resize: vertical }
    input::placeholder, textarea::placeholder { color: #8e8587 }
    input:focus, select:focus, textarea:focus { border-color: #c7444a; outline: 2px solid rgba(229, 9, 20, .25); outline-offset: 1px }
    input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--color-accent) }
    .check-label { display: flex; align-items: center; gap: .65rem; min-height: 30px; font-weight: 500 }
    .check-label input { flex: 0 0 auto; margin: 0 }
    .form-actions { display: flex; justify-content: flex-end; gap: .65rem; padding-top: 1rem; border-top: 1px solid var(--color-border) }
    .form-footnote { margin: .15rem 0 0; color: var(--color-text-muted); font-size: .82rem; text-align: center }
    .form-footnote a { color: #ff9599; font-weight: 650; text-underline-offset: 3px }
    .note, .notice { display: flex; align-items: flex-start; gap: .65rem; margin: 0 0 1rem; border: 1px solid var(--color-border); border-left: 3px solid #9b8c8f; border-radius: var(--radius-sm); padding: .85rem 1rem; background: var(--color-surface); color: #f0e9e9; font-size: .88rem }
    .note.err, .notice--error { border-left-color: var(--color-danger); color: #ffd0d0 }
    .notice--draft { display: grid; gap: .15rem; border-left-color: var(--color-warning); color: #f5dcae }
    .notice--draft span { color: var(--color-text-muted) }

    .auth-layout { min-height: 570px; display: grid; grid-template-columns: minmax(0, 1fr) minmax(340px, .78fr); overflow: hidden; border: 1px solid var(--color-border); border-radius: var(--radius-lg); background: linear-gradient(125deg, #1d1719, #151314 60%); box-shadow: var(--shadow-card) }
    .auth-aside { min-height: 500px; position: relative; display: flex; flex-direction: column; justify-content: center; overflow: hidden; padding: clamp(1.5rem, 5vw, 4rem); background: radial-gradient(ellipse at 78% 60%, rgba(154, 28, 38, .25), transparent 42%), linear-gradient(145deg, rgba(43, 30, 33, .92), rgba(21, 18, 20, .65)) }
    .auth-aside h1 { position: relative; z-index: 1; margin: .25rem 0 .7rem; font-size: clamp(3.8rem, 7vw, 6rem) }
    .auth-aside p:last-of-type { position: relative; z-index: 1; max-width: 360px; margin: 0; color: #cfc5c7 }
    .auth-aside__monogram { position: absolute; right: 0; bottom: -5rem; color: rgba(255, 255, 255, .04); font: 400 22rem/.85 var(--font-display); letter-spacing: -.08em; pointer-events: none }
    .auth-card { align-self: center; padding: clamp(1.5rem, 4vw, 3rem) }
    .auth-card h2 { margin: .2rem 0 .4rem; font: 400 2.5rem/1 var(--font-display); letter-spacing: .02em }
    .auth-card__intro { margin: 0 0 1.4rem; font-size: .86rem }
    .status-page { max-width: 620px; display: grid; justify-items: start; margin: 2rem auto; border: 1px solid var(--color-border); border-radius: var(--radius-lg); padding: clamp(1.5rem, 5vw, 3rem); background: radial-gradient(ellipse at 100% 0%, rgba(229, 9, 20, .14), transparent 50%), var(--color-surface); box-shadow: var(--shadow-card) }
    .status-mark { width: 52px; height: 52px; display: grid; place-items: center; margin-bottom: 1.4rem; border: 1px solid var(--color-border-strong); border-radius: 50%; background: rgba(255, 255, 255, .04); font-size: 1.45rem; font-weight: 750 }
    .status-mark--success { border-color: rgba(123, 212, 165, .5); background: rgba(41, 107, 70, .28); color: var(--color-success) }
    .status-mark--pending { color: var(--color-warning) }
    .status-mark--failed { border-color: rgba(255, 145, 145, .4); color: var(--color-danger) }
    .status-page h1 { margin: 0 0 .7rem; font-size: clamp(2.8rem, 6vw, 4.4rem) }
    .status-page > p:not(.eyebrow):not(.status-reference) { max-width: 480px; margin: 0 0 1.2rem; line-height: 1.8 }
    .status-page form { margin: 0 }
    .status-reference { width: 100%; display: flex; flex-wrap: wrap; justify-content: space-between; gap: .4rem; margin: 1.5rem 0 0; border-top: 1px solid var(--color-border); padding-top: 1rem; color: var(--color-text-subtle); font-size: .78rem }
    .status-reference strong { color: #e8dfe0; overflow-wrap: anywhere }
    .legal-page { max-width: 820px; margin: 0 auto }
    .legal-page h1 { margin: .2rem 0 1.3rem; font-size: clamp(3rem, 7vw, 5rem) }
    .legal-copy { margin-top: 1.3rem; border-top: 1px solid var(--color-border); padding-top: 1.2rem }
    .legal-copy p { font-size: 1rem; line-height: 1.9 }

    footer { min-height: 100px; display: flex; align-items: center; justify-content: space-between; gap: 1.25rem; flex-wrap: wrap; border-top: 1px solid rgba(255, 255, 255, .08); padding: 1.5rem max(4%, calc((100% - var(--content-width)) / 2)); background: #0d0c0d }
    .footer-brand { color: var(--color-text-subtle); font-size: .8rem }
    .footer-links { display: flex; gap: .5rem 1.25rem; flex-wrap: wrap }
    footer a { color: var(--color-text-muted); font-size: .79rem; text-decoration: none; text-underline-offset: 3px }
    footer a:hover { color: #fff; text-decoration: underline }

    @media (min-width: 700px) {
      .row .film-card { flex-basis: 190px }
      .catalog-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1.7rem 1.1rem }
      .stats-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem }
    }
    @media (min-width: 1100px) {
      .catalog-grid { grid-template-columns: repeat(5, minmax(0, 1fr)) }
    }
    @media (max-width: 760px) {
      :root { --nav-height: 68px }
      .bar { gap: .35rem; padding-right: 1rem; padding-left: 1rem }
      .logo { font-size: 1.9rem }
      .bar nav { gap: .12rem }
      .bar nav > a, .bar nav button { min-height: 40px; padding: .45rem .52rem; font-size: .79rem }
      .bar nav > a.btn { min-height: 38px; padding: .45rem .65rem }
      .wrap { width: min(100% - 2rem, var(--content-width)); padding-top: calc(var(--nav-height) + 2.5rem) }
      .hero { min-height: 620px; padding-right: 1.5rem; padding-left: 1.5rem }
      .hero__poster { width: min(32vw, 220px); right: 0; opacity: .76 }
      .hero--home .hero__copy { padding-right: min(22vw, 190px) }
      .film-hero { min-height: 590px; padding-right: 1.5rem; padding-left: 1.5rem }
      .home-content { padding: 0 1rem }
      .subscription-panel { grid-template-columns: 1fr; gap: 1.4rem }
      .subscription-panel__offer { border-top: 1px solid var(--color-border); border-left: 0; padding: 1.2rem 0 0 }
      .auth-layout { grid-template-columns: 1fr; max-width: 600px; margin: 0 auto }
      .auth-aside { min-height: 250px; padding: 1.5rem }
      .auth-aside h1 { font-size: 4rem }
      .auth-aside__monogram { right: 0; bottom: -3.5rem; font-size: 14rem }
      .auth-card { padding: 1.5rem }
      .account-grid { grid-template-columns: 1fr }
      .subscription-status { grid-column: auto; grid-row: auto }
      .account-history { grid-column: auto }
    }
    @media (max-width: 540px) {
      .page-intro--split { align-items: flex-start; flex-direction: column; gap: 1rem }
      .hero { min-height: 590px; align-items: flex-end; padding: calc(var(--nav-height) + 4rem) 1.15rem 3.5rem }
      .hero::before { background-image: linear-gradient(90deg, rgba(16, 15, 16, .92), rgba(16, 15, 16, .68)), linear-gradient(0deg, #100f10 0%, rgba(16, 15, 16, .2) 70%), var(--hero-image, radial-gradient(ellipse at 72% 32%, #402329, #151214 55%)); background-position: center; background-size: cover }
      .hero__inner { min-height: 0; align-items: flex-end }
      .hero__copy, .hero--home .hero__copy { width: 100%; padding: 0 }
      .hero__poster { width: 120px; top: 1.8rem; right: .4rem; transform: rotate(4deg); opacity: .82 }
      .hero__copy .eyebrow { max-width: 65%; font-size: .63rem }
      .hero h1 { max-width: 100%; font-size: clamp(3.5rem, 17vw, 5rem) }
      .hero__description { max-width: 100%; font-size: .92rem }
      .film-hero { min-height: 580px; padding: calc(var(--nav-height) + 3.5rem) 1.15rem 3rem }
      .film-hero::before { background-image: linear-gradient(90deg, rgba(13, 11, 12, .94), rgba(13, 11, 12, .72)), linear-gradient(0deg, #100f10 0%, transparent 72%), var(--hero-image, linear-gradient(135deg, #38242b, #151214)) }
      .film-hero h1 { font-size: clamp(3.2rem, 16vw, 4.6rem) }
      .hero__actions .btn, .film-hero__actions .btn { min-height: 46px }
      .section-heading { align-items: flex-start; flex-direction: column; gap: .35rem }
      .row .film-card { flex-basis: 145px }
      .subscription-panel { margin-top: 2.5rem; padding: 1.2rem }
      .catalog-grid { gap: 1.1rem .7rem }
      .catalog-grid .film-card__title { font-size: .82rem }
      .catalog-grid .film-card__meta { font-size: .7rem }
      .catalog-grid .film-card .badge { top: .45rem; left: .45rem; padding: .12rem .42rem; font-size: .63rem }
      .catalog-grid .film-card__monogram { font-size: 4rem }
      .surface-card { padding: 1rem }
      .stats-grid { gap: .55rem }
      .stat-card { padding: .8rem }
      .stat-card__detail { font-size: .67rem }
      .form-grid { grid-template-columns: 1fr }
      .form-grid__wide { grid-column: auto }
      .form-actions { justify-content: stretch }
      .form-actions > * { flex: 1 }
      .account-history .table-scroll { overflow: visible }
      .account-history .data-table, .account-history .data-table tbody, .account-history .data-table tr, .account-history .data-table td { display: block; width: 100% }
      .account-history .data-table thead { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0, 0, 0, 0) }
      .account-history .data-table tr { margin: .6rem 0; border: 1px solid var(--color-border); border-radius: var(--radius-sm); padding: .5rem .7rem }
      .account-history .data-table td { display: flex; align-items: center; justify-content: space-between; gap: .8rem; border-bottom: 1px solid rgba(255, 255, 255, .06); padding: .55rem 0; text-align: right; overflow-wrap: anywhere }
      .account-history .data-table td::before { content: attr(data-label); color: var(--color-text-subtle); font-size: .72rem; font-weight: 600; text-align: left }
      .account-history .data-table td.table-empty { display: block; text-align: center }
      .account-history .data-table td.table-empty::before { content: none }
      .data-table { min-width: 660px }
      .status-page { margin: 0 auto }
      footer { align-items: flex-start; flex-direction: column; padding: 1.4rem 1rem }
    }
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important }
      .btn:hover, .film-card:hover .film-card__poster { transform: none }
    }
  </style>
</head>
<body>
  <a class="sr-only" href="#main-content">Lewati ke konten</a>
  <header class="bar" id="bar">
    <a class="logo" href="{{ route('home') }}">{{ config('app.name') }}</a>
    <nav aria-label="Navigasi utama">
      <a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Beranda</a>
      <a href="{{ route('catalog') }}" @if(request()->routeIs('catalog')) aria-current="page" @endif>Katalog</a>
      @auth
      @if(auth()->user()->isAdmin())<a href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.*')) aria-current="page" @endif>Admin</a>@endif
      <a href="{{ route('account') }}" @if(request()->routeIs('account')) aria-current="page" @endif>Akun</a>
      <form method="post" action="{{ route('logout') }}">@csrf<button type="submit">Keluar</button></form>
      @else
      <a href="{{ route('login') }}" @if(request()->routeIs('login')) aria-current="page" @endif>Masuk</a>
      <a class="btn btn--primary" href="{{ route('register') }}">Daftar</a>
      @endauth
    </nav>
  </header>
  @yield('hero')
  <main class="wrap @yield('wrapclass')" id="main-content" tabindex="-1">
    @if(session('ok'))<div class="note" role="status">{{ session('ok') }}</div>@endif
    @if(session('err'))<div class="note err" role="alert">{{ session('err') }}</div>@endif
    @if($errors->any())<div class="note err" role="alert">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
    @yield('content')
  </main>
  <footer>
    <span class="footer-brand">{{ config('app.name') }} <span aria-hidden="true">·</span> Film pilihan, waktu milikmu.</span>
    <nav class="footer-links" aria-label="Kebijakan">
      <a href="{{ route('legal', 'privasi') }}">Kebijakan privasi</a>
      <a href="{{ route('legal', 'ketentuan') }}">Ketentuan layanan</a>
      <a href="{{ route('legal', 'refund') }}">Pembatalan &amp; refund</a>
    </nav>
  </footer>
  <script>
    const bar = document.getElementById('bar');
    const sync = () => bar.classList.toggle('solid', scrollY > 20);
    addEventListener('scroll', sync, { passive: true });
    sync();
  </script>
</body>
</html>
