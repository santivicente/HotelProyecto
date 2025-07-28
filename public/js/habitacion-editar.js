document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formHabitacion');
    const mensajeAjax = document.getElementById('mensajeAjax');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mensajeAjax.style.color = 'green';
                    mensajeAjax.textContent = data.message;
                } else {
                    mensajeAjax.style.color = 'red';
                    mensajeAjax.textContent = data.message || 'Ocurrió un error.';
                }
            })
            .catch(() => {
                mensajeAjax.style.color = 'red';
                mensajeAjax.textContent = 'Error de comunicación con el servidor.';
            });
        });
    }
});
