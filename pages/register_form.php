<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Account Registration</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap"
    rel="stylesheet" />
  <!-- Local Stylesheet -->
  <link rel="stylesheet" href="/assets/css/register.css">

</head>

<body>

  <div class="signup-card">

    <!-- Banner -->
    <div class="card-banner">
      <div class="banner-icon"><i class="bi bi-person-plus-fill"></i></div>
      <h1>Account Registration</h1>
      <p>STEP 1 OF 2</p>
    </div>

    <!-- Form -->
    <div class="card-body-custom">

      <form id="registrationForm" method="post" novalidate>

        <!-- Full Name -->
        <div class="field-group">
          <label for="fullName" class="form-label">Full Name</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Jane Doe" required autocomplete="name" />
          </div>
          <div class="invalid-feedback d-block" id="nameError"
            style="display:none!important; font-size:0.78rem; color:#c0392b; margin-top:4px;"></div>
        </div>

        <!-- Phone Number -->
        <div class="field-group">
          <label for="phone" class="form-label">Phone Number</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="tel" class="form-control" id="phone" name="phone" placeholder="(555) 000-0000" required
              autocomplete="tel" />
          </div>
        </div>


        <!--hr class="form-divider" /-->

        <!-- Email Address -->
        <div class="field-group">
          <label for="email" class="form-label">Email Address</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-at"></i></span>
            <input type="text" class="form-control" id="email" name="email" placeholder="email@domain.com" required
              autocomplete="email" />
          </div>
        </div>

        <!-- Password -->
        <div class="field-group">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="password" name="password" placeholder="At least 8 characters" required
              autocomplete="new-password" oninput="updateStrength(this.value)" />
            <button type="button" class="btn-pwd-toggle" onclick="togglePwd('password', 'eyeIcon1')"
              aria-label="Show password">
              <i class="bi bi-eye" id="eyeIcon1"></i>
            </button>
          </div>
          <!-- Strength indicator -->
          <div class="strength-bar-wrap">
            <div class="strength-seg" id="s1"></div>
            <div class="strength-seg" id="s2"></div>
            <div class="strength-seg" id="s3"></div>
            <div class="strength-seg" id="s4"></div>
          </div>
          <p class="strength-label" id="strengthLabel">Enter a password</p>
        </div>

        <!-- Confirm Password -->
        <div class="field-group" style="margin-bottom:1.5rem;">
          <label for="confirmPassword" class="form-label">Confirm Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Repeat password" required
              autocomplete="new-password" />
            <button type="button" class="btn-pwd-toggle" onclick="togglePwd('confirmPassword', 'eyeIcon2')"
              aria-label="Show password">
              <i class="bi bi-eye" id="eyeIcon2"></i>
            </button>
          </div>
        </div>

        <!-- Terms checkbox -->
        <div class="form-check mb-3" style="padding-left:1.6rem;">
          <input class="form-check-input" type="checkbox" id="agreeTerms" name="agreeTerms" required
            style="border-radius:5px; border:1.5px solid #e8e6f0; cursor:pointer;" />
          <label class="form-check-label" for="agreeTerms" style="font-size:0.83rem; color:#9492a8; cursor:pointer;">
            I agree to the <a href="#" style="color:#0f3460; font-weight:500;">Terms of Service</a>
            &amp; <a href="#" style="color:#0f3460; font-weight:500;">Privacy Policy</a>
          </label>
        </div>

        <div class="d-print-block" id="registrationResults"></div>

        <button type="submit" id="registration-submit" class="btn-signup">
          <i class="bi bi-check2-circle me-2"></i>Create My Account
        </button>

      </form>

      <p class="signin-link">
        Already have an account? <a href="/login">Sign in</a>
      </p>
    </div>

  </div>

  <!-- jQuery -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Local JS -->
  <script src="/js/register.js"></script>

</body>

</html>