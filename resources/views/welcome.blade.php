<!doctype html>
<html lang="hu">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Brill Lash and Beauty | Szempilla és szépség Esztivel</title>
    <meta
      name="description"
      content="Brill Lash and Beauty - modern, természetes hatású szempilla szolgáltatások Esztitől. Időpontfoglalás Instagramon: @brill_lash_and_beauty."
    />
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800;900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <header class="site-header">
      <a class="brand" href="#fooldal" aria-label="Brill Lash and Beauty kezdőlap">
        <span class="brand-name">Brill</span>
        <span class="brand-subtitle">Lash & Beauty</span>
      </a>
      <button class="menu-toggle" type="button" aria-label="Menü megnyitása" aria-expanded="false">
        <span></span>
        <span></span>
      </button>
      <nav class="main-nav" aria-label="Főmenü">
        <a href="#bepillantas">Bepillantás</a>
        <a href="#szolgaltatasok">Szolgáltatások</a>
        <a href="#rolam">Rólam</a>
        <a href="#galeria">Galéria</a>
        <a href="#velemenyek">Vélemények</a>
        <a href="#kapcsolat">Kapcsolat</a>
      </nav>
      <a class="header-cta" href="https://www.instagram.com/brill_lash_and_beauty/" target="_blank" rel="noreferrer">
        Instagramon foglalok
      </a>
    </header>

    <main id="fooldal">
      <section class="hero">
        <div class="hero-copy">
          <p class="eyebrow">Természetes. Precíz. Ragyogó.</p>
          <h1>Brill Lash and Beauty</h1>
          <p class="hero-lead">
            Finoman kiemelt tekintet, igényes szempilla szolgáltatások és nyugodt szépségpillanatok Esztivel.
          </p>
          <div class="hero-actions">
            <a class="button primary" href="https://www.instagram.com/brill_lash_and_beauty/" target="_blank" rel="noreferrer">
              Instagramon foglalok
            </a>
            <a class="button ghost" href="#szolgaltatasok">Megnézem a szolgáltatásokat</a>
          </div>
          <dl class="hero-stats" aria-label="Szalon előnyei">
            <div>
              <dt>Puha</dt>
              <dd>viselet</dd>
            </div>
            <div>
              <dt>Precíz</dt>
              <dd>illesztés</dd>
            </div>
            <div>
              <dt>Modern</dt>
              <dd>hatás</dd>
            </div>
          </dl>
        </div>
        <img class="hero-photo" src="{{ asset('assets/hero-eye-photo.png') }}" alt="Közeli szem dús, elegáns szempillákkal" />
      </section>

      <section class="about section-band" id="rolam">
        <div class="about-image">
          <img src="{{ asset('assets/intro.jpg') }}" alt="Árendás Eszter, a Brill Lash and Beauty tulajdonosa" />
        </div>
        <div class="section-copy">
          <p class="eyebrow">Rólam</p>
          <h2>Árendás Eszter vagyok</h2>
          <p>
            Sminkesként és szempilla stylistként 2018 óta dolgozom azon, hogy vendégeim igényes, tartós és harmonikus
            eredménnyel távozzanak. Fontos számomra a precizitás, a nyugodt hangulat és az, hogy minden elkészült
            munka természetesen illeszkedjen a viselőjéhez.
          </p>
          <p>
            Hiszem, hogy a tökéletes végeredmény nem csupán látványos, hanem harmonikusan illeszkedik az arc
            karakteréhez, a szemformához és a vendég életstílusához.
          </p>
          <a class="text-link" href="https://www.instagram.com/brill_lash_and_beauty/" target="_blank" rel="noreferrer">
            @brill_lash_and_beauty
          </a>
        </div>
        <img class="lash-mark" src="{{ asset('assets/lash-mark.svg') }}" alt="" />
      </section>

      <section class="services" id="bepillantas">
        <div class="section-heading">
          <p class="eyebrow">Bepillantás</p>
          <h2>Válassz a kedvenceim közül</h2>
        </div>
        <div class="service-grid">
          @foreach ($content['services'] as $service)
            <article class="service-card">
              @if (! empty($service['image']))
                <img class="service-art" src="{{ asset($service['image']) }}" alt="{{ $service['alt'] }}" />
              @endif
              <h3>{{ $service['title'] }}</h3>
              <p>{{ $service['description'] }}</p>
              <span>{{ $service['price'] }}</span>
            </article>
          @endforeach
        </div>
      </section>

      <section class="pricing section-band" id="szolgaltatasok">
        <div class="section-heading">
          <p class="eyebrow">Szolgáltatások</p>
          <h2>Árlista</h2>
        </div>
        <div class="price-card" aria-label="Brill Lash and Beauty árlista">
          @foreach ($content['price_groups'] as $group)
            <div class="price-group">
              <h3>{{ $group['title'] }}</h3>
              <dl class="price-list">
                @foreach ($group['items'] as $item)
                  <div>
                    <dt>{{ $item['name'] }}</dt>
                    <dd>{{ $item['price'] }}</dd>
                  </div>
                @endforeach
              </dl>
            </div>
          @endforeach
        </div>
      </section>

      <section class="gallery section-band" id="galeria">
        <div class="section-heading">
          <p class="eyebrow">Előtte - utána</p>
          <h2>Finom átalakulások, nagy különbséggel</h2>
        </div>
        <div class="gallery-strip" aria-label="Galéria minták">
          @foreach ($content['gallery'] as $image)
            <figure class="gallery-tile">
              <img src="{{ asset($image['image']) }}" alt="{{ $image['alt'] }}" loading="lazy" />
            </figure>
          @endforeach
        </div>
      </section>

      <section class="benefits" id="miert-en">
        <div class="section-heading">
          <p class="eyebrow">A Brill élmény</p>
          <h2>A részletekre figyelve készül</h2>
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
          <p class="eyebrow">Vélemények</p>
          <h2>Vendégeim mondták</h2>
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

      <section class="booking" id="kapcsolat">
        <div class="booking-copy">
          <p class="eyebrow">Kapcsolat</p>
          <h2>Foglalj időpontot kényelmesen</h2>
          <p>
            Időpontfoglalásra Instagram üzenetben van lehetőség. Munka közben teljes
            figyelmemet a vendégeimre fordítom, ezért előfordulhat, hogy nem tudok azonnal válaszolni. Minden
            megkeresésre a lehető legrövidebb időn belül reagálok.
          </p>
          <ul class="booking-list">
            <li>első alkalommal rövid konzultáció</li>
            <li>egyedi styling ajánlás</li>
            <li>elő- és utóápolási tanácsadás</li>
            <li>új vendégeknek 50% előleggel véglegesített időpont</li>
          </ul>
        </div>
        <img src="{{ asset('assets/lash-mark.svg') }}" alt="" />
        <div class="booking-actions">
          <a class="button primary" href="https://www.instagram.com/brill_lash_and_beauty/" target="_blank" rel="noreferrer">
            Instagramon foglalok
          </a>
          <a class="button ghost" href="https://www.instagram.com/brill_lash_and_beauty/" target="_blank" rel="noreferrer">
            Kapcsolatfelvétel
          </a>
        </div>
      </section>

      <section class="policies section-band" id="informaciok">
        <div class="section-heading policy-heading">
          <p class="eyebrow">Fontos információk</p>
          <h2>Minden, amit érdemes tudnod foglalás előtt</h2>
        </div>
        <div class="accordion-group">
          <details class="accordion">
            <summary>Foglalási feltételek</summary>
            <div class="accordion-panel">
              <p>Az időpont véglegesítése új vendégek esetében 50% előleg befizetésével történik.</p>
              <p>Amennyiben a lefoglalt időpontot nem tudod igénybe venni, kérlek legalább 24 órával korábban jelezd.</p>
              <p>
                A kezelés előtt 24 órán belül lemondott időpontok, illetve meg nem jelenés esetén az előleg
                visszatérítésére nincs lehetőség.
              </p>
              <p>Az időpont lefoglalásával a szolgáltatási feltételek elfogadásra kerülnek.</p>
            </div>
          </details>
          <details class="accordion">
            <summary>Garancia</summary>
            <div class="accordion-panel">
              <p>
                Fontos számomra a minőség és a vendégeim elégedettsége, ezért munkámra 7 nap garanciát vállalok.
              </p>
              <p>
                Amennyiben a szett az általam végzett munka hibájából jelentős mértékben idő előtt hullik ki, a
                szükséges korrekciót díjmentesen elvégzem.
              </p>
            </div>
          </details>
          <details class="accordion">
            <summary>Egészség és biztonság</summary>
            <div class="accordion-panel">
              <p>
                Kérlek, hogy a kezelésre egészséges állapotban érkezz. A saját és más vendégek biztonsága érdekében
                fertőző vagy aktív szemkörnyéki megbetegedés esetén a kezelés nem végezhető el.
              </p>
              <p>
                A műszempilla építés során professzionális, kozmetikai célra fejlesztett szempillaragasztót használok.
                A ragasztó megfelelő alkalmazás mellett biztonságos és tartós rögzítést biztosít, azonban érzékenység
                vagy allergiás reakció ritka esetben előfordulhat.
              </p>
              <p>
                Amennyiben korábban bármilyen kozmetikai termékre, ragasztóra vagy szemkörnyéki készítményre
                érzékenységet tapasztaltál, kérlek jelezd a konzultáció során.
              </p>
            </div>
          </details>
          <details class="accordion" id="gyik">
            <summary>Gyakori kérdések</summary>
            <div class="accordion-panel faq-list">
              @foreach ($content['faq'] as $faq)
                <div>
                  <h3>{{ $faq['question'] }}</h3>
                  <p>{{ $faq['answer'] }}</p>
                </div>
              @endforeach
            </div>
          </details>
        </div>
      </section>

      <section class="closing">
        <h2>Köszönöm a bizalmadat, várlak szeretettel!</h2>
        <p class="closing-signoff">
          Árendás Eszter<br />
          Sminkes &amp; Szempilla Stylist
        </p>
        <p class="closing-quote">“A szépség a részletekben rejlik.”</p>
      </section>
    </main>

    <footer>
      <p>Brill Lash and Beauty. Minden jog fenntartva.</p>
      <a href="https://www.instagram.com/brill_lash_and_beauty/" target="_blank" rel="noreferrer">@brill_lash_and_beauty</a>
    </footer>
  </body>
</html>
