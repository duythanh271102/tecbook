document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.wpcf7-form');
    const loadingContainer = document.createElement('div');

    loadingContainer.id = 'loading-container';
    loadingContainer.style.display = 'none';
    loadingContainer.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    document.body.appendChild(loadingContainer);

    forms.forEach(function (form) {
        form.addEventListener('submit', function () {
            loadingContainer.style.display = 'block';
        });

        form.addEventListener('wpcf7invalid', function () {
            loadingContainer.style.display = 'none';
        });

        form.addEventListener('wpcf7mailsent', function () {
            loadingContainer.style.display = 'none';
        });

        form.addEventListener('wpcf7mailfailed', function () {
            loadingContainer.style.display = 'none';
        });
    });
});
