import fs from "node:fs";
import path from "node:path";
import { fileURLToPath } from "node:url";

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), "..");
const dist = path.join(root, "dist");

const readJson = (relativePath) => JSON.parse(fs.readFileSync(path.join(root, relativePath), "utf8"));
const escapeHtml = (value = "") =>
  String(value).replace(/[&<>"']/g, (character) => ({
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  })[character]);

const content = readJson("storage/app/site-content.json");
const manifest = readJson("public/build/manifest.json");
const cssFile = manifest["resources/css/app.css"]?.file;
const jsFile = manifest["resources/js/app.js"]?.file;

if (!cssFile || !jsFile) {
  throw new Error("Vite manifest is missing app CSS or JS. Run npm run build first.");
}

const asset = (assetPath) => `/${String(assetPath).replace(/^\/+/, "")}`;

const gallery = content.gallery ?? [
  { image: "assets/services/2d-volume.png", alt: "Finom 2D styling közeli képe" },
  { image: "assets/services/3d-volume.png", alt: "Elegáns volume eredmény" },
  { image: "assets/services/classic.png", alt: "Természetes hatású styling" },
];

const renderServices = () => (content.services ?? []).map((service) => `
            <article class="service-card">
              ${service.image ? `<img class="service-art" src="${asset(service.image)}" alt="${escapeHtml(service.alt)}" />` : ""}
              <h3>${escapeHtml(service.title)}</h3>
              <p>${escapeHtml(service.description)}</p>
              <span>${escapeHtml(service.price)}</span>
            </article>`).join("");

const renderPriceGroups = () => (content.price_groups ?? []).map((group) => `
            <div class="price-group">
              <h3>${escapeHtml(group.title)}</h3>
              <dl class="price-list">
                ${(group.items ?? []).map((item) => `
                  <div>
                    <dt>${escapeHtml(item.name)}</dt>
                    <dd>${escapeHtml(item.price)}</dd>
                  </div>`).join("")}
              </dl>
            </div>`).join("");

const renderGallery = () => gallery.map((image) => `
            <figure class="gallery-tile">
              <img src="${asset(image.image)}" alt="${escapeHtml(image.alt)}" loading="lazy" />
            </figure>`).join("");

const renderBenefits = () => (content.benefits ?? []).map((benefit) => `
            <article class="benefit-card">
              <h3>${escapeHtml(benefit.title)}</h3>
              <p>${escapeHtml(benefit.description)}</p>
            </article>`).join("");

const renderTestimonials = () => (content.testimonials ?? []).map((testimonial) => `
            <figure>
              <blockquote>${escapeHtml(testimonial.quote)}</blockquote>
              <figcaption>${escapeHtml(testimonial.name)}</figcaption>
            </figure>`).join("");

const renderFaq = () => (content.faq ?? []).map((faq) => `
                <div>
                  <h3>${escapeHtml(faq.question)}</h3>
                  <p>${escapeHtml(faq.answer)}</p>
                </div>`).join("");

let html = fs.readFileSync(path.join(root, "resources/views/welcome.blade.php"), "utf8");

html = html
  .replace(
    "@vite(['resources/css/app.css', 'resources/js/app.js'])",
    `<link rel="stylesheet" href="/build/${cssFile}" />\n    <script type="module" src="/build/${jsFile}"></script>`
  )
  .replace(/\{\{\s*asset\('([^']+)'\)\s*\}\}/g, (_, assetPath) => asset(assetPath))
  .replace(/@foreach \(\$content\['services'\] as \$service\)[\s\S]*?@endforeach/, renderServices())
  .replace(/<div class="price-card" aria-label="Brill Lash and Beauty árlista">[\s\S]*?^\s*<\/div>\n\s*<\/section>/m, `<div class="price-card" aria-label="Brill Lash and Beauty árlista">${renderPriceGroups()}
        </div>
      </section>`)
  .replace(/@foreach \(\$content\['gallery'\] as \$image\)[\s\S]*?@endforeach/, renderGallery())
  .replace(/@foreach \(\$content\['benefits'\] as \$benefit\)[\s\S]*?@endforeach/, renderBenefits())
  .replace(/@foreach \(\$content\['testimonials'\] as \$testimonial\)[\s\S]*?@endforeach/, renderTestimonials())
  .replace(/@foreach \(\$content\['faq'\] as \$faq\)[\s\S]*?@endforeach/, renderFaq());

if (/@(foreach|endforeach|if|endif|vite|csrf|error)\b/.test(html) || html.includes("{{")) {
  throw new Error("Static export still contains Blade syntax.");
}

fs.rmSync(dist, { recursive: true, force: true });
fs.mkdirSync(dist, { recursive: true });
fs.writeFileSync(path.join(dist, "index.html"), html);
fs.writeFileSync(path.join(dist, "_redirects"), "/* /index.html 200\n");
fs.cpSync(path.join(root, "public/assets"), path.join(dist, "assets"), { recursive: true });
fs.cpSync(path.join(root, "public/build"), path.join(dist, "build"), { recursive: true });

console.log("Netlify static export written to dist/");
