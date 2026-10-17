document.addEventListener('DOMContentLoaded', () => {

    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    const progress = document.getElementById(
        'siteProgress'
    );

    const counters = () =>
        document.querySelectorAll('[data-counter]').forEach(el => {

            if (el.dataset.done) {
                return;
            }

            const r = el.getBoundingClientRect();

            if (r.top < window.innerHeight * 0.9) {

                el.dataset.done = '1';

                const target = parseInt(
                    el.dataset.counter,
                    10
                );

                if (reducedMotion.matches) {
                    el.textContent = target + (el.dataset.counterSuffix || (target === 10 ? '+' : ''));
                    return;
                }

                let n = 0;

                const timer = setInterval(() => {

                    n++;

                    el.textContent =
                        n +
                        (
                            el.dataset.counterSuffix ||
                            (
                                target === 10
                                    ? '+'
                                    : ''
                            )
                        );

                    if (n >= target) {
                        clearInterval(timer);
                    }

                }, 50);

            }

        });


    const scroll = () => {

        const h =
            document.documentElement.scrollHeight -
            window.innerHeight;

        if (progress) {

            progress.style.width =
                (
                    h > 0
                        ? (window.scrollY / h) * 100
                        : 0
                ) + '%';

        }

        counters();

    };


    window.addEventListener(
        'scroll',
        scroll,
        {
            passive: true
        }
    );

    scroll();


    document
        .querySelectorAll('.demo-form')
        .forEach(form =>
            form.addEventListener(
                'submit',
                e => {

                    e.preventDefault();

                    alert(
                        'Frontend demo: connect this form to the backend/API.'
                    );

                }
            )
        );

});


/* =========================================================
   SUNVIET QUICK CONTACT
   ========================================================= */

(() => {

    const root = document.getElementById(
        'svqc'
    );

    const toggle = document.getElementById(
        'svqcToggle'
    );

    const menu = document.getElementById(
        'svqcMenu'
    );


    if (!root || !toggle || !menu) {
        return;
    }


    const close = () => {

        root.classList.remove(
            'is-open'
        );

        toggle.setAttribute(
            'aria-expanded',
            'false'
        );

        toggle.setAttribute(
            'aria-label',
            'Open quick contact'
        );

        menu.setAttribute(
            'aria-hidden',
            'true'
        );

    };


    toggle.addEventListener(
        'click',
        event => {

            event.stopPropagation();

            const open =
                root.classList.toggle(
                    'is-open'
                );

            toggle.setAttribute(
                'aria-expanded',
                open
                    ? 'true'
                    : 'false'
            );

            toggle.setAttribute(
                'aria-label',
                open
                    ? 'Close quick contact'
                    : 'Open quick contact'
            );

            menu.setAttribute(
                'aria-hidden',
                open
                    ? 'false'
                    : 'true'
            );

        }
    );


    menu.addEventListener(
        'click',
        event => event.stopPropagation()
    );


    document.addEventListener(
        'click',
        close
    );


    document.addEventListener(
        'keydown',
        event => {

            if (event.key === 'Escape') {
                close();
            }

        }
    );

})();

document.addEventListener('DOMContentLoaded', () => {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    document.querySelectorAll('[data-media-hero]').forEach((hero) => {
        const slides = Array.from(hero.querySelectorAll('[data-media-slide]'));
        const dots = Array.from(hero.querySelectorAll('[data-media-dot]'));
        const previous = hero.querySelector('[data-media-prev]');
        const next = hero.querySelector('[data-media-next]');
        let index = 0;
        let timer = null;
        let pointerOver = false;
        let focusWithin = hero.contains(document.activeElement);

        if (slides.length < 2) {
            return;
        }

        const show = (nextIndex) => {
            slides[index]?.querySelector('video')?.pause();
            index = (nextIndex + slides.length) % slides.length;
            slides.forEach((slide, slideIndex) => {
                slide.classList.toggle('is-active', slideIndex === index);
                slide.setAttribute('aria-hidden', slideIndex === index ? 'false' : 'true');
            });
            dots.forEach((dot, dotIndex) => {
                dot.classList.toggle('is-active', dotIndex === index);
                dot.setAttribute('aria-current', dotIndex === index ? 'true' : 'false');
            });
        };

        const stop = () => {
            window.clearInterval(timer);
            timer = null;
        };

        const start = () => {
            stop();

            const activeVideo = slides[index].querySelector('video');

            if (
                reducedMotion.matches ||
                document.hidden ||
                pointerOver ||
                focusWithin ||
                (activeVideo && !activeVideo.paused && !activeVideo.ended)
            ) {
                return;
            }

            timer = window.setInterval(() => show(index + 1), 5500);
        };

        previous?.addEventListener('click', () => {
            show(index - 1);
            start();
        });
        next?.addEventListener('click', () => {
            show(index + 1);
            start();
        });
        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                show(Number(dot.dataset.mediaDot));
                start();
            });
        });
        slides.forEach((slide) => {
            const video = slide.querySelector('video');
            video?.addEventListener('play', stop);
            video?.addEventListener('pause', start);
            video?.addEventListener('ended', () => {
                if (!reducedMotion.matches) {
                    show(index + 1);
                }
                start();
            });
        });
        hero.addEventListener('mouseenter', () => {
            pointerOver = true;
            stop();
        });
        hero.addEventListener('mouseleave', () => {
            pointerOver = false;
            start();
        });
        hero.addEventListener('focusin', () => {
            focusWithin = true;
            stop();
        });
        hero.addEventListener('focusout', (event) => {
            focusWithin = hero.contains(event.relatedTarget);
            start();
        });
        document.addEventListener('visibilitychange', start);
        reducedMotion.addEventListener('change', start);
        show(0);
        start();
    });
});
