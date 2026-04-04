
/* ── Password visibility toggle ── */
function togglePwd(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

/* ── Password strength ── */
const segs   = [document.getElementById('s1'), document.getElementById('s2'),
document.getElementById('s3'), document.getElementById('s4')];
const colors = ['#e74c3c', '#e67e22', '#f1c40f', '#27ae60'];
const labels = ['Weak', 'Fair', 'Good', 'Strong'];

function checkStrength(val) {
    let score = 0;
    if (val.length >= 8)              score++;
    if (/[A-Z]/.test(val))            score++;
    if (/[0-9]/.test(val))            score++;
    if (/[^A-Za-z0-9]/.test(val))    score++;

    segs.forEach((s, i) => {
        s.style.background = i < score ? colors[score - 1] : '#e8e6f0';
    });

    const lbl = document.getElementById('strengthLabel');
    lbl.textContent = val.length === 0
    ? 'Enter a password'
    : labels[score - 1] || 'Weak';
    lbl.style.color = val.length === 0 ? '#9492a8' : colors[score - 1];
}

/* ── Form validation ── */
document.getElementById('registrationForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const pwd  = document.getElementById('password').value;
    const cpwd = document.getElementById('confirmPassword').value;
    let valid  = true;

    /* Clear previous errors */
    document.querySelectorAll('.err-msg').forEach(el => el.remove());

    /* Password match */
    if (pwd !== cpwd) {
        showError('confirmPassword', 'Passwords do not match.');
        valid = false;
    }

    /* Phone: must be 10 digits */
    const phoneDigits = document.getElementById('phone').value.replace(/\D/g, '');
    if (phoneDigits.length !== 10) {
        showError('phone', 'Please enter a complete 10-digit phone number.');
        document.getElementById('phone').closest('.input-group').style.borderColor = '#e74c3c';
        valid = false;
    }

    /* Other required fields (skip phone — already checked) */
    ['fullName', 'address', 'username', 'password', 'confirmPassword'].forEach(id => {
        const el = document.getElementById(id);
        if (!el.value.trim()) {
            showError(id, 'This field is required.');
            valid = false;
        }
    });

    /* Terms */
    if (!document.getElementById('agreeTerms').checked) {
        alert('Please accept the Terms of Service to continue.');
        valid = false;
    }

    if (valid) {
        const btn = document.querySelector('.btn-signup');
        btn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Account Created!';
        btn.style.background = '#27ae60';
        btn.disabled = true;
    }
});

function showError(inputId, msg) {
    const wrap = document.getElementById(inputId).closest('.input-group');
    const err  = document.createElement('p');
    err.className = 'err-msg';
    err.style.cssText = 'font-size:0.76rem;color:#c0392b;margin:4px 0 0;';
    err.textContent = msg;
    wrap.insertAdjacentElement('afterend', err);
}

/* ── jQuery phone number forced formatter ── */
$(function () {

    /* Format digits into (XXX) XXX-XXXX as the user types */
    $('#phone').on('input keydown', function (e) {
        const $input = $(this);

        /* Allow: backspace, delete, tab, escape, arrows, home, end */
        const nav = [8, 9, 27, 46, 37, 38, 39, 40, 35, 36];
        if (e.type === 'keydown' && nav.includes(e.which)) return;

        /* Strip everything except digits */
        let digits = $input.val().replace(/\D/g, '').slice(0, 10);

        /* Build formatted string progressively */
        let formatted = '';
        if (digits.length === 0) {
            formatted = '';
        } else if (digits.length <= 3) {
            formatted = '(' + digits;
        } else if (digits.length <= 6) {
            formatted = '(' + digits.slice(0, 3) + ') ' + digits.slice(3);
        } else {
            formatted = '(' + digits.slice(0, 3) + ') ' + digits.slice(3, 6) + '-' + digits.slice(6);
        }

        $input.val(formatted);

        /* Live feedback styling */
        if (digits.length === 10) {
            $input.closest('.input-group')
            .css('border-color', '#27ae60')
            .find('.input-group-text').css('color', '#27ae60');
            $('#phoneFormatHint').text('Looks good!').css('color', '#27ae60');
        } else if (digits.length > 0) {
            $input.closest('.input-group')
            .css('border-color', '')
            .find('.input-group-text').css('color', '');
            const remaining = 10 - digits.length;
            $('#phoneFormatHint').text(remaining + ' digit' + (remaining !== 1 ? 's' : '') + ' remaining').css('color', '#9492a8');
        } else {
            $input.closest('.input-group').css('border-color', '');
            $('#phoneFormatHint').text('Format: (555) 555-5555').css('color', '#9492a8');
        }
    });

    /* Prevent non-numeric key entry (except control keys) */
    $('#phone').on('keypress', function (e) {
        if (e.which < 48 || e.which > 57) e.preventDefault();
    });

        /* Reset border on blur if empty */
        $('#phone').on('blur', function () {
            const digits = $(this).val().replace(/\D/g, '');
            if (digits.length === 0) {
                $(this).closest('.input-group').css('border-color', '');
                $('#phoneFormatHint').text('Format: (555) 555-5555').css('color', '#9492a8');
            } else if (digits.length < 10) {
                $(this).closest('.input-group').css('border-color', '#e74c3c');
                $('#phoneFormatHint').text('Please enter a complete 10-digit number.').css('color', '#c0392b');
            }
        });

        /* Restore default border on focus */
        $('#phone').on('focus', function () {
            const digits = $(this).val().replace(/\D/g, '');
            if (digits.length < 10) {
                $(this).closest('.input-group').css('border-color', '');
            }
        });
});
