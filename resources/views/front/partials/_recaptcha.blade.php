@if(config('services.recaptcha.site_key'))
<style>
    /* Hide Google's floating badge; the required disclosure is shown inside each
       form via the _recaptcha_notice partial instead. */
    .grecaptcha-badge { visibility: hidden; }

    .recaptcha-notice {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        line-height: 1.4;
        margin-top: 15px;
    }
    .recaptcha-notice img { flex-shrink: 0; }
    .recaptcha-notice a { color: #6b7280; text-decoration: underline; }
    .recaptcha-notice a:hover { color: #044cb8; }
</style>
{{-- reCAPTCHA v3 is only needed when one of the protected forms is actually
     used. The newsletter form sits in the footer of every page, so loading the
     script on page load (or on any scroll/tap) pulled ~340 KiB and ~1.2s of
     third-party CPU onto every visit, including visitors who never touch a form.

     It is therefore loaded only on genuine form intent: focusing or typing in a
     field of a protected form. The submit handler below also forces the load and
     waits, so a submit that beats the fetch still gets a token. --}}
<script>
    (function () {
        var siteKey = @json(config('services.recaptcha.site_key'));
        var loaded = false;

        function loadRecaptcha() {
            if (loaded) return;
            loaded = true;
            var s = document.createElement('script');
            s.src = 'https://www.google.com/recaptcha/api.js?render=' + encodeURIComponent(siteKey);
            s.async = true;
            document.head.appendChild(s);
        }

        // Only arm the protected forms - not the whole window.
        function arm() {
            var forms = document.querySelectorAll('form[data-recaptcha-action]');
            for (var i = 0; i < forms.length; i++) {
                // `focusin` bubbles and covers keyboard and pointer entry alike.
                forms[i].addEventListener('focusin', loadRecaptcha, { once: true, passive: true });
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', arm);
        } else {
            arm();
        }

        // Let the submit handler force the fetch if a form is submitted before
        // any interaction has triggered it.
        window.amaLoadRecaptcha = loadRecaptcha;
    })();
</script>
<script>
    // Attaches an invisible reCAPTCHA v3 token to every <form data-recaptcha-action="...">
    // right before submit. The action name must match the `recaptcha:<action>` middleware
    // argument on the matching route (see routes/web.php) so Google's action check passes.
    document.addEventListener('DOMContentLoaded', function () {
        var siteKey = @json(config('services.recaptcha.site_key'));

        document.querySelectorAll('form[data-recaptcha-action]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (form.dataset.recaptchaVerified === 'true') {
                    return;
                }

                event.preventDefault();
                var action = form.dataset.recaptchaAction;

                // The script is fetched lazily, so it may not have arrived yet when
                // the very first interaction is the submit itself. Kick off the load
                // and poll briefly rather than submitting a tokenless request, which
                // the recaptcha middleware would reject.
                if (typeof grecaptcha === 'undefined') {
                    if (typeof window.amaLoadRecaptcha === 'function') {
                        window.amaLoadRecaptcha();
                    }

                    var waited = 0;
                    (function waitForRecaptcha() {
                        if (typeof grecaptcha !== 'undefined') {
                            runRecaptcha();
                            return;
                        }
                        // Give up after ~3s and submit anyway, so a blocked or failed
                        // CDN fetch can never leave the form permanently unusable.
                        if (waited >= 3000) {
                            form.dataset.recaptchaVerified = 'true';
                            form.submit();
                            return;
                        }
                        waited += 100;
                        setTimeout(waitForRecaptcha, 100);
                    })();
                    return;
                }

                runRecaptcha();

                function runRecaptcha() {
                grecaptcha.ready(function () {
                    grecaptcha.execute(siteKey, { action: action }).then(function (token) {
                        var input = form.querySelector('input[name="g-recaptcha-response"]');
                        if (!input) {
                            input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'g-recaptcha-response';
                            form.appendChild(input);
                        }
                        input.value = token;
                        form.dataset.recaptchaVerified = 'true';
                        form.submit();
                    });
                });
                }
            });
        });
    });
</script>
@endif
