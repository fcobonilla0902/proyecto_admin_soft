    const input = document.getElementById('contenido');
    const contador = document.getElementById('contador');

    input.addEventListener('input', () => {
        contador.textContent = `${input.value.length} / ${input.maxLength}`;
    });

document.addEventListener('DOMContentLoaded', function() {

    // contador de caracteres 
    const input = document.getElementById('contenido');
    const contador = document.getElementById('contador');
    if (input && contador) {
        input.addEventListener('input', () => {
            contador.textContent = `${input.value.length} / ${input.maxLength}`;
        });
    }

    // manejo de reacciones por fetch (AJAX)
    document.querySelectorAll('.btn-reaccion').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const tipo = btn.dataset.tipo;

            // deshabilitar botón mientras se procesa
            btn.disabled = true;

            try {
                const params = new URLSearchParams();
                params.append('id', id);
                params.append('tipo', tipo);

                const res = await fetch('../admin/reaccion.php', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: params
                });

                if (!res.ok) {
                    throw new Error('HTTP ' + res.status);
                }

                const data = await res.json();
                console.log('reaccion:', data);

                if (data.success) {
                    const comentario = btn.closest('.comentario');
                    if (comentario) {
                        const likeSpan = comentario.querySelector('.count-like');
                        const dislikeSpan = comentario.querySelector('.count-dislike');
                        if (likeSpan) likeSpan.textContent = data.likes;
                        if (dislikeSpan) dislikeSpan.textContent = data.dislikes;
                    }
                } else {
                    console.error('Respuesta falsa del servidor', data);
                    alert('No fue posible procesar la reacción. Revisa la consola.');
                }
            } catch (err) {
                console.error('Error al enviar reacción:', err);
                alert('Error al enviar la reacción. Abre la consola (F12) para más info.');
            } finally {
                btn.disabled = false;
            }
        });
    });
});

