@extends('layouts.app')
@section('pageTitle', 'Credits')

@section('content')

<header class="main-header">
    <span class="main-header__left"></span>
    <span id="js-menu-button" class="menu-button">
        <button id="menu-toggle" class="menu-button__menu">Menu</button>
        <button id="menu-close" class="menu-button__close">Close</button>
    </span>
    <div id="back-menu-overlay"></div>
  </header>
  <!-- /header -->

  <section class="page-static">
    <h1 class="page-static__title">Credits</h1>
    <div class="page-static__content">
    <div>
        <b>KM</b> project (for Knowledge Mapping) is initiated by <a href="https://www.rivier-consulting.com/" alt="Rivier Consulting Website" target="_blank">Prof. Laurent Rivier</a>, designer <a href="https://www.odoma.ch/about" alt="Odoma website about page" target="_blank">Laurent Bolli</a> and data scientist <a href="https://www.odoma.ch/about" alt="Odoma website about page" target="_blank">Giovanni Colavizza</a>.
    </div>

    <div class="credits-photos clearfix">
        <div class="credits-photo">
            <a href="https://www.rivier-consulting.com/" alt="Rivier Consulting Website" target="_blank">
                <img class="column" src="img/Laurent-Rivier.png" />
                <div class="credits-photo__overlay">
                    <h3>Laurent Rivier</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 63 63" fill="none">
                        <rect x="1.65" y="1.65" width="59.7" height="59.7" rx="8.00904" stroke="white" stroke-width="3.3"/>
                        <path d="M53.65 12.0035C53.6519 11.0922 52.9147 10.3519 52.0035 10.35L37.1535 10.3188C36.2422 10.3169 35.502 11.054 35.5 11.9653C35.4981 12.8766 36.2353 13.6169 37.1466 13.6188L50.3465 13.6465L50.3188 26.8465C50.3169 27.7578 51.054 28.498 51.9653 28.5C52.8766 28.5019 53.6169 27.7647 53.6188 26.8534L53.65 12.0035ZM20.9869 45.2115L53.1643 13.1692L50.8357 10.8308L18.6584 42.8732L20.9869 45.2115Z" fill="white"/>
                    </svg>
                </div>
            </a>
        </div>
        <div class="credits-photo">
            <a href="https://www.odoma.ch/about" alt="Odoma website about page" target="_blank">
                <img class="column" src="img/Laurent-Bolli.png" />
                <div class="credits-photo__overlay">
                    <h3>Laurent Bolli</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 63 63" fill="none">
                        <rect x="1.65" y="1.65" width="59.7" height="59.7" rx="8.00904" stroke="white" stroke-width="3.3"/>
                        <path d="M53.65 12.0035C53.6519 11.0922 52.9147 10.3519 52.0035 10.35L37.1535 10.3188C36.2422 10.3169 35.502 11.054 35.5 11.9653C35.4981 12.8766 36.2353 13.6169 37.1466 13.6188L50.3465 13.6465L50.3188 26.8465C50.3169 27.7578 51.054 28.498 51.9653 28.5C52.8766 28.5019 53.6169 27.7647 53.6188 26.8534L53.65 12.0035ZM20.9869 45.2115L53.1643 13.1692L50.8357 10.8308L18.6584 42.8732L20.9869 45.2115Z" fill="white"/>
                    </svg>
                </div>
            </a>
        </div>
        <div class="credits-photo">
            <a href="https://www.odoma.ch/about" alt="Odoma website about page" target="_blank">
                <img class="column" src="img/Giovanni-Colavizza.png" />
                <div class="credits-photo__overlay">
                    <h3>Giovanni Colavizza</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 63 63" fill="none">
                        <rect x="1.65" y="1.65" width="59.7" height="59.7" rx="8.00904" stroke="white" stroke-width="3.3"/>
                        <path d="M53.65 12.0035C53.6519 11.0922 52.9147 10.3519 52.0035 10.35L37.1535 10.3188C36.2422 10.3169 35.502 11.054 35.5 11.9653C35.4981 12.8766 36.2353 13.6169 37.1466 13.6188L50.3465 13.6465L50.3188 26.8465C50.3169 27.7578 51.054 28.498 51.9653 28.5C52.8766 28.5019 53.6169 27.7647 53.6188 26.8534L53.65 12.0035ZM20.9869 45.2115L53.1643 13.1692L50.8357 10.8308L18.6584 42.8732L20.9869 45.2115Z" fill="white"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <h1 class="page-static__title">Support from: </h1>
    <table class="page-static__logos">
      <tr>
        <td><img src="img/logo-TO.png" alt="www.to.org"></td>
        <td><a href="http://www.to.org">www.to.org</a></td>
      </tr>
      <tr>
        <td><img src="img/logo-odoma.png" alt="www.odoma.ch"></td>
        <td><a href="http://www.odoma.ch">www.odoma.ch</a></td>
      </tr>
      <tr>
        <td><img src="img/logo_explorateurs.png" alt="www.explo.org"></td>
        <td></td>
      </tr>
    </table>
  </div>
  </section>
  <!-- /page-main -->

@include('layouts.nav')
@endsection
