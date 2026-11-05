/* Église Évangélique de Peniel : menu, animations au défilement, partage */
(function () {
    'use strict';

    /* ---------- Menu mobile et sous-menus ---------- */
    var toggle = document.querySelector('.nav-toggle');
    var nav = document.getElementById('nav');

    if (toggle && nav) {
        var mobile = window.matchMedia('(max-width: 820px)');
        var links = nav.querySelectorAll('.nav-item > a');

        var closeSubmenus = function () {
            nav.querySelectorAll('.nav-item.open').forEach(function (item) {
                item.classList.remove('open');
            });
            links.forEach(function (l) { l.setAttribute('aria-expanded', 'false'); });
        };

        toggle.addEventListener('click', function () {
            var open = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (!open) closeSubmenus();
        });

        // Sur mobile, un clic sur « À propos », « Événements » ou « Média » ouvre son sous-menu
        links.forEach(function (link) {
            link.setAttribute('aria-haspopup', 'true');
            link.setAttribute('aria-expanded', 'false');
            link.addEventListener('click', function (e) {
                if (!mobile.matches) return;
                e.preventDefault();
                var item = link.parentElement;
                var wasOpen = item.classList.contains('open');
                closeSubmenus();
                if (!wasOpen) {
                    item.classList.add('open');
                    link.setAttribute('aria-expanded', 'true');
                }
            });
        });

        mobile.addEventListener('change', function () {
            if (!mobile.matches) {
                nav.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
                closeSubmenus();
            }
        });
    }

    /* ---------- Partage du culte en direct ---------- */
    document.querySelectorAll('[data-share]').forEach(function (btn) {
        if (!navigator.share) return;
        btn.hidden = false;
        btn.addEventListener('click', function () {
            var box = btn.closest('[data-share-url]');
            navigator.share({ title: box.dataset.shareTitle, url: box.dataset.shareUrl }).catch(function () {});
        });
    });

    document.querySelectorAll('[data-copy]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var value = btn.dataset.copy;
            var done = function () {
                var old = btn.textContent;
                btn.textContent = 'Lien copié';
                setTimeout(function () { btn.textContent = old; }, 2000);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(value).then(done, function () { window.prompt('Copiez ce lien :', value); });
            } else {
                window.prompt('Copiez ce lien :', value);
            }
        });
    });

    /* ---------- Animations au défilement (pas dans l'administration) ---------- */
    if (document.querySelector('.admin-nav')) return;

    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var targets = [];

    function position(el) {
        return Array.prototype.indexOf.call(el.parentNode.children, el);
    }

    function mark(selector, direction, stagger) {
        document.querySelectorAll(selector).forEach(function (el) {
            if (el.classList.contains('reveal') || el.closest('.site-header')) return;
            el.classList.add('reveal');
            if (direction) el.classList.add(direction);
            if (stagger) el.style.setProperty('--d', (Math.min(position(el), 6) * 0.1) + 's');
            targets.push(el);
        });
    }

    mark('.hero-text > *', null, true);
    mark('.page-head .container > *', null, true);
    mark('.devise-grid > div', null, true);
    mark('.day-card', null, true);
    mark('.split > :first-child', 'from-left');
    mark('.split > :last-child', 'from-right');
    mark('.section-head, .section > .container > h2, .section > .container > .lead, .section > .narrow > .lead');
    mark('.prose > h2, .prose > p, .prose > .facts, .prose > .event-img');
    mark('.event-row, .entry-list li, .vision-list li, .sermon, .timeline li', null, true);
    mark('.map-frame, .video-card, .live-wrap > *, .cta-inner > *, .footer-grid > div', null, true);

    if (reduce || !('IntersectionObserver' in window)) {
        targets.forEach(function (el) { el.classList.add('in'); });
    } else {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

        targets.forEach(function (el) { io.observe(el); });
    }

    /* La ligne de la chronologie se remplit au fil du défilement */
    var timeline = document.querySelector('.timeline');
    if (timeline && !reduce) {
        var ticking = false;
        var update = function () {
            var rect = timeline.getBoundingClientRect();
            var progress = (window.innerHeight * 0.65 - rect.top) / rect.height;
            timeline.style.setProperty('--fill', Math.max(0, Math.min(1, progress)) * 100 + '%');
            ticking = false;
        };
        window.addEventListener('scroll', function () {
            if (!ticking) { ticking = true; requestAnimationFrame(update); }
        }, { passive: true });
        window.addEventListener('resize', update);
        update();
    }
})();
