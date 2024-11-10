
document.addEventListener('DOMContentLoaded', () => {
const steps = document.querySelectorAll('.steps-segment');

    let currentStep = 0;
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');

    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            step.classList.remove('is-active');
            const target = step.getAttribute('data-target');
            document.getElementById(target).classList.remove('is-active');

            if (index === stepIndex) {
                step.classList.add('is-active');
                const target = step.getAttribute('data-target');
                document.getElementById(target).classList.add('is-active');

                if (target === 'step-one') {
                    document.getElementById('prevButton').style.display = 'none';
                } else {
                    document.getElementById('prevButton').style.display = 'inline-block';
                }

                if (target === 'step-three') {
                    document.getElementById('nextButton').style.display = 'none';
                    document.getElementById('saveButton').style.display = 'inline-block';
                } else {
                    document.getElementById('nextButton').style.display = 'inline-block';
                    document.getElementById('saveButton').style.display = 'none';
                }
            }
        });

        currentStep = stepIndex;
    }

    prevButton.addEventListener('click', () => {
        event.preventDefault();
        if (currentStep > 0) {
            showStep(currentStep - 1);
        }
    });

    nextButton.addEventListener('click', () => {
        event.preventDefault();

        if (currentStep < steps.length - 1) {
            showStep(currentStep + 1);
        }
    });

    showStep(0);
});