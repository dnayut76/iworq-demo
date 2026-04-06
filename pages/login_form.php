<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- Google Fonts: same Playfair Display + DM Sans pairing -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/assets/css/login.css">
</head>

<body>

  <div class="login-card">

    <!-- Banner -->
    <div class="card-banner">
      <div class="banner-ring"></div>
      <div class="banner-icon"><i class="bi bi-shield-lock-fill"></i></div>
      <h1>Welcome <em>back.</em></h1>
      <p>Sign in to continue to your account</p>
    </div>

    <!-- Form -->
    <div class="card-body-custom">
      <form id="loginForm" method="post" action="#" novalidate>

        <!-- Email / Username -->
        <div class="field-group">
          <label for="email" class="form-label">Email Address</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-at"></i></span>
            <input
              type="text"
              class="form-control"
              id="email"
              name="email"
              placeholder="email@domain.com"
              required
              autocomplete="email"
              autofocus
            />
          </div>
        </div>

        <!-- Password -->
        <div class="field-group" style="margin-bottom: 0.6rem;">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input
              type="password"
              class="form-control"
              id="password"
              name="password"
              placeholder="Your password"
              required
              autocomplete="current-password"
            />
            <button
              type="button"
              class="btn-pwd-toggle"
              onclick="togglePwd('password', 'eyeIcon')"
              aria-label="Show password"
            >
              <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
          </div>
        </div>

        <!-- Remember me + Forgot password -->
        <div class="form-meta">
          <div class="form-check" style="padding-left:1.6rem;">
            <input
              class="form-check-input"
              type="checkbox"
              id="rememberMe"
              name="rememberMe"
              style="border-radius:5px; border:1.5px solid #e8e6f0; cursor:pointer;"
            />
            <label class="form-check-label" for="rememberMe">Remember me</label>
          </div>
          <a href="#" class="forgot-link">Forgot password?</a>
        </div>

        <button type="submit" id="login-submit" class="btn-signin">
          <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
        </button>

      </form>

      <div id="loginResults"></div>

      <div class="or-divider">or</div>

      <p class="register-link">
        Don't have an account? <a href="/register">Create one</a>
      </p>
    </div>

  </div>

  <!-- jQuery -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Local JS -->
  <script src="/js/login.js"></script>

</body>
</html>
