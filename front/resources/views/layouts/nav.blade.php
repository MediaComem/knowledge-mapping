  <nav id="js-main-nav" class="main-nav" role="navigation">
    <a href="/" class="main-nav__title nav-logo"><img src="/img/km-logo.svg" alt=""></a>
    <ul class="main-nav__list">
      <li class="main-nav__item">
        <a href="/papers" class="main-nav__link {{ (request()->is('papers')) ? 'active' : '' }}">Papers</a>
      </li>
      <li class="main-nav__item">
        <a href="/authors" class="main-nav__link {{ (request()->is('authors')) ? 'active' : '' }}">Authors</a>
      </li>
      <li class="main-nav__item">
        <a href="/search" class="main-nav__link {{ (request()->is('search')) ? 'active' : '' }}">Search</a>
      </li>
    </ul>
    @if(Route::is('papers') )
      <div id="papers-info">
        <div class="papers-info__close-container">
            <button class="papers-info__close-container__close-current" hidden>
                <svg stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <h5>Current selection</h5>
        <small>Topic</small><br><b><span id="selected-topic"></span></b><br>
        <small>Term</small><br><b><span id="selected-term"></span></b><br>
      </div>
    @endif
    <!-- /menu one -->
    <ul class="main-nav__list bottom">
      <li class="main-nav__item">
        <a href="/introduction" class="main-nav__link {{ (request()->is('introduction')) ? 'active' : '' }}">Introduction</a>
      </li>
      <li class="main-nav__item">
        <a href="/method" class="main-nav__link {{ (request()->is('method')) ? 'active' : '' }}">Method</a>
      </li>
      <li class="main-nav__item">
        <a href="/credits" class="main-nav__link {{ (request()->is('credits')) ? 'active' : '' }}">Credits</a>
      </li>
    </ul>
  </nav>
  <!-- /main-nav -->
