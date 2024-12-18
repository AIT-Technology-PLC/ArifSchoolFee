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

if (document.getElementById('school')) {
    document.getElementById('school').addEventListener('change', function() {
        var schoolId = this.value;
        var branchSelect = document.getElementById('branch');

        branchSelect.innerHTML = '<option value="" selected disabled>Select Branch</option>';

        if (schoolId) {
            fetch(`/api/branches/${schoolId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(branch => {
                        var option = document.createElement('option');
                        option.value = branch.id;
                        option.textContent = branch.name;
                        branchSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching branches:', error));
        }
    });

    document.getElementById('school').addEventListener('change', function() {
        var schoolId = this.value;
        var schoolClassSelect = document.getElementById('school_class_id');

        schoolClassSelect.innerHTML = '<option value="" selected disabled>Select Class</option>';

        if (schoolId) {
            fetch(`/api/classes/${schoolId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(schoolClass => {
                        var option = document.createElement('option');
                        option.value = schoolClass.id;
                        option.textContent = schoolClass.name;
                        schoolClassSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching Classes:', error));
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