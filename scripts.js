// scripts.js - Custom JavaScript for Animations
document.addEventListener('DOMContentLoaded', function () {
    // Form Submission Spinner
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const submitButton = form.querySelector('button[type="submit"]');
            const spinner = document.createElement('span');
            spinner.className = 'spinner';
            submitButton.appendChild(spinner);
            submitButton.disabled = true;
            spinner.style.display = 'inline-block';
        });
    });

    // Scroll-based Animations
    const sections = document.querySelectorAll('.fade-in-section');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, { threshold: 0.1 });

    sections.forEach(section => observer.observe(section));

    // PWA Service Worker Registration
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('service-worker.js')
            .then(reg => console.log('Service Worker registered', reg))
            .catch(err => console.log('Service Worker registration failed', err));
    }
});