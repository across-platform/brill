<!doctype html>
<html lang="hu">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Brill Admin | Tartalom szerkesztés</title>
    <style>
      :root {
        --ink: #1f1514;
        --muted: #67504b;
        --line: #ddbeb5;
        --paper: #fffaf6;
        --rose: #b87588;
        --rose-dark: #7f4f5d;
        --surface: #ffffff;
        --soft: #fff3ee;
        --shadow: 0 22px 60px rgba(74, 38, 33, 0.16);
      }

      * {
        box-sizing: border-box;
      }

      body {
        margin: 0;
        color: var(--ink);
        background:
          linear-gradient(180deg, rgba(255, 243, 238, 0.84), rgba(255, 250, 246, 0.98) 320px),
          var(--paper);
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        line-height: 1.5;
      }

      header {
        position: sticky;
        top: 0;
        z-index: 5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 18px clamp(18px, 5vw, 72px);
        border-bottom: 1px solid rgba(221, 190, 181, 0.88);
        background: rgba(255, 250, 246, 0.94);
        backdrop-filter: blur(18px);
      }

      h1,
      h2,
      h3,
      p {
        margin-top: 0;
      }

      h1,
      h2,
      h3 {
        font-family: Georgia, "Times New Roman", serif;
        font-weight: 500;
        line-height: 1.1;
      }

      h1 {
        margin-bottom: 4px;
        font-size: clamp(1.8rem, 3vw, 2.6rem);
      }

      h2 {
        margin-bottom: 18px;
        font-size: clamp(1.6rem, 2.5vw, 2.2rem);
      }

      h3 {
        margin-bottom: 14px;
        font-size: 1.25rem;
      }

      a {
        color: var(--rose-dark);
        font-weight: 800;
        text-decoration: none;
      }

      .subtitle,
      .hint {
        color: var(--muted);
      }

      .subtitle {
        margin: 0;
        font-size: 0.92rem;
      }

      .header-actions,
      .panel-actions,
      .row-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
      }

      .header-actions {
        justify-content: flex-end;
      }

      .page {
        display: grid;
        gap: 22px;
        padding: clamp(24px, 5vw, 56px) clamp(18px, 5vw, 72px);
      }

      .section-picker {
        display: grid;
        gap: 10px;
        max-width: 420px;
      }

      .error-list {
        padding: 14px 18px;
        border: 1px solid rgba(127, 67, 67, 0.28);
        border-radius: 8px;
        color: #7f4343;
        background: rgba(255, 246, 242, 0.82);
        font-weight: 700;
      }

      .error-list ul {
        margin: 8px 0 0;
        padding-left: 20px;
      }

      .panel {
        display: none;
        border: 1px solid rgba(221, 190, 181, 0.94);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.88);
        box-shadow: var(--shadow);
      }

      .panel.is-active {
        display: block;
      }

      .panel-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        padding: 24px 24px 0;
      }

      .panel-head p {
        margin-bottom: 0;
      }

      .panel-body,
      .repeater {
        display: grid;
        gap: 18px;
      }

      .panel-body {
        padding: 24px;
      }

      .status {
        padding: 14px 18px;
        border: 1px solid rgba(127, 143, 130, 0.24);
        border-radius: 8px;
        color: #445645;
        background: rgba(127, 143, 130, 0.12);
        font-weight: 800;
      }

      .hint {
        font-size: 0.9rem;
      }

      .field-help {
        color: var(--muted);
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0;
        text-transform: none;
      }

      .image-route-field {
        display: grid;
        grid-column: 1 / -1;
        gap: 10px;
      }

      .image-route-tools {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(160px, 0.45fr);
        gap: 10px;
        align-items: stretch;
      }

      .image-dropzone {
        display: grid;
        min-height: 42px;
        place-items: center;
        padding: 10px 12px;
        border: 1px dashed rgba(156, 111, 125, 0.44);
        border-radius: 8px;
        color: var(--rose-dark);
        background: rgba(255, 255, 255, 0.62);
        cursor: pointer;
        font-size: 0.78rem;
        font-weight: 850;
        letter-spacing: 0.04em;
        text-align: center;
        text-transform: uppercase;
        transition: border-color 160ms ease, background 160ms ease;
      }

      .image-dropzone.is-over,
      .image-dropzone:focus {
        outline: 0;
        border-color: var(--rose-dark);
        background: rgba(255, 246, 242, 0.92);
      }

      .row,
      .price-group-editor,
      .price-row {
        display: grid;
        gap: 12px;
        padding: 18px;
        border: 1px solid rgba(221, 190, 181, 0.86);
        border-radius: 12px;
        background: rgba(255, 250, 246, 0.72);
      }

      .row {
        align-items: start;
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .row label.full,
      .row-actions,
      .price-group-editor,
      .price-row {
        grid-column: 1 / -1;
      }

      .price-group-editor {
        background: var(--surface);
        box-shadow: 0 12px 36px rgba(74, 38, 33, 0.08);
      }

      .price-row {
        grid-template-columns: minmax(0, 1fr) minmax(150px, 0.3fr) auto;
        padding: 12px;
        border-color: rgba(221, 190, 181, 0.72);
        background: var(--soft);
      }

      label {
        display: grid;
        gap: 7px;
        color: var(--muted);
        font-size: 0.82rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
      }

      input,
      textarea,
      select {
        width: 100%;
        min-height: 42px;
        padding: 10px 12px;
        border: 1px solid rgba(125, 82, 74, 0.28);
        border-radius: 8px;
        color: var(--ink);
        background: #fff;
        font: inherit;
        letter-spacing: 0;
      }

      textarea {
        min-height: 92px;
        resize: vertical;
      }

      .button,
      button {
        display: inline-flex;
        min-height: 42px;
        align-items: center;
        justify-content: center;
        padding: 0 18px;
        border: 0;
        border-radius: 999px;
        color: #fff;
        background: var(--rose);
        box-shadow: 0 14px 34px rgba(184, 117, 136, 0.26);
        cursor: pointer;
        font: inherit;
        font-size: 0.78rem;
        font-weight: 850;
        letter-spacing: 0.08em;
        text-transform: uppercase;
      }

      .button.secondary,
      button.secondary {
        color: var(--rose-dark);
        border: 1px solid rgba(127, 79, 93, 0.38);
        background: rgba(255, 250, 246, 0.92);
        box-shadow: none;
      }

      button.danger {
        color: #7f4343;
        border: 1px solid rgba(127, 67, 67, 0.34);
        background: rgba(255, 246, 242, 0.94);
        box-shadow: none;
      }

      .image-preview {
        width: 92px;
        height: 62px;
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 8px;
        background: #fff;
      }

      .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .backup-list {
        display: grid;
        gap: 10px;
      }

      .backup-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px;
        border: 1px solid rgba(234, 213, 205, 0.7);
        border-radius: 8px;
        background: rgba(255, 250, 246, 0.62);
      }

      .backup-item strong {
        display: block;
        font-size: 0.94rem;
      }

      .backup-item span {
        color: var(--muted);
        font-size: 0.86rem;
      }

      .preview-wrap {
        position: relative;
        width: fit-content;
      }

      .preview-popover {
        position: absolute;
        bottom: calc(100% + 10px);
        left: 0;
        z-index: 20;
        width: min(320px, calc(100vw - 48px));
        padding: 16px;
        border: 1px solid rgba(234, 213, 205, 0.9);
        border-radius: 8px;
        background: #fff;
        box-shadow: var(--shadow);
        opacity: 0;
        pointer-events: none;
        transform: translateY(6px);
        transition: opacity 160ms ease, transform 160ms ease;
      }

      .preview-wrap:hover .preview-popover,
      .preview-wrap:focus-within .preview-popover {
        opacity: 1;
        transform: translateY(0);
      }

      .preview-popover h4 {
        margin: 0 0 8px;
        font-family: Georgia, "Times New Roman", serif;
        font-size: 1.2rem;
        font-weight: 500;
      }

      .preview-popover p {
        margin: 0 0 8px;
        color: var(--muted);
        font-size: 0.9rem;
      }

      .preview-popover img {
        width: 100%;
        max-height: 150px;
        margin-bottom: 12px;
        border-radius: 8px;
        object-fit: cover;
      }

      .preview-popover strong {
        display: block;
        color: var(--ink);
        font-family: Georgia, "Times New Roman", serif;
        font-size: 1.05rem;
        font-weight: 500;
      }

      .save-bar {
        position: sticky;
        bottom: 0;
        display: flex;
        justify-content: flex-end;
        padding: 18px 0 0;
        background: linear-gradient(180deg, rgba(255, 250, 246, 0), var(--paper) 42%);
      }

      template {
        display: none;
      }

      @media (max-width: 760px) {
        header,
        .header-actions,
        .panel-head {
          align-items: flex-start;
          flex-direction: column;
        }

        .row,
        .price-row,
        .image-route-tools,
        .backup-item {
          grid-template-columns: 1fr;
        }

        .backup-item {
          align-items: flex-start;
        }
      }
    </style>
  </head>
  <body>
    <header>
      <div>
        <h1>Brill Admin</h1>
        <p class="subtitle">Rejtett tartalom- és árlista szerkesztő</p>
      </div>
      <div class="header-actions">
        <a class="button secondary" href="{{ route('home') }}" target="_blank" rel="noreferrer">Weboldal megtekintése</a>
        <form method="post" action="{{ route('admin.logout') }}">
          @csrf
          <button class="secondary" type="submit">Kilépés</button>
        </form>
      </div>
    </header>

    <main class="page">
      @if (session('status'))
        <div class="status">{{ session('status') }}</div>
      @endif

      @if ($errors->any())
        <div class="error-list">
          Kérlek, javítsd az alábbi hibákat:
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <label class="section-picker">
        Szekció kiválasztása
        <select id="sectionPicker" aria-label="Szerkesztendő szekció kiválasztása">
          <option value="services">Szolgáltatások</option>
          <option value="gallery">Galéria</option>
          <option value="prices">Árlista</option>
          <option value="benefits">Előnyök</option>
          <option value="testimonials">Vélemények</option>
          <option value="faq">GYIK</option>
          <option value="backups">Legutóbbi mentések</option>
        </select>
      </label>

      <form id="contentForm" method="post" action="{{ route('admin.save') }}">
        @csrf

        <section class="panel is-active" data-section="services">
          <div class="panel-head">
            <div>
              <h2>Szolgáltatások</h2>
              <p class="hint">Itt módosíthatók a főoldalon megjelenő szolgáltatás kártyák.</p>
            </div>
            <button type="button" data-add="service">Sor hozzáadása</button>
          </div>
          <div class="panel-body">
            <div class="repeater" data-repeater="services">
              @foreach ($content['services'] as $index => $service)
                <div class="row" data-preview="service">
                  <label>
                    Cím
                    <input name="content[services][{{ $index }}][title]" value="{{ $service['title'] ?? '' }}" data-field="title" />
                  </label>
                  <label>
                    Ár
                    <input name="content[services][{{ $index }}][price]" value="{{ $service['price'] ?? '' }}" placeholder="14 000 Ft" data-field="price" />
                  </label>
                  <label class="full">
                    Leírás
                    <textarea name="content[services][{{ $index }}][description]" data-field="description">{{ $service['description'] ?? '' }}</textarea>
                  </label>
                  <label class="image-route-field">
                    Kép útvonala
                    <span class="image-route-tools">
                      <input name="content[services][{{ $index }}][image]" value="{{ $service['image'] ?? '' }}" placeholder="assets/services/2d-volume.png" data-field="image" />
                      <span class="image-dropzone" role="button" tabindex="0" data-image-dropzone data-image-folder="assets/services">Ide húzd a képet</span>
                    </span>
                    <span class="field-help">Kép útvonala például: assets/services/2d-volume.png</span>
                  </label>
                <label>
                  Kép alt szöveg
                  <input name="content[services][{{ $index }}][alt]" value="{{ $service['alt'] ?? '' }}" />
                </label>
                @if (! empty($service['image']) && file_exists(public_path($service['image'])))
                  <div class="image-preview" aria-label="Kép előnézet">
                    <img src="{{ asset($service['image']) }}" alt="" />
                  </div>
                @endif
                <div class="row-actions">
                    <div class="preview-wrap">
                    <button class="secondary" type="button" data-preview-button>Előnézet</button>
                      <div class="preview-popover" data-preview-popover></div>
                    </div>
                    <button class="danger" type="button" data-remove>Törlés</button>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </section>

        <section class="panel" data-section="gallery">
          <div class="panel-head">
            <div>
              <h2>Galéria</h2>
              <p class="hint">A főoldali galéria képei. Csak nyilvánosan elérhető képútvonalat adj meg.</p>
            </div>
            <button type="button" data-add="gallery">Sor hozzáadása</button>
          </div>
          <div class="panel-body">
            <div class="repeater" data-repeater="gallery">
              @foreach ($content['gallery'] as $index => $image)
                <div class="row" data-preview="gallery">
                  <label class="image-route-field">
                    Kép útvonala
                    <span class="image-route-tools">
                      <input name="content[gallery][{{ $index }}][image]" value="{{ $image['image'] ?? '' }}" placeholder="assets/gallery/kep-neve.jpg" data-field="image" />
                      <span class="image-dropzone" role="button" tabindex="0" data-image-dropzone data-image-folder="assets/gallery">Ide húzd a képet</span>
                    </span>
                    <span class="field-help">Kép útvonala például: assets/gallery/kep-neve.jpg</span>
                  </label>
                  <label>
                    Kép alt szöveg
                    <input name="content[gallery][{{ $index }}][alt]" value="{{ $image['alt'] ?? '' }}" data-field="alt" />
                  </label>
                  @if (! empty($image['image']) && file_exists(public_path($image['image'])))
                    <div class="image-preview" aria-label="Kép előnézet">
                      <img src="{{ asset($image['image']) }}" alt="" />
                    </div>
                  @endif
                  <div class="row-actions">
                    <div class="preview-wrap">
                      <button class="secondary" type="button" data-preview-button>Előnézet</button>
                      <div class="preview-popover" data-preview-popover></div>
                    </div>
                    <button class="danger" type="button" data-remove>Törlés</button>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </section>

        <section class="panel" data-section="prices">
          <div class="panel-head">
            <div>
              <h2>Árlista</h2>
              <p class="hint">Az árakat lehetőleg 0-ra végződően add meg, például 14 000 Ft.</p>
            </div>
            <button type="button" data-add="price-group">Csoport hozzáadása</button>
          </div>
          <div class="panel-body">
            <div class="repeater" data-repeater="price-groups">
              @foreach ($content['price_groups'] as $groupIndex => $group)
                <div class="price-group-editor" data-price-group>
                  <label>
                    Csoport címe
                    <input name="content[price_groups][{{ $groupIndex }}][title]" value="{{ $group['title'] ?? '' }}" data-field="group-title" />
                  </label>
                  <div class="repeater" data-repeater="price-items">
                    @foreach (($group['items'] ?? []) as $itemIndex => $item)
                      <div class="price-row" data-preview="price">
                        <label>
                          Szolgáltatás
                          <input name="content[price_groups][{{ $groupIndex }}][items][{{ $itemIndex }}][name]" value="{{ $item['name'] ?? '' }}" data-field="name" />
                        </label>
                        <label>
                          Ár
                          <input name="content[price_groups][{{ $groupIndex }}][items][{{ $itemIndex }}][price]" value="{{ $item['price'] ?? '' }}" placeholder="14 000 Ft" data-field="price" />
                        </label>
                        <div class="row-actions">
                          <div class="preview-wrap">
                    <button class="secondary" type="button" data-preview-button>Előnézet</button>
                            <div class="preview-popover" data-preview-popover></div>
                          </div>
                          <button class="danger" type="button" data-remove>Törlés</button>
                        </div>
                      </div>
                    @endforeach
                  </div>
                  <div class="row-actions">
                    <button class="secondary" type="button" data-add="price-item">Sor hozzáadása</button>
                    <button class="danger" type="button" data-remove-group>Csoport törlése</button>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </section>

        <section class="panel" data-section="benefits">
          <div class="panel-head">
            <div>
              <h2>Előnyök</h2>
              <p class="hint">Rövid bizalmi üzenetek, amelyek segítik a döntést.</p>
            </div>
            <button type="button" data-add="benefit">Sor hozzáadása</button>
          </div>
          <div class="panel-body">
            <div class="repeater" data-repeater="benefits">
              @foreach ($content['benefits'] as $index => $benefit)
                <div class="row" data-preview="benefit">
                  <label>
                    Cím
                    <input name="content[benefits][{{ $index }}][title]" value="{{ $benefit['title'] ?? '' }}" data-field="title" />
                  </label>
                  <label class="full">
                    Leírás
                    <textarea name="content[benefits][{{ $index }}][description]" data-field="description">{{ $benefit['description'] ?? '' }}</textarea>
                  </label>
                  <div class="row-actions">
                    <div class="preview-wrap">
                    <button class="secondary" type="button" data-preview-button>Előnézet</button>
                      <div class="preview-popover" data-preview-popover></div>
                    </div>
                    <button class="danger" type="button" data-remove>Törlés</button>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </section>

        <section class="panel" data-section="testimonials">
          <div class="panel-head">
            <div>
              <h2>Vélemények</h2>
              <p class="hint">Rövid vendégvélemények, amelyek a főoldalon kártyaként jelennek meg.</p>
            </div>
            <button type="button" data-add="testimonial">Sor hozzáadása</button>
          </div>
          <div class="panel-body">
            <div class="repeater" data-repeater="testimonials">
              @foreach ($content['testimonials'] as $index => $testimonial)
                <div class="row" data-preview="testimonial">
                  <label>
                    Név
                    <input name="content[testimonials][{{ $index }}][name]" value="{{ $testimonial['name'] ?? '' }}" data-field="name" />
                  </label>
                  <label class="full">
                    Vélemény
                    <textarea name="content[testimonials][{{ $index }}][quote]" data-field="quote">{{ $testimonial['quote'] ?? '' }}</textarea>
                  </label>
                  <div class="row-actions">
                    <div class="preview-wrap">
                    <button class="secondary" type="button" data-preview-button>Előnézet</button>
                      <div class="preview-popover" data-preview-popover></div>
                    </div>
                    <button class="danger" type="button" data-remove>Törlés</button>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </section>

        <section class="panel" data-section="faq">
          <div class="panel-head">
            <div>
              <h2>GYIK</h2>
              <p class="hint">Gyakori kérdések és rövid, vendégeknek szóló válaszok.</p>
            </div>
            <button type="button" data-add="faq">Sor hozzáadása</button>
          </div>
          <div class="panel-body">
            <div class="repeater" data-repeater="faq">
              @foreach ($content['faq'] as $index => $faq)
                <div class="row" data-preview="faq">
                  <label>
                    Kérdés
                    <input name="content[faq][{{ $index }}][question]" value="{{ $faq['question'] ?? '' }}" data-field="question" />
                  </label>
                  <label class="full">
                    Válasz
                    <textarea name="content[faq][{{ $index }}][answer]" data-field="answer">{{ $faq['answer'] ?? '' }}</textarea>
                  </label>
                  <div class="row-actions">
                    <div class="preview-wrap">
                    <button class="secondary" type="button" data-preview-button>Előnézet</button>
                      <div class="preview-popover" data-preview-popover></div>
                    </div>
                    <button class="danger" type="button" data-remove>Törlés</button>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </section>

        <div class="save-bar" id="saveBar">
          <button type="submit">Mentés</button>
        </div>
      </form>

      <section class="panel" data-section="backups">
        <div class="panel-head">
          <div>
            <h2>Legutóbbi mentések</h2>
            <p class="hint">Mentés előtt automatikusan készül biztonsági másolat. A legutóbbi 20 marad meg.</p>
          </div>
        </div>
        <div class="panel-body">
          @if (count($backups) === 0)
            <p class="hint">Még nincs visszaállítható mentés.</p>
          @else
            <div class="backup-list">
              @foreach ($backups as $backup)
                <div class="backup-item">
                  <div>
                    <strong>{{ $backup['name'] }}</strong>
                    <span>{{ $backup['date'] }}</span>
                  </div>
                  <form method="post" action="{{ route('admin.restore') }}" data-skip-dirty>
                    @csrf
                    <input type="hidden" name="backup" value="{{ $backup['name'] }}" />
                    <button class="secondary" type="submit">Visszaállítás</button>
                  </form>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </section>
    </main>

    <template id="serviceTemplate">
      <div class="row" data-preview="service">
        <label>Cím<input name="content[services][__INDEX__][title]" data-field="title" /></label>
        <label>Ár<input name="content[services][__INDEX__][price]" placeholder="14 000 Ft" data-field="price" /></label>
        <label class="full">Leírás<textarea name="content[services][__INDEX__][description]" data-field="description"></textarea></label>
        <label class="image-route-field">Kép útvonala<span class="image-route-tools"><input name="content[services][__INDEX__][image]" placeholder="assets/services/2d-volume.png" data-field="image" /><span class="image-dropzone" role="button" tabindex="0" data-image-dropzone data-image-folder="assets/services">Ide húzd a képet</span></span><span class="field-help">Kép útvonala például: assets/services/2d-volume.png</span></label>
        <label>Kép alt szöveg<input name="content[services][__INDEX__][alt]" /></label>
        <div class="row-actions">
          <div class="preview-wrap"><button class="secondary" type="button" data-preview-button>Előnézet</button><div class="preview-popover" data-preview-popover></div></div>
          <button class="danger" type="button" data-remove>Törlés</button>
        </div>
      </div>
    </template>

    <template id="galleryTemplate">
      <div class="row" data-preview="gallery">
        <label class="image-route-field">Kép útvonala<span class="image-route-tools"><input name="content[gallery][__INDEX__][image]" placeholder="assets/gallery/kep-neve.jpg" data-field="image" /><span class="image-dropzone" role="button" tabindex="0" data-image-dropzone data-image-folder="assets/gallery">Ide húzd a képet</span></span><span class="field-help">Kép útvonala például: assets/gallery/kep-neve.jpg</span></label>
        <label>Kép alt szöveg<input name="content[gallery][__INDEX__][alt]" data-field="alt" /></label>
        <div class="row-actions">
          <div class="preview-wrap"><button class="secondary" type="button" data-preview-button>Előnézet</button><div class="preview-popover" data-preview-popover></div></div>
          <button class="danger" type="button" data-remove>Törlés</button>
        </div>
      </div>
    </template>

    <template id="priceGroupTemplate">
      <div class="price-group-editor" data-price-group>
        <label>Csoport címe<input name="content[price_groups][__GROUP__][title]" data-field="group-title" /></label>
        <div class="repeater" data-repeater="price-items"></div>
        <div class="row-actions">
          <button class="secondary" type="button" data-add="price-item">Sor hozzáadása</button>
          <button class="danger" type="button" data-remove-group>Csoport törlése</button>
        </div>
      </div>
    </template>

    <template id="priceItemTemplate">
      <div class="price-row" data-preview="price">
        <label>Szolgáltatás<input name="content[price_groups][__GROUP__][items][__ITEM__][name]" data-field="name" /></label>
        <label>Ár<input name="content[price_groups][__GROUP__][items][__ITEM__][price]" placeholder="14 000 Ft" data-field="price" /></label>
        <div class="row-actions">
          <div class="preview-wrap"><button class="secondary" type="button" data-preview-button>Előnézet</button><div class="preview-popover" data-preview-popover></div></div>
          <button class="danger" type="button" data-remove>Törlés</button>
        </div>
      </div>
    </template>

    <template id="benefitTemplate">
      <div class="row" data-preview="benefit">
        <label>Cím<input name="content[benefits][__INDEX__][title]" data-field="title" /></label>
        <label class="full">Leírás<textarea name="content[benefits][__INDEX__][description]" data-field="description"></textarea></label>
        <div class="row-actions">
          <div class="preview-wrap"><button class="secondary" type="button" data-preview-button>Előnézet</button><div class="preview-popover" data-preview-popover></div></div>
          <button class="danger" type="button" data-remove>Törlés</button>
        </div>
      </div>
    </template>

    <template id="testimonialTemplate">
      <div class="row" data-preview="testimonial">
        <label>Név<input name="content[testimonials][__INDEX__][name]" data-field="name" /></label>
        <label class="full">Vélemény<textarea name="content[testimonials][__INDEX__][quote]" data-field="quote"></textarea></label>
        <div class="row-actions">
          <div class="preview-wrap"><button class="secondary" type="button" data-preview-button>Előnézet</button><div class="preview-popover" data-preview-popover></div></div>
          <button class="danger" type="button" data-remove>Törlés</button>
        </div>
      </div>
    </template>

    <template id="faqTemplate">
      <div class="row" data-preview="faq">
        <label>Kérdés<input name="content[faq][__INDEX__][question]" data-field="question" /></label>
        <label class="full">Válasz<textarea name="content[faq][__INDEX__][answer]" data-field="answer"></textarea></label>
        <div class="row-actions">
          <div class="preview-wrap"><button class="secondary" type="button" data-preview-button>Előnézet</button><div class="preview-popover" data-preview-popover></div></div>
          <button class="danger" type="button" data-remove>Törlés</button>
        </div>
      </div>
    </template>

    <script>
      const sectionPicker = document.querySelector("#sectionPicker");
      const editorForm = document.querySelector("#contentForm");
      const saveBar = document.querySelector("#saveBar");
      let isDirty = false;
      let isSubmitting = false;

      const markDirty = () => {
        isDirty = true;
      };

      editorForm?.addEventListener("input", markDirty);
      editorForm?.addEventListener("change", markDirty);
      editorForm?.addEventListener("submit", () => {
        isSubmitting = true;
      });

      document.querySelectorAll("[data-skip-dirty]").forEach((form) => {
        form.addEventListener("submit", () => {
          isSubmitting = true;
        });
      });

      window.addEventListener("beforeunload", (event) => {
        if (!isDirty || isSubmitting) {
          return;
        }

        event.preventDefault();
        event.returnValue = "";
      });

      sectionPicker?.addEventListener("change", () => {
        document.querySelectorAll("[data-section]").forEach((panel) => {
          panel.classList.toggle("is-active", panel.dataset.section === sectionPicker.value);
        });

        if (saveBar) {
          saveBar.hidden = sectionPicker.value === "backups";
        }
      });

      const nextIndex = (container, pattern) => {
        const names = [...container.querySelectorAll("[name]")].map((input) => input.name);
        const values = names
          .map((name) => name.match(pattern)?.[1])
          .filter((value) => value !== undefined)
          .map(Number);

        return values.length ? Math.max(...values) + 1 : 0;
      };

      const fragmentFromTemplate = (id, replacements) => {
        let html = document.querySelector(id).innerHTML;
        Object.entries(replacements).forEach(([key, value]) => {
          html = html.replaceAll(key, String(value));
        });

        const wrapper = document.createElement("div");
        wrapper.innerHTML = html.trim();
        return wrapper.firstElementChild;
      };

      const addRow = (type, trigger) => {
        if (type === "service") {
          const container = document.querySelector('[data-repeater="services"]');
          container.append(fragmentFromTemplate("#serviceTemplate", { __INDEX__: nextIndex(container, /content\[services]\[(\d+)]/) }));
        }

        if (type === "benefit") {
          const container = document.querySelector('[data-repeater="benefits"]');
          container.append(fragmentFromTemplate("#benefitTemplate", { __INDEX__: nextIndex(container, /content\[benefits]\[(\d+)]/) }));
        }

        if (type === "gallery") {
          const container = document.querySelector('[data-repeater="gallery"]');
          container.append(fragmentFromTemplate("#galleryTemplate", { __INDEX__: nextIndex(container, /content\[gallery]\[(\d+)]/) }));
        }

        if (type === "testimonial") {
          const container = document.querySelector('[data-repeater="testimonials"]');
          container.append(fragmentFromTemplate("#testimonialTemplate", { __INDEX__: nextIndex(container, /content\[testimonials]\[(\d+)]/) }));
        }

        if (type === "faq") {
          const container = document.querySelector('[data-repeater="faq"]');
          container.append(fragmentFromTemplate("#faqTemplate", { __INDEX__: nextIndex(container, /content\[faq]\[(\d+)]/) }));
        }

        if (type === "price-group") {
          const container = document.querySelector('[data-repeater="price-groups"]');
          const groupIndex = nextIndex(container, /content\[price_groups]\[(\d+)]/);
          const group = fragmentFromTemplate("#priceGroupTemplate", { __GROUP__: groupIndex });
          container.append(group);
          addRow("price-item", group.querySelector('[data-add="price-item"]'));
        }

        if (type === "price-item") {
          const group = trigger.closest("[data-price-group]");
          const groupInput = group.querySelector('[name*="[title]"]');
          const groupIndex = groupInput.name.match(/content\[price_groups]\[(\d+)]/)?.[1] ?? 0;
          const container = group.querySelector('[data-repeater="price-items"]');
          const itemIndex = nextIndex(container, /items]\[(\d+)]/);
          container.append(fragmentFromTemplate("#priceItemTemplate", { __GROUP__: groupIndex, __ITEM__: itemIndex }));
        }
      };

      document.addEventListener("click", (event) => {
        const addButton = event.target.closest("[data-add]");
        if (addButton) {
          addRow(addButton.dataset.add, addButton);
          markDirty();
          return;
        }

        const removeButton = event.target.closest("[data-remove]");
        if (removeButton) {
          removeButton.closest(".row, .price-row")?.remove();
          markDirty();
          return;
        }

        const removeGroupButton = event.target.closest("[data-remove-group]");
        if (removeGroupButton) {
          removeGroupButton.closest("[data-price-group]")?.remove();
          markDirty();
        }
      });

      const cleanImagePath = (path) => path
        .trim()
        .replace(/^https?:\/\/[^/]+\//, "")
        .replace(/^\/+/, "")
        .replace(/^public\//, "");

      const applyDroppedImagePath = (dropzone, dataTransfer) => {
        const input = dropzone.closest(".image-route-field")?.querySelector('[data-field="image"]');

        if (!input) {
          return;
        }

        const file = dataTransfer.files?.[0];
        const droppedText = dataTransfer.getData("text/plain");
        const folder = dropzone.dataset.imageFolder || "assets/services";
        const nextPath = file?.name ? `${folder}/${file.name}` : cleanImagePath(droppedText);

        if (!nextPath) {
          return;
        }

        input.value = nextPath;
        input.dispatchEvent(new Event("input", { bubbles: true }));
      };

      document.addEventListener("dragover", (event) => {
        const dropzone = event.target.closest("[data-image-dropzone]");

        if (!dropzone) {
          return;
        }

        event.preventDefault();
        dropzone.classList.add("is-over");
      });

      document.addEventListener("dragleave", (event) => {
        event.target.closest("[data-image-dropzone]")?.classList.remove("is-over");
      });

      document.addEventListener("drop", (event) => {
        const dropzone = event.target.closest("[data-image-dropzone]");

        if (!dropzone) {
          return;
        }

        event.preventDefault();
        dropzone.classList.remove("is-over");
        applyDroppedImagePath(dropzone, event.dataTransfer);
      });

      const fieldValue = (container, field) => container.querySelector(`[data-field="${field}"]`)?.value.trim() || "";
      const escapeHtml = (value) => value.replace(/[&<>"']/g, (character) => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#039;",
      })[character]);

      const previewHtml = (row) => {
        const type = row.dataset.preview;

        if (type === "service") {
          const image = fieldValue(row, "image").replace(/^\/+/, "");
          const imageHtml = image ? `<img src="/${encodeURI(image.replace(/["'<>]/g, ""))}" alt="" onerror="this.remove()">` : "";

          return `${imageHtml}<h4>${escapeHtml(fieldValue(row, "title") || "Szolgáltatás")}</h4><p>${escapeHtml(fieldValue(row, "description") || "Leírás")}</p><strong>${escapeHtml(fieldValue(row, "price") || "Ár")}</strong>`;
        }

        if (type === "price") {
          return `<h4>${escapeHtml(fieldValue(row, "name") || "Szolgáltatás")}</h4><strong>${escapeHtml(fieldValue(row, "price") || "Ár")}</strong>`;
        }

        if (type === "gallery") {
          const image = fieldValue(row, "image").replace(/^\/+/, "");
          const imageHtml = image ? `<img src="/${encodeURI(image.replace(/["'<>]/g, ""))}" alt="" onerror="this.remove()">` : "";

          return `${imageHtml}<h4>Galéria kép</h4><p>${escapeHtml(fieldValue(row, "alt") || "Alt szöveg")}</p>`;
        }

        if (type === "benefit") {
          return `<h4>${escapeHtml(fieldValue(row, "title") || "Előny")}</h4><p>${escapeHtml(fieldValue(row, "description") || "Leírás")}</p>`;
        }

        if (type === "testimonial") {
          return `<p>“${escapeHtml(fieldValue(row, "quote") || "Vélemény")}”</p><strong>${escapeHtml(fieldValue(row, "name") || "Név")}</strong>`;
        }

        if (type === "faq") {
          return `<h4>${escapeHtml(fieldValue(row, "question") || "Kérdés")}</h4><p>${escapeHtml(fieldValue(row, "answer") || "Válasz")}</p>`;
        }

        return "<p>Nincs előnézet.</p>";
      };

      document.addEventListener("mouseover", (event) => {
        const button = event.target.closest("[data-preview-button]");
        if (!button) {
          return;
        }

        const row = button.closest("[data-preview]");
        const popover = button.parentElement.querySelector("[data-preview-popover]");
        popover.innerHTML = previewHtml(row);
      });

      document.addEventListener("focusin", (event) => {
        const button = event.target.closest("[data-preview-button]");
        if (!button) {
          return;
        }

        const row = button.closest("[data-preview]");
        const popover = button.parentElement.querySelector("[data-preview-popover]");
        popover.innerHTML = previewHtml(row);
      });
    </script>
  </body>
</html>
