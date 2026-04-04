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
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet" />
  <!-- Local Stylesheet -->
  <link rel="stylesheet" href="/assets/css/register.css">

</head>
<body>

  <div class="signup-card">

    <!-- Banner -->
    <div class="card-banner">
      <div class="banner-icon"><i class="bi bi-person-plus-fill"></i></div>
      <h1>Account Registration</h1>
      <p>Join us — it only takes a minute.</p>
    </div>

    <!-- Form -->
    <div class="card-body-custom">
      <form id="registrationForm" novalidate>

        <!-- Full Name -->
        <div class="field-group">
          <label for="fullName" class="form-label">Full Name</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control" id="fullName"
              placeholder="Jane Doe" required autocomplete="name" />
          </div>
          <div class="invalid-feedback d-block" id="nameError" style="display:none!important; font-size:0.78rem; color:#c0392b; margin-top:4px;"></div>
        </div>

        <!-- Phone Number -->
        <div class="field-group">
          <label for="phone" class="form-label">Phone Number</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="tel" class="form-control" id="phone"
              placeholder="(555) 000-0000" required autocomplete="tel" />
          </div>
          <p id="phoneFormatHint" style="font-size:0.76rem; color:#9492a8; margin:5px 0 0;">Format: (555) 555-5555</p>
        </div>

        <!-- Address -->
        <div class="field-group">
          <label for="address" class="form-label">Address</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
            <input type="text" class="form-control" id="address"
              placeholder="123 Main St, City, State" required autocomplete="street-address" />
          </div>
        </div>

         <!-- Address 2 -->
        <div class="field-group">
          <label class="form-label">Address Line 2</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-geo"></i></span>
            <input type="text" class="form-control" />
          </div>
        </div>

        <!-- City -->
        <div class="field-group">
          <label class="form-label">City</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-building"></i></span>
            <input type="text" class="form-control" required />
          </div>
        </div>

        <!-- State -->
        <div class="field-group">
          <label class="form-label">State</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-map"></i></span>
            <select class="form-select" required>
              <option value="">Select State</option>
              <option value="AL">Alabama</option>
              <option value="AK">Alaska</option>
              <option value="AZ">Arizona</option>
              <option value="AR">Arkansas</option>
              <option value="CA">California</option>
              <option value="CO">Colorado</option>
              <option value="CT">Connecticut</option>
              <option value="DE">Delaware</option>
              <option value="FL">Florida</option>
              <option value="GA">Georgia</option>
              <option value="HI">Hawaii</option>
              <option value="ID">Idaho</option>
              <option value="IL">Illinois</option>
              <option value="IN">Indiana</option>
              <option value="IA">Iowa</option>
              <option value="KS">Kansas</option>
              <option value="KY">Kentucky</option>
              <option value="LA">Louisiana</option>
              <option value="ME">Maine</option>
              <option value="MD">Maryland</option>
              <option value="MA">Massachusetts</option>
              <option value="MI">Michigan</option>
              <option value="MN">Minnesota</option>
              <option value="MS">Mississippi</option>
              <option value="MO">Missouri</option>
              <option value="MT">Montana</option>
              <option value="NE">Nebraska</option>
              <option value="NV">Nevada</option>
              <option value="NH">New Hampshire</option>
              <option value="NJ">New Jersey</option>
              <option value="NM">New Mexico</option>
              <option value="NY">New York</option>
              <option value="NC">North Carolina</option>
              <option value="ND">North Dakota</option>
              <option value="OH">Ohio</option>
              <option value="OK">Oklahoma</option>
              <option value="OR">Oregon</option>
              <option value="PA">Pennsylvania</option>
              <option value="RI">Rhode Island</option>
              <option value="SC">South Carolina</option>
              <option value="SD">South Dakota</option>
              <option value="TN">Tennessee</option>
              <option value="TX">Texas</option>
              <option value="UT">Utah</option>
              <option value="VT">Vermont</option>
              <option value="VA">Virginia</option>
              <option value="WA">Washington</option>
              <option value="WV">West Virginia</option>
              <option value="WI">Wisconsin</option>
              <option value="WY">Wyoming</option>
            </select>
          </div>
        </div>

        <!-- Zipcode -->
        <div class="field-group">
          <label class="form-label">Zip Code</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-mailbox"></i></span>
            <input type="text" class="form-control" id="zipcode" maxlength="5" inputmode="numeric" pattern="[0-9]*" required />
          </div>
        </div>

        <hr class="form-divider" />

        <!-- Username -->
        <div class="field-group">
          <label for="username" class="form-label">Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-at"></i></span>
            <input type="text" class="form-control" id="username"
              placeholder="janedoe92" required autocomplete="username" />
          </div>
        </div>

        <!-- Password -->
        <div class="field-group">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" id="password"
              placeholder="At least 8 characters" required autocomplete="new-password"
              oninput="checkStrength(this.value)" />
            <button type="button" class="btn-pwd-toggle" onclick="togglePwd('password', 'eyeIcon1')" aria-label="Show password">
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
            <input type="password" class="form-control" id="confirmPassword"
              placeholder="Repeat password" required autocomplete="new-password" />
            <button type="button" class="btn-pwd-toggle" onclick="togglePwd('confirmPassword', 'eyeIcon2')" aria-label="Show password">
              <i class="bi bi-eye" id="eyeIcon2"></i>
            </button>
          </div>
        </div>

        <!-- Terms checkbox -->
        <div class="form-check mb-3" style="padding-left:1.6rem;">
          <input class="form-check-input" type="checkbox" id="agreeTerms" required
            style="border-radius:5px; border:1.5px solid #e8e6f0; cursor:pointer;" />
          <label class="form-check-label" for="agreeTerms"
            style="font-size:0.83rem; color:#9492a8; cursor:pointer;">
            I agree to the <a href="#" style="color:#0f3460; font-weight:500;">Terms of Service</a>
            &amp; <a href="#" style="color:#0f3460; font-weight:500;">Privacy Policy</a>
          </label>
        </div>

        <button type="submit" class="btn-signup">
          <i class="bi bi-check2-circle me-2"></i>Create My Account
        </button>

      </form>

      <p class="signin-link">
        Already have an account? <a href="#">Sign in</a>
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
