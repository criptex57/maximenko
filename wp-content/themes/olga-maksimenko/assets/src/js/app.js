import '../css/app.css';
import '../css/art-direction.css';
import '../css/hero-refined.css';
import Lenis from 'lenis';
import 'lenis/dist/lenis.css';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const header = document.querySelector('[data-header]');
let smoothScroll = null;
window.addEventListener('scroll', () => header?.classList.toggle('is-scrolled', window.scrollY > 20), { passive: true });

const heroPortraitImage = document.querySelector('.hero-refined__portrait img');
if (heroPortraitImage) {
  const showHeroPortrait = () => heroPortraitImage.classList.add('is-loaded');
  if (heroPortraitImage.complete && heroPortraitImage.naturalWidth) {
    heroPortraitImage.decode?.().then(showHeroPortrait).catch(showHeroPortrait);
  } else {
    heroPortraitImage.addEventListener('load', showHeroPortrait, { once: true });
  }
}

const splitWords = (element) => {
  const walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT);
  const nodes = [];
  while (walker.nextNode()) nodes.push(walker.currentNode);
  nodes.forEach((node) => {
    if (!node.textContent.trim()) return;
    const fragment = document.createDocumentFragment();
    node.textContent.split(/(\s+)/).forEach((part) => {
      if (!part.trim()) {
        fragment.appendChild(document.createTextNode(part));
        return;
      }
      const outer = document.createElement('span');
      const inner = document.createElement('span');
      outer.className = 'split-word';
      inner.className = 'split-word-inner';
      inner.textContent = part;
      outer.appendChild(inner);
      fragment.appendChild(outer);
    });
    node.replaceWith(fragment);
  });
};
document.querySelectorAll('[data-split]').forEach(splitWords);

const waitForImage = (image) => new Promise((resolve) => {
  const decode = (target) => target.decode?.().catch(() => {}).finally(resolve) || resolve();
  if (image.complete && image.naturalWidth) {
    decode(image);
    return;
  }
  const preload = new Image();
  preload.decoding = 'async';
  preload.onload = () => decode(preload);
  preload.onerror = resolve;
  preload.src = image.currentSrc || image.src;
});

const waitForVisualAssets = async (root = document) => {
  const images = [...root.querySelectorAll('img')];
  images.forEach((image) => { image.loading = 'eager'; });
  await Promise.all([document.fonts?.ready, ...images.map(waitForImage)]);
  await new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(resolve)));
};

let horizontalTimeline = null;

const waitForScrollIdle = () => new Promise((resolve) => {
  if (!smoothScroll?.isScrolling) {
    resolve();
    return;
  }

  let idleTimer = 0;
  const unsubscribe = smoothScroll.on('scroll', (lenis) => {
    window.clearTimeout(idleTimer);
    if (lenis.isScrolling) return;
    idleTimer = window.setTimeout(() => {
      unsubscribe();
      resolve();
    }, 80);
  });
});

const initHorizontalStory = () => {
  const section = document.querySelector('[data-horizontal]');
  const track = section?.querySelector('.projects-track');
  const viewport = section?.querySelector('.projects-story__pin');
  const intro = section?.querySelector('.projects-story__intro');
  const progress = section?.querySelector('.projects-story__progress i');
  if (!section || !track || !viewport || !intro || window.innerWidth <= 767) return;

  horizontalTimeline?.scrollTrigger?.kill();
  horizontalTimeline?.kill();

  const getDistance = () => Math.max(1, Math.ceil(track.scrollWidth - (viewport.clientWidth - intro.offsetWidth)));
  const projectImages = gsap.utils.toArray(track.querySelectorAll('.story-card__image img'));

  gsap.set(projectImages, {
    xPercent: (index) => index % 2 ? -6 : 6,
    force3D: true
  });

  horizontalTimeline = gsap.timeline({
    defaults: { ease: 'none' },
    scrollTrigger: {
      trigger: section,
      start: 'top top+=70',
      end: () => `+=${getDistance()}`,
      pin: true,
      pinSpacing: true,
      scrub: true,
      anticipatePin: 1,
      invalidateOnRefresh: true,
      refreshPriority: 20
    }
  });

  horizontalTimeline.to(track, { x: () => -getDistance(), force3D: true, duration: 1 }, 0);
  horizontalTimeline.to(projectImages, {
    xPercent: (index) => index % 2 ? 6 : -6,
    force3D: true,
    duration: 1
  }, 0);
  if (progress) horizontalTimeline.to(progress, { '--progress': 1, duration: 1 }, 0);

  waitForVisualAssets(section).then(async () => {
    await waitForScrollIdle();
    smoothScroll.resize();
    ScrollTrigger.refresh();
  });
};

const initMotion = () => {
  if (reducedMotion) return;

  smoothScroll = new Lenis({ duration: 1.2, smoothWheel: true, wheelMultiplier: 0.85, autoRaf: false });
  smoothScroll.on('scroll', ScrollTrigger.update);
  gsap.ticker.add((time) => smoothScroll.raf(time * 1000));
  ScrollTrigger.config({ ignoreMobileResize: true });
  ScrollTrigger.addEventListener('refreshInit', () => smoothScroll.resize());

  const hero = document.querySelector('[data-hero]');
  if (hero) {
    const heroTimeline = gsap.timeline({ defaults: { ease: 'power3.out' } });
    heroTimeline
      .from('.hero-refined__portrait', {
        clipPath: 'inset(0 0 100% 0 round 46% 46% 0 0)',
        scale: 1.04,
        duration: 1.9,
        onComplete: () => gsap.set('.hero-refined__portrait', { clearProps: 'clipPath' })
      })
      .from('.hero-refined__portrait img', { scale: 1.13, duration: 2.2 }, 0)
      .from('.hero-refined__title > span', { yPercent: 115, opacity: 0, stagger: 0.16, duration: 1.15 }, 0.55)
      .from('.hero-refined__copy p', { y: 24, opacity: 0, duration: 1 }, 1.05)
      .from('.hero-refined__cta', { y: 18, opacity: 0, duration: 1 }, 1.22);

    gsap.to('.hero-refined__portrait img', { yPercent: 8, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: 1.2 } });
    gsap.to('.hero-refined__copy', { yPercent: -10, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: 1.2 } });
    gsap.to('.hero-refined__light', { scale: 1.2, opacity: 0.45, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: 1.2 } });
  }

  document.querySelectorAll('[data-split]').forEach((element) => {
    gsap.from(element.querySelectorAll('.split-word-inner'), {
      yPercent: 120,
      rotate: 3,
      stagger: 0.035,
      duration: 1.05,
      ease: 'power4.out',
      scrollTrigger: { trigger: element, start: 'top 84%', toggleActions: 'play none none reverse' }
    });
  });

  gsap.utils.toArray('.reveal').forEach((element, index) => {
    gsap.from(element, { y: 80 + (index % 3) * 20, opacity: 0, rotateX: 8, duration: 1.1, ease: 'power3.out', scrollTrigger: { trigger: element, start: 'top 88%' } });
  });
  gsap.utils.toArray('.reveal-image').forEach((element, index) => {
    gsap.fromTo(element, { clipPath: index % 2 ? 'inset(0 100% 0 0)' : 'inset(100% 0 0 0)' }, { clipPath: 'inset(0% 0 0 0)', duration: 1.4, ease: 'expo.inOut', scrollTrigger: { trigger: element, start: 'top 90%' } });
  });

  document.querySelectorAll('.collage-photo').forEach((photo, index) => {
    gsap.from(photo, {
      y: 180 * (index + 1),
      rotate: index % 2 ? 5 : -4,
      clipPath: 'inset(25% 15%)',
      ease: 'none',
      scrollTrigger: { trigger: '[data-manifesto]', start: 'top 70%', end: 'bottom 70%', scrub: 0.8 }
    });
    gsap.to(photo.querySelector('img'), { yPercent: index % 2 ? -10 : 12, ease: 'none', scrollTrigger: { trigger: photo, start: 'top bottom', end: 'bottom top', scrub: 0.8 } });
  });
  gsap.from('.manifesto__stats > div', { yPercent: 100, stagger: 0.15, duration: 1, ease: 'power3.out', scrollTrigger: { trigger: '.manifesto__stats', start: 'top 90%' } });

  initHorizontalStory();

  gsap.from('[data-service-row]', {
    xPercent: 18,
    opacity: 0,
    stagger: 0.11,
    duration: 1,
    ease: 'power3.out',
    scrollTrigger: { trigger: '[data-services] .services-list', start: 'top 80%' }
  });

  const portal = document.querySelector('[data-portal]');
  if (portal) {
    const portalTimeline = gsap.timeline({ scrollTrigger: { trigger: portal, start: 'top 85%', end: 'bottom bottom', scrub: 0.8, invalidateOnRefresh: true } });
    portalTimeline
      .to('.portal__frame img', { scale: 1.05, clipPath: 'inset(0% 0%)', filter: 'saturate(1) brightness(.9)', duration: 1, ease: 'none' }, 0)
      .fromTo('.portal__word--one', { xPercent: -28 }, { xPercent: -2, duration: 0.55, ease: 'power1.out' }, 0)
      .to('.portal__word--one', { xPercent: 4, duration: 0.45, ease: 'none' }, 0.55)
      .fromTo('.portal__word--two', { xPercent: 28 }, { xPercent: 2, duration: 0.55, ease: 'power1.out' }, 0)
      .to('.portal__word--two', { xPercent: -4, duration: 0.45, ease: 'none' }, 0.55)
      .to('.portal svg', { rotate: 12, scale: 1.18, opacity: 0.12, transformOrigin: 'center', duration: 1, ease: 'none' }, 0);
  }

  document.querySelectorAll('.process-list li').forEach((row, index) => {
    gsap.from(row.querySelector('span'), { xPercent: index % 2 ? 70 : -70, opacity: 0, ease: 'none', scrollTrigger: { trigger: row, start: 'top bottom', end: 'center center', scrub: 0.7 } });
    gsap.from(row.querySelector('h3'), { x: index % 2 ? -100 : 100, opacity: 0, duration: 1, ease: 'power3.out', scrollTrigger: { trigger: row, start: 'top 82%' } });
  });

  const comparison = document.querySelector('[data-comparison]');
  if (comparison) {
    gsap.from('.before-after', { scale: 0.72, rotateX: 8, clipPath: 'inset(16% 12%)', duration: 1.4, ease: 'power4.out', scrollTrigger: { trigger: comparison, start: 'top 76%' } });
    gsap.to('.comparison__ghost', { xPercent: -20, ease: 'none', scrollTrigger: { trigger: comparison, start: 'top bottom', end: 'bottom top', scrub: 1 } });
  }

  document.querySelectorAll('[data-depth-scene]').forEach((scene) => {
    scene.querySelectorAll('[data-depth]').forEach((layer) => {
      const depth = Number(layer.dataset.depth || 0.1);
      gsap.to(layer, { y: -220 * depth, ease: 'none', scrollTrigger: { trigger: scene, start: 'top bottom', end: 'bottom top', scrub: 1 } });
    });
  });

  if (window.matchMedia('(pointer:fine)').matches) {
    document.addEventListener('mousemove', (event) => {
      const x = event.clientX / window.innerWidth - 0.5;
      const y = event.clientY / window.innerHeight - 0.5;
      document.querySelectorAll('[data-hero] [data-depth]').forEach((layer) => {
        const depth = Number(layer.dataset.depth || 0.1);
        gsap.to(layer, { x: x * 140 * depth, y: y * 100 * depth, duration: 1.4, ease: 'power3.out', overwrite: 'auto' });
      });
    });
  }

};

initMotion();

if (!reducedMotion && window.matchMedia('(pointer:fine)').matches) {
  document.querySelectorAll('.magnetic').forEach((element) => {
    element.addEventListener('mousemove', (event) => {
      const bounds = element.getBoundingClientRect();
      gsap.to(element, { x: (event.clientX - bounds.left - bounds.width / 2) * 0.22, y: (event.clientY - bounds.top - bounds.height / 2) * 0.22, duration: 0.45, ease: 'power3.out' });
    });
    element.addEventListener('mouseleave', () => gsap.to(element, { x: 0, y: 0, duration: 0.7, ease: 'elastic.out(1,.35)' }));
  });
}

document.querySelectorAll('[data-transition-link]').forEach((link) => {
  link.addEventListener('click', (event) => {
    if (reducedMotion || event.metaKey || event.ctrlKey) return;
    event.preventDefault();
    const overlay = document.querySelector('.page-transition');
    overlay.style.visibility = 'visible';
    gsap.to(overlay, {
      clipPath: 'inset(0% 0 0)',
      duration: 0.75,
      ease: 'power4.inOut',
      onComplete: () => { window.location.href = link.href; }
    });
  });
});

const menuButton = document.querySelector('[data-menu-toggle]');
const mobileMenu = document.querySelector('[data-mobile-menu]');
const setMenu = (open) => {
  menuButton?.setAttribute('aria-expanded', String(open));
  mobileMenu?.setAttribute('aria-hidden', String(!open));
  document.body.classList.toggle('menu-open', open);
  if (open) smoothScroll?.stop();
  else if (!document.body.classList.contains('modal-open')) smoothScroll?.start();
};
menuButton?.addEventListener('click', () => setMenu(menuButton.getAttribute('aria-expanded') !== 'true'));
mobileMenu?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setMenu(false)));

const scrollToSection = (section, immediate = false) => {
  const target = document.getElementById(section);
  if (!target) return;
  smoothScroll?.scrollTo(target, { offset: -70, immediate, force: true });
};

document.querySelectorAll('[data-section-link]').forEach((link) => {
  link.addEventListener('click', (event) => {
    const section = link.dataset.sectionLink;
    if (!document.getElementById(section)) return;
    event.preventDefault();
    setMenu(false);
    window.history.pushState({ olgaSection: section }, '', link.href);
    scrollToSection(section);
  });
});

if (window.olgaSite?.initialSection) {
  window.history.replaceState({ olgaSection: window.olgaSite.initialSection }, '', window.location.href);
  requestAnimationFrame(() => scrollToSection(window.olgaSite.initialSection, true));
}

window.addEventListener('popstate', (event) => {
  if (event.state?.olgaSection) scrollToSection(event.state.olgaSection);
});

document.querySelectorAll('[data-before-after]').forEach((component) => {
  const input = component.querySelector('input[type="range"]');
  input?.addEventListener('input', () => component.style.setProperty('--position', input.value + '%'));
});

const modal = document.querySelector('[data-contact-modal]');
let lastFocus = null;
const focusableSelector = 'button, a[href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
const setModal = (open) => {
  if (!modal) return;
  modal.setAttribute('aria-hidden', String(!open));
  document.body.classList.toggle('modal-open', open);
  if (open) smoothScroll?.stop();
  else if (!document.body.classList.contains('menu-open')) smoothScroll?.start();
  if (open) {
    lastFocus = document.activeElement;
    setTimeout(() => modal.querySelector('input')?.focus(), 50);
  } else {
    lastFocus?.focus();
  }
};
document.querySelectorAll('[data-open-contact]').forEach((button) => button.addEventListener('click', () => setModal(true)));
document.querySelectorAll('[data-close-contact]').forEach((button) => button.addEventListener('click', () => setModal(false)));
document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape' && modal?.getAttribute('aria-hidden') === 'false') setModal(false);
  if (event.key === 'Tab' && modal?.getAttribute('aria-hidden') === 'false') {
    const items = [...modal.querySelectorAll(focusableSelector)].filter((item) => !item.disabled);
    const first = items[0];
    const last = items.at(-1);
    if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
    if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
  }
});

const form = document.querySelector('[data-contact-form]');
form?.addEventListener('submit', async (event) => {
  event.preventDefault();
  const status = form.querySelector('[data-form-status]');
  const button = form.querySelector('button[type="submit"]');
	if (!form.checkValidity()) {
		form.reportValidity();
		status.textContent = window.olgaSite?.messages?.check || 'Проверьте обязательные поля.';
		return;
	}
	const data = Object.fromEntries(new FormData(form).entries());
	data.agree = Boolean(form.elements.agree.checked);
	data.language = window.olgaSite?.language || 'ru';
	button.disabled = true;
	status.textContent = window.olgaSite?.messages?.sending || 'Отправляем…';
  try {
    const response = await fetch(window.olgaSite.restUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': window.olgaSite.nonce }, body: JSON.stringify(data) });
    const result = await response.json();
    if (!response.ok) throw new Error(result.message || 'Ошибка отправки.');
    status.textContent = result.message;
    status.classList.add('is-success');
    form.reset();
  } catch (error) {
    status.textContent = error.message;
    status.classList.remove('is-success');
  } finally {
    button.disabled = false;
  }
});

document.querySelector('[data-to-top]')?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: reducedMotion ? 'auto' : 'smooth' }));
