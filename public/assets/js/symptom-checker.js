document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.symptom-form');

    if (! form) {
        return;
    }

    form.addEventListener('submit', (event) => {
        const symptoms = Array.from(form.querySelectorAll('select[name="symptoms[]"] option:checked')).map((option) => option.value);
        if (symptoms.length === 0) {
            event.preventDefault();
            alert('Please select at least one symptom.');
            return;
        }

        const temperature = parseFloat(form.querySelector('input[name="temperature"]').value);
        if (Number.isNaN(temperature) || temperature < 30 || temperature > 45) {
            event.preventDefault();
            alert('Please enter a realistic temperature in Celsius.');
        }
    });
});
