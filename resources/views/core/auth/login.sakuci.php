@extends('layouts.app')

@section('title', 'Login')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,600;1,500&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    :root {
        --pk-bg: #fff0f3;
        --pk-card: #fffafb;
        --pk-soft: #ffd6e0;
        --pk-rose: #f4a7b9;
        --pk-deep: #d9687f;
        --pk-ink: #6b2a3f;
        --pk-line: #f7c8d4;
    }

    body { background: var(--pk-bg) !important; margin: 0; }

    .login-wrap, .login-wrap * { box-sizing: border-box; }
    .login-wrap {
        position: relative; z-index: 1; overflow: hidden;
        min-height: 80vh; display: flex; align-items: center; justify-content: center;
        padding: 3rem 1rem; font-family: 'DM Sans', sans-serif; color: var(--pk-ink);
    }

    .login-card {
        position: relative; width: 100%; max-width: 440px;
        background: var(--pk-card);
        border: 2px dashed var(--pk-rose);
        border-radius: 18px 18px 18px 4px;
        padding: 2.25rem 2rem 2rem;
    }
    .login-card h1 { font-family: 'Fraunces', serif; font-weight: 600; font-size: 2.2rem; margin: 0 0 .35rem; letter-spacing: -.5px; }
    .login-card .demo { color: var(--pk-deep); font-size: .9rem; margin: 0 0 1.75rem; }
    .login-card code {
        background: var(--pk-soft); color: var(--pk-ink); border-radius: 999px;
        padding: .1rem .6rem; font-size: .85rem; font-weight: 700;
    }

    .grup { margin-bottom: 1.1rem; }
    .grup label { display: block; font-weight: 700; font-size: .9rem; margin-bottom: .35rem; }
    .isian {
        width: 100%; font-family: inherit; font-size: 1rem; color: var(--pk-ink);
        background: #fff; border: 2px solid var(--pk-line); border-radius: 999px;
        padding: .6rem 1.1rem; transition: border-color .15s, box-shadow .15s;
    }
    .isian:focus { outline: none; border-color: var(--pk-deep); box-shadow: 0 0 0 4px var(--pk-soft); }
    .isian.salah { border-color: var(--pk-deep); }
    .galat { color: var(--pk-deep); font-size: .85rem; margin: .35rem 0 0 1rem; font-weight: 500; }

    .btn-pink {
        display: block; width: 100%; cursor: pointer; font-family: inherit; font-size: 1rem;
        background: var(--pk-deep); color: #fff; border: 0; border-radius: 999px;
        padding: .7rem 1.4rem; font-weight: 700; margin-top: 1.5rem;
        box-shadow: 0 4px 0 #b84f65; transition: transform .1s, box-shadow .1s;
    }
    .btn-pink:hover { transform: translateY(2px); box-shadow: 0 2px 0 #b84f65; }
    .btn-pink:focus-visible { outline: 3px solid var(--pk-ink); outline-offset: 2px; }

    .daftar { text-align: center; font-size: .9rem; color: var(--pk-deep); margin: 1.25rem 0 0; }
    .daftar a { color: var(--pk-ink); font-weight: 700; }

    /* Hiasan bunga sakura di sudut kartu */
    .bunga { position: absolute; pointer-events: none; z-index: 2; height: auto; }
    .bunga.b1 { width: 96px; top: -40px; right: -34px; transform: rotate(15deg); }
    .bunga.b2 { width: 52px; top: 42px; right: -46px; transform: rotate(-20deg); }
    .bunga.b3 { width: 76px; bottom: -34px; left: -32px; transform: rotate(-10deg); }
    .bunga.b4 { width: 40px; bottom: 56px; left: -40px; transform: rotate(30deg); }

    /* Kelopak jatuh pelan */
    .kelopak {
        position: fixed; top: -30px; width: 14px; height: 14px; z-index: 0; pointer-events: none; opacity: .8;
        background: linear-gradient(135deg, var(--pk-rose), var(--pk-soft));
        border-radius: 150% 0 150% 0;
        animation: jatuh 14s linear infinite;
    }
    .kelopak:nth-child(1) { left: 5%;  animation-duration: 13s; animation-delay: -2s; }
    .kelopak:nth-child(2) { left: 16%; animation-duration: 17s; animation-delay: -9s;  width: 10px; height: 10px; }
    .kelopak:nth-child(3) { left: 29%; animation-duration: 12s; animation-delay: -5s; }
    .kelopak:nth-child(4) { left: 43%; animation-duration: 16s; animation-delay: -12s; width: 12px; height: 12px; }
    .kelopak:nth-child(5) { left: 58%; animation-duration: 14s; animation-delay: -7s; }
    .kelopak:nth-child(6) { left: 70%; animation-duration: 18s; animation-delay: -1s; width: 10px; height: 10px; }
    .kelopak:nth-child(7) { left: 83%; animation-duration: 12s; animation-delay: -10s; }
    .kelopak:nth-child(8) { left: 94%; animation-duration: 15s; animation-delay: -4s; width: 12px; height: 12px; }
    @keyframes jatuh {
        0%   { transform: translate3d(0, -30px, 0) rotate(0deg); }
        50%  { transform: translate3d(40px, 55vh, 0) rotate(180deg); }
        100% { transform: translate3d(-20px, 110vh, 0) rotate(360deg); }
    }
    @media (prefers-reduced-motion: reduce) { .kelopak { animation: none; opacity: 0; } }
</style>

{{-- Bentuk bunga sakura (dipakai berulang) --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <symbol id="sakura" viewBox="-36 -36 72 72">
        <g fill="#ffc9d7" stroke="#e58aa3" stroke-width="1" stroke-linejoin="round">
            <path d="M0,0 C-14,-6 -15,-28 -6,-33 L0,-28 L6,-33 C15,-28 14,-6 0,0 Z"/>
            <path d="M0,0 C-14,-6 -15,-28 -6,-33 L0,-28 L6,-33 C15,-28 14,-6 0,0 Z" transform="rotate(72)"/>
            <path d="M0,0 C-14,-6 -15,-28 -6,-33 L0,-28 L6,-33 C15,-28 14,-6 0,0 Z" transform="rotate(144)"/>
            <path d="M0,0 C-14,-6 -15,-28 -6,-33 L0,-28 L6,-33 C15,-28 14,-6 0,0 Z" transform="rotate(216)"/>
            <path d="M0,0 C-14,-6 -15,-28 -6,-33 L0,-28 L6,-33 C15,-28 14,-6 0,0 Z" transform="rotate(288)"/>
        </g>
        <g stroke="#d9687f" stroke-width="1" stroke-linecap="round">
            <line x1="0" y1="0" x2="6.5" y2="-8.9"/>
            <line x1="0" y1="0" x2="10.5" y2="3.4"/>
            <line x1="0" y1="0" x2="0" y2="11"/>
            <line x1="0" y1="0" x2="-10.5" y2="3.4"/>
            <line x1="0" y1="0" x2="-6.5" y2="-8.9"/>
        </g>
        <g fill="#d9687f">
            <circle cx="6.5" cy="-8.9" r="1.6"/>
            <circle cx="10.5" cy="3.4" r="1.6"/>
            <circle cx="0" cy="11" r="1.6"/>
            <circle cx="-10.5" cy="3.4" r="1.6"/>
            <circle cx="-6.5" cy="-8.9" r="1.6"/>
            <circle cx="0" cy="0" r="3"/>
        </g>
    </symbol>
</svg>

<span class="kelopak"></span><span class="kelopak"></span><span class="kelopak"></span><span class="kelopak"></span>
<span class="kelopak"></span><span class="kelopak"></span><span class="kelopak"></span><span class="kelopak"></span>

<div class="login-wrap">
    <div class="login-card">

        <svg class="bunga b1" viewBox="-36 -36 72 72" aria-hidden="true"><use href="#sakura"/></svg>
        <svg class="bunga b2" viewBox="-36 -36 72 72" aria-hidden="true"><use href="#sakura"/></svg>
        <svg class="bunga b3" viewBox="-36 -36 72 72" aria-hidden="true"><use href="#sakura"/></svg>
        <svg class="bunga b4" viewBox="-36 -36 72 72" aria-hidden="true"><use href="#sakura"/></svg>

        <h1>Masuk</h1>
        <p class="demo">Akun demo: admin &mdash; password <code>rahasia123</code></p>

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <div class="grup">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" class="isian {{ errors()->has('username') ? 'salah' : '' }}" autofocus>
                @error('username') <div class="galat">{{ $message }}</div> @enderror
            </div>

            <div class="grup">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="isian {{ errors()->has('password') ? 'salah' : '' }}">
                @error('password') <div class="galat">{{ $message }}</div> @enderror
            </div>

            <button class="btn-pink" type="submit">Login</button>
        </form>

        @php
            $canRegister = false;
            try {
                $canRegister = \App\Models\Role::where('can_register', 1)->exists();
            } catch (\Throwable $e) {
                $canRegister = false;
            }
        @endphp
        @if ($canRegister)
            <p class="daftar">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>.</p>
        @endif
    </div>
</div>

@endsection
