<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telkom Paycoll - Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container">
        <div class="branding">
            <img src="{{ asset('img/logo_telkom.png') }}" alt="Telkom Indonesia Logo" class="logo">
            <h2>PAYCOLL</h2>
            <h3>Telkom Indonesia</h3>
        </div>

        <div class="form-box">
            <div class="form-header">
                <button type="button" class="role-btn active" data-role="Admin">Admin</button>
                <button type="button" class="role-btn" data-role="Agent">Agent</button>
            </div>

            <form id="loginForm" class="form active">
                <h2 class="login-title">Log In</h2>
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" placeholder="Enter username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" placeholder="Enter password" required>
                        <span class="toggle-password" data-target="password">
                            <img src="{{ asset('img/eye-close-svgrepo-com.svg') }}" alt="show/hide">
                        </span>
                    </div>
                </div>
                <div class="options">
                    <label><input type="checkbox"> Ingat saya</label>
                    <a href="#">Lupa password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <p class="toggle-link">Belum punya akun? <span onclick="showRegister()">Daftar</span></p>
            </form>

            <form id="registerForm" class="form">
                <h2 class="login-title">Register</h2>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <label for="reg-username">Username</label>
                    <input type="text" id="reg-username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <label for="reg-password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="reg-password" placeholder="Password" required>
                        <span class="toggle-password" data-target="reg-password">
                            <img src="{{ asset('img/eye-close-svgrepo-com.svg') }}" alt="show/hide">
                        </span>
                    </div>
                </div>
                <div class="input-group">
                    <label for="confirm-password">Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="confirm-password" placeholder="Confirm Password" required>
                        <span class="toggle-password" data-target="confirm-password">
                            <img src="{{ asset('img/eye-close-svgrepo-com.svg') }}" alt="show/hide">
                        </span>
                    </div>
                </div>
                <div class="input-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" id="fullname" placeholder="Nama Lengkap" required>
                </div>
                <div class="input-group">
                    <label for="phone">No. HP</label>
                    <div class="phone-input">
                        <span class="prefix">+62</span>
                        <input type="tel" id="phone" placeholder="8123456789" required inputmode="numeric" pattern="[0-9]*">
                    </div>
                </div>
                <button type="submit" class="btn">Daftar</button>
                <p class="toggle-link">Sudah punya akun? <span onclick="showLogin()">Login</span></p>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/regist_login.js') }}"></script>
</body>
</html>
