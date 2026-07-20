const toggle = document.querySelector(".menu-toggle");
const nav = document.querySelector(".main-nav");
const brandLink = document.querySelector(".brand");
const hero = document.querySelector(".hero");
const services = document.querySelector("#szolgaltatasok");

if ("scrollRestoration" in history) {
  history.scrollRestoration = "manual";
}

const scrollHomeToTop = () => {
  if (!window.location.hash || window.location.hash === "#fooldal") {
    window.scrollTo({ top: 0, left: 0, behavior: "auto" });
  }
};

scrollHomeToTop();
requestAnimationFrame(scrollHomeToTop);
setTimeout(scrollHomeToTop, 0);
window.addEventListener("load", scrollHomeToTop);
window.addEventListener("pageshow", scrollHomeToTop);

const syncStickyCta = () => {
  if (!services && !hero) {
    document.body.classList.add("sticky-cta-visible");
    return;
  }

  const threshold = services ? services.offsetTop - window.innerHeight * 0.35 : hero.offsetHeight * 1.1;
  document.body.classList.toggle("sticky-cta-visible", window.scrollY > threshold);
};

syncStickyCta();
window.addEventListener("scroll", syncStickyCta, { passive: true });
window.addEventListener("resize", syncStickyCta);

if (brandLink instanceof HTMLAnchorElement) {
  brandLink.addEventListener("click", (event) => {
    event.preventDefault();
    history.replaceState(null, "", `${window.location.pathname}${window.location.search}`);
    window.scrollTo({ top: 0, left: 0, behavior: "smooth" });
    document.body.classList.remove("nav-open");
    if (toggle) {
      toggle.setAttribute("aria-expanded", "false");
      toggle.setAttribute("aria-label", "Menü megnyitása");
    }
  });
}

if (toggle && nav) {
  toggle.addEventListener("click", () => {
    const isOpen = document.body.classList.toggle("nav-open");
    toggle.setAttribute("aria-expanded", String(isOpen));
    toggle.setAttribute("aria-label", isOpen ? "Menü bezárása" : "Menü megnyitása");
  });

  nav.addEventListener("click", (event) => {
    if (event.target instanceof HTMLAnchorElement) {
      document.body.classList.remove("nav-open");
      toggle.setAttribute("aria-expanded", "false");
      toggle.setAttribute("aria-label", "Menü megnyitása");
    }
  });
}

const motionQuery = window.matchMedia("(prefers-reduced-motion: reduce)");
const revealItems = document.querySelectorAll(
  "section:not(.hero), .service-card, .price-group, .benefit-card, .testimonial-grid figure, .gallery-tile, .accordion, .feature-list span"
);

if (motionQuery.matches) {
  revealItems.forEach((item) => item.classList.add("is-visible"));
} else if ("IntersectionObserver" in window) {
  revealItems.forEach((item) => item.classList.add("revealable"));

  const revealObserver = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) {
          return;
        }

        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      });
    },
    { rootMargin: "0px 0px -10% 0px", threshold: 0.12 }
  );

  revealItems.forEach((item) => revealObserver.observe(item));
}

const lightbox = document.querySelector(".lightbox");
const lightboxImage = lightbox?.querySelector("img");
const lightboxClose = lightbox?.querySelector(".lightbox-close");
const galleryButtons = document.querySelectorAll("[data-lightbox-src]");

const closeLightbox = () => {
  if (!lightbox || !lightboxImage) {
    return;
  }

  lightbox.hidden = true;
  lightboxImage.setAttribute("src", "");
  lightboxImage.setAttribute("alt", "");
  document.body.classList.remove("lightbox-open");
};

galleryButtons.forEach((button) => {
  button.addEventListener("click", () => {
    if (!lightbox || !lightboxImage) {
      return;
    }

    lightboxImage.setAttribute("src", button.dataset.lightboxSrc || "");
    lightboxImage.setAttribute("alt", button.dataset.lightboxAlt || "");
    lightbox.hidden = false;
    document.body.classList.add("lightbox-open");
  });
});

lightboxClose?.addEventListener("click", closeLightbox);
lightbox?.addEventListener("click", (event) => {
  if (event.target === lightbox) {
    closeLightbox();
  }
});
document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    closeLightbox();
  }
});
