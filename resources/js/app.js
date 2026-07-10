const toggle = document.querySelector(".menu-toggle");
const nav = document.querySelector(".main-nav");
const brandLink = document.querySelector(".brand");

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
  "section:not(.hero), .service-card, .price-group, .benefit-card, .testimonial-grid figure, .gallery-tile, .accordion"
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
