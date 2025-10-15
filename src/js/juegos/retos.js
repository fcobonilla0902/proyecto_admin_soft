    document.addEventListener('DOMContentLoaded', () => {
        const challenges = [
            { code: `function saludar(nombre) {\n  console.log('Hola, ' + nombre<span class="placeholder">;</span>\n}`, question: "Falta un carácter para cerrar el console.log. ¿Cuál es?", answer: ")" },
            { code: `for (let i = 0; i < 5; <span class="placeholder">???</span>) {\n  console.log(i);\n}`, question: "Escribe la parte que incrementa la variable 'i' en cada iteración.", answer: "i++" },
            { code: `<span class="placeholder">???</span> nombre = 'Ana';\nconsole.log(nombre);`, question: "Falta la palabra clave para declarar la variable 'nombre'. Complétala (usa la más común).", answer: "let" },
            { code: `let edad = 20;\nif (edad <span class="placeholder">=</span> 20) {\n  console.log('Tienes 20 años');\n}`, question: "El operador de asignación (=) es incorrecto para una comparación. ¿Cuál es el operador correcto para una comparación estricta?", answer: "===" },
            { code: `const sumar = (a, b) => <span class="placeholder">???</span>;`, question: "Completa la función para que devuelva la suma de 'a' y 'b'.", answer: ["a + b", "a+b"] },
            { code: `const frutas = ['manzana', 'plátano', 'naranja'];\nconsole.log(frutas[<span class="placeholder">???</span>]);`, question: "Completa el código para que imprima 'plátano' en la consola.", answer: "1" },
            { code: `const persona = { nombre: 'Juan', edad: 30 };\nconsole.log(persona.<span class="placeholder">???</span>);`, question: "Completa el código para que imprima el nombre de la persona.", answer: "nombre" },
            { code: `const saludo = 'Hola mundo';\nconsole.log(saludo.<span class="placeholder">???</span>);`, question: "Completa el código para obtener la cantidad de caracteres en la variable 'saludo'.", answer: "length" },
            { code: `let edad = 15;\nif (edad >= 18) {\n  console.log('Eres mayor de edad');\n} <span class="placeholder">???</span> {\n  console.log('Eres menor de edad');\n}`, question: "Falta la palabra clave para la condición alternativa. ¿Cuál es?", answer: "else" },
            { code: `const colores = ['rojo', 'verde'];\ncolores.<span class="placeholder">???</span>('azul');`, question: "Completa el código para agregar el color 'azul' al final del array 'colores'.", answer: "push" }
        ];

        let currentChallengeIndex = 0;
        let completedChallenges = 0;
        const challengeTitleEl = document.getElementById('challengeTitle');
        const codeSnippetEl = document.getElementById('codeSnippet');
        const userInputEl = document.getElementById('userInput');
        const submitBtn = document.getElementById('submitBtn');
        const nextBtn = document.getElementById('nextBtn');
        const feedbackEl = document.getElementById('feedback');
        const winModal = document.getElementById('win-modal');
        const gameContainer = document.querySelector('.game-container');
        const playAgainBtn = document.getElementById('play-again-btn');

      
        function loadChallenge(index) {
            const challenge = challenges[index];
            challengeTitleEl.textContent = challenge.question;
            codeSnippetEl.innerHTML = challenge.code;
            Prism.highlightAll();
            
            userInputEl.value = '';
            // Limpia completamente el feedback anterior
            feedbackEl.textContent = ''; 
            feedbackEl.className = ''; 
            
            userInputEl.disabled = false;
            userInputEl.focus();
            submitBtn.style.display = 'inline-block';
            nextBtn.style.display = 'none';
        }

        
        function checkAnswer() {
            if (userInputEl.disabled) return;
            const userAnswer = userInputEl.value.trim();
            const correctAnswer = challenges[currentChallengeIndex].answer;
            let isCorrect = false;

            if (Array.isArray(correctAnswer)) {
                isCorrect = correctAnswer.map(a => a.toLowerCase()).includes(userAnswer.toLowerCase());
            } else {
                isCorrect = (userAnswer.toLowerCase() === correctAnswer.toLowerCase());
            }

            // Limpia las clases antes de añadir la nueva
            feedbackEl.classList.remove('correct', 'incorrect');

            if (isCorrect) {
                feedbackEl.textContent = "¡Correcto! Buen trabajo.";
                feedbackEl.classList.add('correct'); // Añade la clase 'correct'
                userInputEl.disabled = true;
                submitBtn.style.display = 'none';
                nextBtn.style.display = 'inline-block';
                nextBtn.focus();
                if (!challenges[currentChallengeIndex].completed) {
                    challenges[currentChallengeIndex].completed = true;
                    completedChallenges++;
                }
            } else {
                feedbackEl.textContent = "Incorrecto, ¡inténtalo de nuevo!";
                feedbackEl.classList.add('incorrect'); // Añade la clase 'incorrect'
            }
        }
        
        function nextChallenge() {
            if (completedChallenges === challenges.length) {
                showWinModal();
                return;
            }
            currentChallengeIndex = (currentChallengeIndex + 1) % challenges.length;
            loadChallenge(currentChallengeIndex);
        }

        function showWinModal() {
            setTimeout(() => {
                gameContainer.style.display = 'none';
                winModal.classList.remove('hide');
            }, 300);

            updateUserProgress(); 
        }

        function resetGame() {
            challenges.forEach(c => c.completed = false);
            completedChallenges = 0;
            currentChallengeIndex = 0;
            winModal.classList.add('hide');
            gameContainer.style.display = 'block';
            loadChallenge(currentChallengeIndex);
        }


        async function updateUserProgress() {
    try {
        // El método GET es el predeterminado, no necesitas especificarlo.
        const response = await fetch('../../admin/actualizar.php?juego=6'); 
        const result = await response.json();
        // ...manejar la respuesta
    } catch (error) {
        console.error("Error de red:", error);
    }
}

        submitBtn.addEventListener('click', checkAnswer);
        nextBtn.addEventListener('click', nextChallenge);
        playAgainBtn.addEventListener('click', resetGame);

        

// Reemplaza los listeners anteriores por este único listener:
document.addEventListener('keydown', (event) => {
    if (event.key !== 'Enter') return;

    // Evita comportamiento por defecto (p. ej. envío de formulario)
    event.preventDefault();

    // Si el focus está en el input, priorizamos "Comprobar"
    if (document.activeElement === userInputEl) {
        if (submitBtn.style.display !== 'none' && !submitBtn.disabled) {
            checkAnswer();
            return;
        }
        if (nextBtn.style.display !== 'none') {
            nextChallenge();
            return;
        }
        return;
    }

    // Si el foco NO está en el input, usamos la visibilidad de botones
    if (submitBtn.style.display !== 'none' && !submitBtn.disabled) {
        checkAnswer();
    } else if (nextBtn.style.display !== 'none') {
        nextChallenge();
    }
});

        loadChallenge(currentChallengeIndex);
    });