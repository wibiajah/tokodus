<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-left">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="navbar-logo">
            <h1 class="navbar-title">Tokodus</h1>
        </div>

        <div class="navbar-center">
            <input type="text" placeholder="Cari produk..." class="navbar-search">
        </div>

        <div class="navbar-right">
            @auth
                <a href="{{ route('profile.show') }}" class="navbar-link">Profil</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="navbar-btn-logout">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="navbar-link">Login</a>
                <a href="{{ route('register') }}" class="navbar-btn">Register</a>
            @endauth
        </div>
    </div>
</nav>