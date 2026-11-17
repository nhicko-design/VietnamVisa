document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('applyForm');

    if (form) {
        const steps = [...form.querySelectorAll('.form-step')];
        const dots = [...form.querySelectorAll('.form-progress span')];
        const review = document.getElementById('applicationReview');
        let current = 0;

        const renderReview = () => {
            if (!review) {
                return;
            }

            const data = new FormData(form);
            const items = [
                ['Name', data.get('full_name') || '-'],
                ['Email', data.get('email') || '-'],
                ['Nationality', data.get('nationality') || '-'],
                ['Service', data.get('service') || '-'],
                ['Entry date', data.get('entry_date') || '-'],
            ];

            review.replaceChildren();

            items.forEach(([label, value]) => {
                const item = document.createElement('div');
                const heading = document.createElement('b');
                const text = document.createElement('span');

                heading.textContent = label;
                text.textContent = value;

                item.append(heading, text);
                review.append(item);
            });
        };

        const show = () => {
            steps.forEach((step, index) => {
                step.classList.toggle('active', index === current);
            });

            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index <= current);
            });

            if (current === steps.length - 1) {
                renderReview();
            }
        };

        const currentStepIsValid = () => {
            const fields = steps[current].querySelectorAll('[required]');

            return [...fields].every(field => {
                if (field.checkValidity()) {
                    return true;
                }

                field.reportValidity();
                return false;
            });
        };

        form.querySelectorAll('.next-step').forEach(button => {
            button.addEventListener('click', () => {
                if (current < steps.length - 1 && currentStepIsValid()) {
                    current += 1;
                    show();
                }
            });
        });

        form.querySelectorAll('.prev-step').forEach(button => {
            button.addEventListener('click', () => {
                if (current > 0) {
                    current -= 1;
                    show();
                }
            });
        });

        show();
    }

    const quoteButton = document.getElementById('calculateQuote');

    if (quoteButton) {
        quoteButton.addEventListener('click', () => {
            const base = Number(document.getElementById('quoteService').value);
            const applicants = Math.max(
                1,
                Number(document.getElementById('quoteApplicants').value) || 1
            );
            const processing = Number(
                document.getElementById('quoteProcessing').value
            );

            document.getElementById('quoteTotal').textContent =
                '$' + Math.round(base * applicants * processing);
        });
    }
});
