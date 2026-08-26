@extends('front.layouts.app2')

@section('title', 'Privacy Policy - Authentic Morocco Adventures')

@section('meta_description', 'How Authentic Morocco Adventures collects, uses and protects your personal data when you book a tour, contact us or subscribe to our newsletter.')

@section('og_title', 'Privacy Policy - Authentic Morocco Adventures')
@section('og_description', 'How Authentic Morocco Adventures collects, uses and protects your personal data when you book a tour, contact us or subscribe to our newsletter.')

@section('content')

<section data-anim="fade" class="mt-header pt-30">
  <div class="container">
    <div class="breadcrumbs mb-30 md:mb-15">
      <span class="breadcrumbs__item">
        <a href="{{ route('home') }}">Home</a>
      </span>
      <span>></span>
      <span class="breadcrumbs__item">
        <span>Privacy Policy</span>
      </span>
    </div>

    <h1 class="text-30">Privacy Policy</h1>

    <p class="mt-10 text-15">
      Authentic Morocco Adventures respects your privacy. This page explains what personal
      information we collect when you use our website, why we collect it, and the choices
      you have. If you have any question about this policy, please
      <a href="{{ route('front.contact') }}">contact us</a>.
    </p>
  </div>
</section>

<section data-anim="slide-up delay-2" class="layout-pt-md layout-pb-lg">
  <div class="container">
    <div class="tabs -terms js-tabs">
      <div class="row y-gap-30">
        <div class="col-lg-3">
          <div class="tabs__controls row y-gap-10 js-tabs-controls">
            <div class="col-12">
              <button class="tabs__button relative pl-20 js-tabs-button is-tab-el-active" data-tab-target=".-tab-item-1">
                Information We Collect
              </button>
            </div>
            <div class="col-12">
              <button class="tabs__button relative pl-20 js-tabs-button" data-tab-target=".-tab-item-2">
                How We Use It
              </button>
            </div>
            <div class="col-12">
              <button class="tabs__button relative pl-20 js-tabs-button" data-tab-target=".-tab-item-3">
                Sharing &amp; Third Parties
              </button>
            </div>
            <div class="col-12">
              <button class="tabs__button relative pl-20 js-tabs-button" data-tab-target=".-tab-item-4">
                Cookies &amp; Local Storage
              </button>
            </div>
            <div class="col-12">
              <button class="tabs__button relative pl-20 js-tabs-button" data-tab-target=".-tab-item-5">
                Your Rights
              </button>
            </div>
          </div>
        </div>

        <div class="col-lg-9">
          <div class="tabs__content js-tabs-content">

            {{-- Tab 1 --}}
            <div class="tabs__pane -tab-item-1 is-tab-el-active">
              <h2 class="text-20 fw-500">Information We Collect</h2>
              <p class="mt-10">
                We only collect the personal information you choose to give us. When you request
                a reservation for a tour, activity or trek, we collect the details needed to
                organise it &mdash; typically your name, email address, phone number, travel dates
                and the number of travellers, together with any notes you add to your request.
                When you use our contact form we collect your name, email address and message.
                When you subscribe to our newsletter we collect your email address. We do not ask
                for, and do not store, your card or bank details through this website.
              </p>
            </div>

            {{-- Tab 2 --}}
            <div class="tabs__pane -tab-item-2">
              <h2 class="text-20 fw-500">How We Use It</h2>
              <p class="mt-10">
                We use your information to answer your enquiry, to prepare and confirm your
                booking, to arrange the practical details of your tour, and to contact you about
                your trip. If you subscribed to our newsletter we may also send you occasional
                travel news and offers &mdash; you can unsubscribe at any time. We do not use your
                personal information for automated decision-making, and we do not sell it.
              </p>
            </div>

            {{-- Tab 3 --}}
            <div class="tabs__pane -tab-item-3">
              <h2 class="text-20 fw-500">Sharing &amp; Third Parties</h2>
              <p class="mt-10">
                Your information is never sold. We share it only where it is necessary to deliver
                the service you asked for &mdash; for example with the guide, driver or accommodation
                involved in your own booking &mdash; or where we are required to do so by law.
                Our website uses Google reCAPTCHA to protect our forms from spam and abuse; your
                use of it is subject to the
                <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Google Privacy Policy</a>
                and
                <a href="https://policies.google.com/terms" target="_blank" rel="noopener">Terms of Service</a>.
              </p>
            </div>

            {{-- Tab 4 --}}
            <div class="tabs__pane -tab-item-4">
              <h2 class="text-20 fw-500">Cookies &amp; Local Storage</h2>
              <p class="mt-10">
                Our website uses a small number of cookies that are necessary for it to work,
                including a session cookie and a security token that protects our forms. Your
                wishlist of saved tours is kept in your own browser using local storage &mdash; it
                stays on your device, is not sent to us, and you can clear it at any time by
                removing the saved items or clearing your browser data. You can also block or
                delete cookies in your browser settings, though some parts of the site may then
                not work as intended.
              </p>
            </div>

            {{-- Tab 5 --}}
            <div class="tabs__pane -tab-item-5">
              <h2 class="text-20 fw-500">Your Rights</h2>
              <p class="mt-10">
                You may ask us at any time for a copy of the personal information we hold about
                you, ask us to correct it if it is wrong, or ask us to delete it where we are not
                required to keep it. You may also unsubscribe from our newsletter at any time. To
                make any of these requests, write to us at
                <a href="mailto:authenticmoroccoadventures@gmail.com">authenticmoroccoadventures@gmail.com</a>
                and we will respond as soon as we can. See also our
                <a href="{{ route('front.terms') }}">Terms and Conditions</a>.
              </p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
