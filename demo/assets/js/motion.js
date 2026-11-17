/* Progressive animation: content remains readable if JavaScript is unavailable. */
document.addEventListener('DOMContentLoaded', () => {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const mobileLayout = window.matchMedia('(max-width: 820px)');
    const activeAnimations = new Set();
    const observedElements = new Set();
    let observer = null;

    const revealSelectors = [
        '.reveal',
        '.sv-section-heading',
        '.section-intro',
        '.service-hero-copy',
        '.service-hero-panel',
        '.sv-metric-card',
        '.service-card',
        '.sv-service-card',
        '.sv-process-card',
        '.process-card',
        '.news-card',
        '.sv-insight-card',
        '.mini-card',
        '.detail-step',
        '.country-info-card',
        '.contact-items > a',
    ];

    const revealElement = (element) => {
        observedElements.delete(element);
        observer?.unobserve(element);

        if (reducedMotion.matches || typeof element.animate !== 'function') {
            return;
        }

        const siblings = Array.from(element.parentElement.children);
        const order = siblings.indexOf(element);
        const distance = mobileLayout.matches ? 10 : 16;
        const animation = element.animate([
            { opacity: 0, translate: `0 ${distance}px` },
            { opacity: 1, translate: '0 0' },
        ], {
            duration: mobileLayout.matches ? 360 : 480,
            delay: Math.min(Math.max(order, 0), 3) * 55,
            easing: 'cubic-bezier(0.22, 1, 0.36, 1)',
            fill: 'backwards',
        });

        activeAnimations.add(animation);
        const complete = () => activeAnimations.delete(animation);
        animation.addEventListener('finish', complete, { once: true });
        animation.addEventListener('cancel', complete, { once: true });
    };

    if (!reducedMotion.matches && 'IntersectionObserver' in window) {
        observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    revealElement(entry.target);
                }
            });
        }, {
            threshold: 0.08,
        });

        document.querySelectorAll(revealSelectors.join(',')).forEach((element) => {
            // Never animate a containing grid: sticky sidebars keep their normal behavior.
            if (element.parentElement.closest(revealSelectors.join(','))) {
                return;
            }

            observedElements.add(element);
            observer.observe(element);
        });
    }

    // Keyboard navigation must never wait for an entrance animation.
    document.addEventListener('focusin', (event) => {
        observedElements.forEach((element) => {
            if (element.contains(event.target)) {
                observer?.unobserve(element);
                observedElements.delete(element);
            }
        });

        activeAnimations.forEach((animation) => {
            if (animation.effect?.target?.contains(event.target)) {
                animation.cancel();
            }
        });
    });

    const accordions = [];

    const closeOtherAccordions = (current) => {
        accordions.forEach((accordion) => {
            if (accordion !== current && accordion.expanded) {
                accordion.setOpen(false);
            }
        });
    };

    document.querySelectorAll('.faq-item').forEach((details) => {
        const summary = details.querySelector('summary');

        if (!summary) {
            return;
        }

        const controller = {
            expanded: details.open,
            animation: null,
            setOpen(expanded, immediate = false) {
                const startHeight = details.getBoundingClientRect().height;
                this.animation?.cancel();
                this.animation = null;
                this.expanded = expanded;
                details.style.removeProperty('height');
                details.style.removeProperty('overflow');

                if (immediate || reducedMotion.matches || typeof details.animate !== 'function') {
                    details.open = expanded;
                    return;
                }

                // Keep native <details> open until its closing animation finishes.
                details.open = true;
                const style = window.getComputedStyle(details);
                const borders = parseFloat(style.borderTopWidth) + parseFloat(style.borderBottomWidth);
                const padding = parseFloat(style.paddingTop) + parseFloat(style.paddingBottom);
                const endHeight = expanded
                    ? details.getBoundingClientRect().height
                    : summary.getBoundingClientRect().height + borders + padding;

                details.style.height = `${startHeight}px`;
                details.style.overflow = 'hidden';

                const animation = details.animate([
                    { height: `${startHeight}px` },
                    { height: `${endHeight}px` },
                ], {
                    duration: 240,
                    easing: 'cubic-bezier(0.22, 1, 0.36, 1)',
                });

                this.animation = animation;
                animation.addEventListener('finish', () => {
                    if (this.animation !== animation) {
                        return;
                    }

                    this.animation = null;
                    details.open = this.expanded;
                    details.style.removeProperty('height');
                    details.style.removeProperty('overflow');
                }, { once: true });
            },
        };

        accordions.push(controller);

        summary.addEventListener('click', (event) => {
            if (event.target.closest('a, button, input, select, textarea')) {
                return;
            }

            event.preventDefault();
            const expanded = !controller.expanded;

            if (expanded) {
                closeOtherAccordions(controller);
            }

            controller.setOpen(expanded);
        });

        // Preserve native toggle behavior for accessibility tools and external scripts.
        details.addEventListener('toggle', () => {
            if (controller.animation) {
                return;
            }

            controller.expanded = details.open;

            if (details.open) {
                closeOtherAccordions(controller);
            }
        });
    });

    const updateMotionPreference = () => {
        if (!reducedMotion.matches) {
            return;
        }

        observer?.disconnect();
        observedElements.clear();
        activeAnimations.forEach((animation) => animation.cancel());
        activeAnimations.clear();
        accordions.forEach((accordion) => accordion.setOpen(accordion.expanded, true));
    };

    reducedMotion.addEventListener('change', updateMotionPreference);
});
