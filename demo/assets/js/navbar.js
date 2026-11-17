document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('siteHeader');
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileNav = document.getElementById('mobileNav');
    const desktopNav = header?.querySelector('.desktop-nav');
    const desktopLayout = window.matchMedia('(min-width: 1051px)');
    const hoverPointer = window.matchMedia('(hover: hover) and (pointer: fine)');

    const setMobileOpen = (open) => {
        if (!mobileToggle || !mobileNav) {
            return;
        }

        mobileNav.classList.toggle('open', open);
        mobileToggle.setAttribute('aria-expanded', String(open));
        document.body.classList.toggle('menu-open', open);
    };

    mobileToggle?.addEventListener('click', () => {
        setMobileOpen(mobileToggle.getAttribute('aria-expanded') !== 'true');
    });

    mobileNav?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setMobileOpen(false));
    });

    const updateHeader = () => header?.classList.toggle('scrolled', window.scrollY > 10);
    window.addEventListener('scroll', updateHeader, { passive: true });
    updateHeader();

    const dropdowns = [];

    const closeDropdowns = (except = null) => {
        dropdowns.forEach((dropdown) => {
            if (dropdown !== except) {
                dropdown.setOpen(false);
            }
        });
    };

    desktopNav?.querySelectorAll('.has-dropdown').forEach((item) => {
        const toggle = item.querySelector('.nav-dropdown-toggle');
        const parentLink = item.querySelector('.nav-link');
        const panel = item.querySelector('.nav-dropdown');

        if (!toggle || !panel) {
            return;
        }

        const links = Array.from(panel.querySelectorAll('a'));
        let closeTimer = null;
        const dropdown = {
            item,
            toggle,
            setOpen(open) {
                window.clearTimeout(closeTimer);
                item.classList.toggle('is-open', open);
                toggle.setAttribute('aria-expanded', String(open));
            },
        };

        dropdowns.push(dropdown);

        const openDropdown = () => {
            closeDropdowns(dropdown);
            dropdown.setOpen(true);
        };

        toggle.addEventListener('click', () => {
            if (toggle.getAttribute('aria-expanded') === 'true') {
                dropdown.setOpen(false);
            } else {
                openDropdown();
            }
        });

        item.addEventListener('pointerenter', (event) => {
            if (desktopLayout.matches && hoverPointer.matches && event.pointerType === 'mouse') {
                openDropdown();
            }
        });

        item.addEventListener('pointerleave', () => {
            if (!item.contains(document.activeElement)) {
                closeTimer = window.setTimeout(() => dropdown.setOpen(false), 140);
            }
        });

        item.addEventListener('focusout', (event) => {
            if (!item.contains(event.relatedTarget)) {
                dropdown.setOpen(false);
            }
        });

        item.addEventListener('focusin', () => window.clearTimeout(closeTimer));

        item.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                event.preventDefault();
                event.stopPropagation();
                dropdown.setOpen(false);
                toggle.focus();
                return;
            }

            if (!['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key) || links.length === 0) {
                return;
            }

            const isTrigger = event.target === toggle || event.target === parentLink;
            const index = links.indexOf(event.target);

            if (!isTrigger && index < 0) {
                return;
            }

            if (isTrigger && ['Home', 'End'].includes(event.key)) {
                return;
            }

            event.preventDefault();
            openDropdown();

            if (event.key === 'Home' || (isTrigger && event.key === 'ArrowDown')) {
                links[0].focus();
            } else if (event.key === 'End' || (isTrigger && event.key === 'ArrowUp')) {
                links[links.length - 1].focus();
            } else {
                const direction = event.key === 'ArrowDown' ? 1 : -1;
                links[(index + direction + links.length) % links.length].focus();
            }
        });
    });

    desktopNav?.classList.add('is-enhanced');

    desktopNav?.addEventListener('focusin', (event) => {
        const focusedDropdown = dropdowns.find((dropdown) => dropdown.item.contains(event.target));
        closeDropdowns(focusedDropdown ?? null);
    });

    document.addEventListener('click', (event) => {
        if (!desktopNav?.contains(event.target)) {
            closeDropdowns();
        }
    });

    document.addEventListener('focusin', (event) => {
        if (!desktopNav?.contains(event.target)) {
            closeDropdowns();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }

        closeDropdowns();

        if (mobileNav?.classList.contains('open')) {
            setMobileOpen(false);
            mobileToggle?.focus();
        }
    });

    desktopLayout.addEventListener('change', () => {
        closeDropdowns();
        setMobileOpen(false);
    });
});
