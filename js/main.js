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

function toggleDarkMode() {
    document.body.classList.toggle('dark');
    const btn = document.getElementById('darkModeBtn');
    if (document.body.classList.contains('dark')) {
        btn.textContent = 'Light Mode';
        localStorage.setItem('theme', 'dark');
    } else {
        btn.textContent = 'Dark Mode';
        localStorage.setItem('theme', 'light');
    }
}

window.onload = function() {
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark');
        const btn = document.getElementById('darkModeBtn');
        if (btn) btn.textContent = 'Light Mode';
    }
};


function copyNotice(title, message) {
    const text = title + '\n\n' + message;
    navigator.clipboard.writeText(text).then(function() {
        alert('Notice copied to clipboard!');
    });
}

function printNotice(title, message) {
    const printWindow = window.open('', '_blank');
    printWindow.document.write('<h2>' + title + '</h2><p>' + message + '</p>');
    printWindow.document.close();
    printWindow.print();
}