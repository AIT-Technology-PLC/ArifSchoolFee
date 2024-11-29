document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('#tabs a');
    const panels = document.querySelectorAll('.panel-block');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(item => item.classList.remove('is-active'));
            tab.classList.add('is-active');

            panels.forEach(panel => {
                panel.classList.remove('is-active');

                if (!panel.classList.contains('is-active')) {
                    clearSelections(panel);
                }
            });

            const target = tab.getAttribute('data-target');
            panels.forEach(panel => {
                if (panel.id === target) {
                    panel.classList.add('is-active');
                } else {
                    panel.classList.remove('is-active');
                }
            });
        });
    });
});

function clearSelections(panel) {
    panel.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });

    panel.querySelectorAll('select').forEach(select => {
        select.value = ''; 
        $(select).val(null).trigger('change'); 
    });
}