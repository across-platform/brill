@php
  $instagramUrl = 'https://www.instagram.com/brill_lash_and_beauty/';
  $phoneDisplay = '+36 70 325 1155';
  $phoneHref = '+36703251155';
  $address = '8060 Mór, Zrínyi utca 11.';
  $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=8060%20M%C3%B3r%2C%20Zr%C3%ADnyi%20utca%2011';
  $year = date('Y');
@endphp

<!doctype html>
<html lang="hu">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Brill Lash and Beauty | Műszempilla, lifting és szemöldök styling Móron</title>
    <meta
      name="description"
      content="Prémium műszempilla, szempilla lifting, szemöldök styling és AirBrush Brow System Árendás Eszternél Móron, a Brill Lash and Beauty szalonban."
    />
    <link rel="canonical" href="{{ url('/') }}" />
    <meta property="og:title" content="Brill Lash and Beauty | Szépségszalon Móron" />
    <meta property="og:description" content="Személyre szabott műszempilla, lifting és szemöldök styling Mór szívében." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:image" content="{{ asset('assets/hero-eye-photo.png') }}" />
    <script>
      (() => {
        if ("scrollRestoration" in history) {
          history.scrollRestoration = "manual";
        }

        const navigation = performance.getEntriesByType("navigation")[0];
        if (navigation?.type === "reload" && window.location.hash) {
          history.replaceState(null, "", `${window.location.pathname}${window.location.search}`);
        }

        window.scrollTo(0, 0);
      })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <script type="application/ld+json">
      {
        "@@context": "https://schema.org",
        "@@type": "BeautySalon",
        "name": "Brill Lash and Beauty",
        "image": "{{ asset('assets/hero-eye-photo.png') }}",
        "address": {
          "@@type": "PostalAddress",
          "streetAddress": "Zrínyi utca 11.",
          "postalCode": "8060",
          "addressLocality": "Mór",
          "addressCountry": "HU"
        },
        "telephone": "{{ $phoneDisplay }}",
        "url": "{{ url('/') }}",
        "sameAs": ["{{ $instagramUrl }}"]
      }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <header class="site-header">
      <a class="brand" href="#fooldal" aria-label="Brill Lash and Beauty kezdőlap">
        <img
          class="brand-logo"
          src="{{ asset('assets/brill-logo-full.png') }}"
          alt=""
          width="1014"
          height="390"
        />
      </a>

      <button class="menu-toggle" type="button" aria-label="Menü megnyitása" aria-expanded="false">
        <span></span>
        <span></span>
      </button>

      <nav class="main-nav" aria-label="Főmenü">
        <a href="#fooldal">Kezdőlap</a>
        <a href="#szolgaltatasok">Szolgáltatások</a>
        <a href="#arlista">Árlista</a>
        <a href="#rolam">Rólam</a>
        <a href="#galeria">Galéria</a>
        <a href="#kapcsolat">Kapcsolat</a>
      </nav>

      <a class="header-cta" href="{{ $instagramUrl }}" target="_blank" rel="noreferrer" aria-label="Időpontkérés Instagramon">
        Időpontot kérek
      </a>
    </header>

    <main id="fooldal">
      <section class="hero">
        <div class="hero-copy">
          <p class="eyebrow">Brill Lash and Beauty · Mór</p>
          <h1>A tekinteted. A stílusod. A magabiztosságod.</h1>
          <p class="hero-lead">
            Prémium műszempilla, szempilla lifting és szemöldök styling Mór szívében, Árendás Eszternél.
          </p>
          <div class="hero-actions">
            <a class="button primary" href="{{ $instagramUrl }}" target="_blank" rel="noreferrer">
              Időpontot kérek
            </a>
            <a class="button secondary" href="#szolgaltatasok">Szolgáltatások megtekintése</a>
          </div>
          <ul class="hero-notes" aria-label="Fő előnyök">
            <li>UV-LED technológia</li>
            <li>Személyre szabott tervezés</li>
            <li>Nyugodt, barátságos környezet</li>
          </ul>
        </div>

        <div class="hero-media" aria-label="Elegáns szempilla styling hangulatkép">
          <img src="{{ asset('assets/hero-eye-photo.png') }}" alt="Közeli szem elegáns, dús szempillákkal" />
        </div>
      </section>

      <section class="intro section-band" id="rolam">
        <div class="intro-image">
          <img src="{{ asset('assets/intro.jpg') }}" alt="Árendás Eszter, a Brill Lash and Beauty tulajdonosa" loading="lazy" />
        </div>
        <div class="section-copy">
          <p class="eyebrow">Rólam</p>
          <h2>Árendás Eszter vagyok</h2>
          <p>
            Sminkes és szempilla stylistként 2018 óta dolgozom azon, hogy minden vendégem számára igényes, tartós és
            természetesen harmonikus eredmény készüljön. A Brill Lash and Beauty-ben minden szettet az arc karakteréhez,
            a szemformához és az életstílusodhoz igazítok.
          </p>
        </div>
      </section>

      <section class="services" id="szolgaltatasok">
        <div class="section-heading">
          <p class="eyebrow">Szolgáltatások</p>
          <h2>Finom részletek, harmonikus végeredmény</h2>
          <p>
            A szolgáltatásokat az arcodhoz, stílusodhoz és a mindennapjaidhoz igazítva tervezzük meg.
          </p>
        </div>

        <div class="service-grid">
          @foreach ($content['services'] as $service)
            <article class="service-card">
              @if (! empty($service['image']))
                <img class="service-art" src="{{ asset($service['image']) }}" alt="{{ $service['alt'] }}" loading="lazy" />
              @endif
              <div class="service-body">
                <h3>{{ $service['title'] }}</h3>
                <p>{{ $service['description'] }}</p>
                @if (! empty($service['price']))
                  <span>{{ $service['price'] }}</span>
                @endif
              </div>
            </article>
          @endforeach
        </div>
      </section>

      <section class="technology section-band" id="uv-led">
        <div class="feature-copy">
          <p class="eyebrow">UV-LED technológia</p>
          <h2>Tartós, kényelmes viselet modern technológiával</h2>
          <p>
            A műszempilla építés UV-LED technológiával készül, amelynél a ragasztó azonnal megköt. A kezelés után a
            pillát rögtön érheti víz és gőz, így zuhanyozás, sport, úszás vagy szauna mellett is praktikus választás.
          </p>
          <p class="small-note">Az eredmény és a tartósság egyénfüggő, ezért minden alkalommal rövid konzultációval kezdünk.</p>
        </div>
        <div class="feature-list">
          <span>A ragasztó azonnal megköt</span>
          <span>Gyors, precíz munkavégzés</span>
          <span>Kényelmes mindennapi viselet</span>
          <span>Víz és gőz azonnal érheti</span>
        </div>
      </section>

      <section class="airbrush">
        <div class="airbrush-panel">
          <div>
            <p class="eyebrow">Újdonság a szalonban</p>
            <h2>A tökéletes szemöldökfestés új generációja</h2>
            <p>
              Az AirBrush Brow System ultrafinom porlasztással segít egyenletes, természetes satírozott, ombre vagy
              intenzívebb szemöldökhatást kialakítani.
            </p>
            <a class="button primary" href="{{ $instagramUrl }}" target="_blank" rel="noreferrer">Időpontot kérek</a>
          </div>
          <ul>
            <li>ultrafinom porlasztás</li>
            <li>egyenletes festékfelvitel</li>
            <li>soft powder és ombre hatás</li>
            <li>rétegről rétegre építhető intenzitás</li>
            <li>modern, prémium technológia</li>
          </ul>
        </div>
      </section>

      <section class="pricing section-band" id="arlista">
        <div class="section-heading">
          <p class="eyebrow">Árlista</p>
          <h2>Átlátható árak, nyugodt döntés</h2>
          <p>A töltési ár legfeljebb 21 napig érvényes. Az utántöltés általában 3-4 hetente ajánlott, de ez egyénfüggő.</p>
        </div>
        <div class="price-card" aria-label="Brill Lash and Beauty árlista">
          @foreach ($content['price_groups'] as $group)
            <section class="price-group" aria-labelledby="price-{{ $loop->index }}">
              <h3 id="price-{{ $loop->index }}">{{ $group['title'] }}</h3>
              <dl class="price-list">
                @foreach ($group['items'] as $item)
                  <div>
                    <dt>{{ $item['name'] }}</dt>
                    <dd>{{ $item['price'] }}</dd>
                  </div>
                @endforeach
              </dl>
            </section>
          @endforeach
        </div>
      </section>

      <section class="gallery" id="galeria">
        <div class="section-heading">
          <p class="eyebrow">Galéria</p>
          <h2>Saját munkák és hangulatképek</h2>
          <p>Válogatás az elkészült munkákból és a Brill Lash and Beauty hangulatából.</p>
        </div>
        <div class="gallery-tabs" aria-label="Galéria kategóriák">
          <span>Műszempilla</span>
          <span>Lifting</span>
          <span>Szemöldök</span>
          <span>Airbrush</span>
        </div>
        <div class="gallery-grid" aria-label="Galéria képek">
          @foreach ($content['gallery'] as $image)
            <button class="gallery-tile" type="button" data-lightbox-src="{{ asset($image['image']) }}" data-lightbox-alt="{{ $image['alt'] }}">
              <img src="{{ asset($image['image']) }}" alt="{{ $image['alt'] }}" loading="lazy" />
            </button>
          @endforeach
        </div>
      </section>

      <section class="benefits section-band" id="miert-en">
        <div class="section-heading">
          <p class="eyebrow">Miért válassz engem?</p>
          <h2>A Brill élmény a figyelmes részletekből áll</h2>
        </div>
        <div class="benefit-grid">
          @foreach ($content['benefits'] as $benefit)
            <article class="benefit-card">
              <h3>{{ $benefit['title'] }}</h3>
              <p>{{ $benefit['description'] }}</p>
            </article>
          @endforeach
        </div>
      </section>

      <section class="testimonials" id="velemenyek">
        <div class="section-heading">
          <p class="eyebrow">Vendégvélemények</p>
          <h2>Vendégélmények hamarosan</h2>
          <p>Ebben a részben később valódi értékelések jelenhetnek meg, engedéllyel és pontos szöveggel.</p>
        </div>
        <div class="testimonial-grid">
          @foreach ($content['testimonials'] as $testimonial)
            <figure>
              <blockquote>{{ $testimonial['quote'] }}</blockquote>
              <figcaption>{{ $testimonial['name'] }}</figcaption>
            </figure>
          @endforeach
        </div>
      </section>

      <section class="faq section-band" id="gyik">
        <div class="section-heading">
          <p class="eyebrow">Gyakori kérdések</p>
          <h2>Rövid válaszok foglalás előtt</h2>
        </div>
        <div class="accordion-group">
          @foreach ($content['faq'] as $faq)
            <details class="accordion">
              <summary>{{ $faq['question'] }}</summary>
              <div class="accordion-panel">
                <p>{{ $faq['answer'] }}</p>
              </div>
            </details>
          @endforeach
          <details class="accordion">
            <summary>Mi történik késés vagy lemondás esetén?</summary>
            <div class="accordion-panel">
              <p>Kérlek, jelezd minél hamarabb, ha változtatni szeretnél. A pontos feltételeket foglaláskor egyeztetjük.</p>
            </div>
          </details>
        </div>
      </section>

      <section class="contact" id="kapcsolat">
        <div class="contact-card">
          <p class="eyebrow">Kapcsolat és időpontfoglalás</p>
          <h2>Várlak szeretettel Móron</h2>
          <address>
            <strong>Brill Lash and Beauty</strong>
            <span>Árendás Eszter</span>
            <span>Sminkes és Szempilla Stylist</span>
            <span>{{ $address }}</span>
            <a href="tel:{{ $phoneHref }}">{{ $phoneDisplay }}</a>
            <a href="{{ $instagramUrl }}" target="_blank" rel="noreferrer">Instagram: @brill_lash_and_beauty</a>
          </address>
          <div class="contact-actions">
            <a class="button primary" href="{{ $instagramUrl }}" target="_blank" rel="noreferrer">Időpontot kérek</a>
            <a class="button secondary" href="{{ $mapsUrl }}" target="_blank" rel="noreferrer">Térkép megnyitása</a>
            <a class="button quiet" href="tel:{{ $phoneHref }}">Telefonálok</a>
          </div>
        </div>
      </section>
    </main>

    <footer>
      <div>
        <strong>Brill Lash and Beauty</strong>
        <span>Árendás Eszter · Sminkes és Szempilla Stylist</span>
        <span>{{ $address }} · <a href="tel:{{ $phoneHref }}">{{ $phoneDisplay }}</a></span>
      </div>
      <nav aria-label="Lábléc linkek">
        <a href="{{ $instagramUrl }}" target="_blank" rel="noreferrer">Instagram</a>
        <a href="#">Adatkezelési tájékoztató</a>
        <a href="#">Impresszum</a>
      </nav>
      <p>© {{ $year }} Brill Lash and Beauty. Minden jog fenntartva.</p>
    </footer>

    <a class="mobile-sticky-cta" href="{{ $instagramUrl }}" target="_blank" rel="noreferrer">Időpontot kérek</a>

    <div class="lightbox" hidden>
      <button class="lightbox-close" type="button" aria-label="Galéria kép bezárása">×</button>
      <img src="" alt="" />
    </div>
  </body>
</html>
