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
            <p>Step 2 of 2</p>
        </div>

        <!-- Form -->
        <div class="card-body-custom">
            <form id="registrationForm" novalidate>

                <!-- Address 1 -->
                <div class="field-group">
                    <label class="form-label">Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" class="form-control" required />
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