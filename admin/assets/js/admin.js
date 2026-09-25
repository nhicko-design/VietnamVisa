document.addEventListener('DOMContentLoaded', function () {


    /*
     * ========================================
     * SIDEBAR
     * ========================================
     */

    const sidebar = document.querySelector('[data-sidebar]');

    const sidebarToggle = document.querySelector(
        '[data-sidebar-toggle]'
    );


    if (sidebar && sidebarToggle) {

        sidebarToggle.addEventListener('click', function () {

            sidebar.classList.toggle('open');

            document.body.classList.toggle(
                'sidebar-open'
            );

        });

    }


    /*
     * ========================================
     * CLOSE SIDEBAR WHEN CLICK OUTSIDE
     * ========================================
     */

    document.addEventListener('click', function (event) {

        if (!sidebar) {
            return;
        }

        if (!sidebar.classList.contains('open')) {
            return;
        }

        const clickedInsideSidebar =
            sidebar.contains(event.target);

        const clickedToggle =
            sidebarToggle &&
            sidebarToggle.contains(event.target);


        if (
            !clickedInsideSidebar &&
            !clickedToggle
        ) {

            sidebar.classList.remove('open');

            document.body.classList.remove(
                'sidebar-open'
            );

        }

    });


    /*
     * ========================================
     * PASSWORD TOGGLE
     * ========================================
     */

    const passwordToggle = document.querySelector(
        '[data-password-toggle]'
    );

    const passwordInput = document.querySelector(
        '#password'
    );


    if (passwordToggle && passwordInput) {

        passwordToggle.addEventListener(
            'click',
            function () {

                const isPassword =
                    passwordInput.type === 'password';


                passwordInput.type =
                    isPassword
                        ? 'text'
                        : 'password';


                passwordToggle.textContent =
                    isPassword
                        ? 'Hide'
                        : 'Show';


                passwordToggle.setAttribute(
                    'aria-label',
                    isPassword
                        ? 'Hide password'
                        : 'Show password'
                );

            }
        );

    }


    /*
     * ========================================
     * ESCAPE KEY
     * ========================================
     */

    document.addEventListener('keydown', function (event) {

        if (event.key !== 'Escape') {
            return;
        }

        if (!sidebar) {
            return;
        }

        sidebar.classList.remove('open');

        document.body.classList.remove(
            'sidebar-open'
        );

    });


});