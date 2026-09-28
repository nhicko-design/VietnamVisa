console.log('APP JS LOADED');

document.addEventListener('DOMContentLoaded', () => {

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
        .querySelectorAll('.faq-item')
        .forEach(d =>
            d.addEventListener(
                'toggle',
                () => {

                    if (d.open) {

                        document
                            .querySelectorAll('.faq-item')
                            .forEach(x => {

                                if (x !== d) {
                                    x.removeAttribute('open');
                                }

                            });

                    }

                }
            )
        );


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