const preguntas = document.querySelector('#preguntas');
const guias = document.querySelector('#guias');
const videos = document.querySelector('#videos');
const reportar = document.querySelector('#reportar');
const telefonos = document.querySelector('#telefonos');
const ubicacion = document.querySelector('#ubicacion');

preguntas.addEventListener('click', function(event) {
    event.preventDefault();
    window.open('https://www.fime.uanl.mx/preguntas-frecuentes/', '_blank');
});


guias.addEventListener('click', function(event) {
    event.preventDefault();
    window.open('/src/pdf/Manual_usuario_proy-pmbok.pdf', '_blank');
});

videos.addEventListener('click', function(event) {
    event.preventDefault();
    window.open('https://www.youtube.com/playlist?list=PL-_ObXiitJQCjOBAbjiGWTisOOERwdDf5', '_blank');
});

reportar.addEventListener('click', function(event) {
    event.preventDefault();
    window.open('https://www.fime.uanl.mx/buzon-de-sugerencias/', '_blank');
});

telefonos.addEventListener('click', function(event) {
    event.preventDefault();
    window.open('https://www.fime.uanl.mx/contacto/', '_blank');
});

ubicacion.addEventListener('click', function(event) {
    event.preventDefault();
    window.open('https://www.google.com/maps/place/Faculty+of+Mechanical+and+Electrical+Engineering+UANL/@25.7253908,-100.3137887,15z/data=!4m5!3m4!1s0x86629452551ea79f:0x66e03550ec5730cb!8m2!3d25.7253908!4d-100.3055993', '_blank');
});

