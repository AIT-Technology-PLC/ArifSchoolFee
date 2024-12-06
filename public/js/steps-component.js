if (document.getElementById('school_class_id')) {
    document.getElementById('school_class_id').addEventListener('change', function() {
        var classId = this.value;
        var sectionSelect = document.getElementById('section_id');

        sectionSelect.innerHTML = '<option value="" selected disabled>Select Section</option>';

        if (classId) {
            fetch(`/api/sections/${classId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(section => {
                        var option = document.createElement('option');
                        option.value = section.id;
                        option.textContent = section.name;
                        sectionSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching sections:', error));
        }
    });
}

if (document.getElementById('route_id')) {
    document.getElementById('route_id').addEventListener('change', function() {
        var routeId = this.value;
        var vehicleSelect = document.getElementById('vehicle_id');

        vehicleSelect.innerHTML = '<option value="" selected disabled>Select Vehicle</option>';

        if (routeId) {
            fetch(`/api/vehicles/${routeId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(vehicle => {
                        var option = document.createElement('option');
                        option.value = vehicle.id;
                        option.textContent = vehicle.vehicle_number;
                        vehicleSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching vehicles:', error));
        }
    });
}
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

                if (target === 'step-last') {
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