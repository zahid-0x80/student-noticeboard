document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('input[maxlength], textarea[maxlength]');

    inputs.forEach(function (input) {
        const counter = input.nextElementSibling;

        if (counter && counter.classList.contains('char-count')) {
            input.addEventListener('input', function () {
                const current = input.value.length;
                const max = input.getAttribute('maxlength');
                counter.textContent = current + ' / ' + max;
            });
        }
    });
});