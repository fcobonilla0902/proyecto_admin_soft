document.addEventListener('DOMContentLoaded', function() {
    const selectTipo = document.getElementById('tipo-usuario-select');
    const labelIdentificador = document.getElementById('label-identificador');
    const inputIdentificador = document.getElementById('input-identificador');

    // Función para actualizar el campo según el tipo seleccionado
    function actualizarCampo() {
        const tipoSeleccionado = selectTipo.value;

        if (tipoSeleccionado === 'externo') {
            labelIdentificador.textContent = 'Ingrese su Correo:';
            inputIdentificador.setAttribute('name', 'correo');
            inputIdentificador.setAttribute('type', 'email');
            inputIdentificador.setAttribute('placeholder', 'Correo Electrónico');
        } else {
            labelIdentificador.textContent = 'Ingrese su Matrícula:';
            inputIdentificador.setAttribute('name', 'matricula');
            inputIdentificador.setAttribute('type', 'text');
            inputIdentificador.setAttribute('placeholder', 'Matrícula');
        }
    }

    // Cambiar dinámicamente cuando el usuario seleccione
    selectTipo.addEventListener('change', actualizarCampo);

    actualizarCampo();
});
