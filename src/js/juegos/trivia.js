document.addEventListener('DOMContentLoaded', () => {
    const startContainer = document.getElementById('start-container');
    const quizContainer = document.getElementById('quiz-container');

    const startBtn = document.getElementById('start-btn');
    const questionEl = document.getElementById('question');
    const optionsContainer = document.getElementById('options-container');
    const nextBtn = document.getElementById('next-btn');
    const progressEl = document.getElementById('progress');
    const scoreEl = document.getElementById('score');
    const restartBtn = document.getElementById('restart-btn');
    const quiz = document.getElementById('quiz');
    const winModal = document.getElementById('win-modal');

    const questions = [
        {
            question: '¿Cuál de las siguientes tecnologías es un pilar fundamental de la Industria 4.0?',
            options: [
                { text: 'Máquinas de vapor', correct: false },
                { text: 'Inteligencia Artificial (IA)', correct: true },
                { text: 'Telégrafo', correct: false },
                { text: 'Imprenta', correct: false }
            ]
        },
        {
            question: '¿Qué concepto describe la interconexión digital de objetos cotidianos con internet?',
            options: [
                { text: 'Realidad Virtual', correct: false },
                { text: 'Blockchain', correct: false },
                { text: 'Internet de las Cosas (IoT)', correct: true },
                { text: 'Computación Cuántica', correct: false }
            ]
        },
        {
            question: 'El análisis de grandes volúmenes de datos para encontrar patrones y tendencias se conoce como:',
            options: [
                { text: 'Small Data', correct: false },
                { text: 'Big Data', correct: true },
                { text: 'Data Ficticia', correct: false },
                { text: 'Data Oculta', correct: false }
            ]
        },
        {
            question: '¿Qué tecnología permite a las máquinas aprender y tomar decisiones sin ser explícitamente programadas para ello?',
            options: [
                { text: 'Machine Learning', correct: true },
                { text: 'Realidad Aumentada', correct: false },
                { text: 'Impresión 3D', correct: false },
                { text: 'GPS', correct: false }
            ]
        },
        {
            question: '¿Cuál es uno de los principales objetivos de la Ciberseguridad en el entorno de la Industria 4.0?',
            options: [
                { text: 'Acelerar la producción', correct: false },
                { text: 'Proteger los sistemas y datos de ataques digitales', correct: true },
                { text: 'Reducir el consumo de energía', correct: false },
                { text: 'Mejorar el diseño de productos', correct: false }
            ]
        },
        {
            question: 'La creación de un modelo virtual de un producto o proceso físico se denomina:',
            options: [
                { text: 'Simulación Estándar', correct: false },
                { text: 'Copia de Seguridad', correct: false },
                { text: 'Prototipo Físico', correct: false },
                { text: 'Gemelo Digital', correct: true }
            ]
        },
        {
            question: '¿Qué tecnología se utiliza para la fabricación de objetos tridimensionales capa por capa a partir de un diseño digital?',
            options: [
                { text: 'Impresión 3D', correct: true },
                { text: 'Escáner 2D', correct: false },
                { text: 'Fotocopiadora', correct: false },
                { text: 'Proyector holográfico', correct: false }
            ]
        },
        {
            question: 'Los robots que pueden trabajar de forma segura junto a los humanos en un entorno de producción se conocen como:',
            options: [
                { text: 'Robots Peligrosos', correct: false },
                { text: 'Androides', correct: false },
                { text: 'Robots Colaborativos (Cobots)', correct: true },
                { text: 'Drones de carga', correct: false }
            ]
        },
        {
            question: '¿Qué modelo de servicio en la nube (Cloud Computing) ofrece infraestructura de TI como servidores y almacenamiento a través de internet?',
            options: [
                { text: 'SaaS (Software as a Service)', correct: false },
                { text: 'PaaS (Platform as a Service)', correct: false },
                { text: 'IaaS (Infrastructure as a Service)', correct: true },
                { text: 'FaaS (Function as a Service)', correct: false }
            ]
        },
        {
            question: '¿Cuál es el principal beneficio de la automatización en la Industria 4.0?',
            options: [
                { text: 'Aumento del error humano', correct: false },
                { text: 'Mayor precisión y productividad', correct: true },
                { text: 'Menor eficiencia', correct: false },
                { text: 'Mayor costo de producción', correct: false }
            ]
        }
    ];

    let currentQuestionIndex = 0;
    let score = 0;

    startBtn.addEventListener('click', startGame);
    
    // Función anónima para el botón "Siguiente"
    const goToNextQuestion = () => {
        currentQuestionIndex++;
        if (currentQuestionIndex < questions.length) {
            showQuestion();
        } else {
            showResults();
        }
    };

    nextBtn.addEventListener('click', goToNextQuestion);
    restartBtn.addEventListener('click', startGame);


    // Event listener para la tecla Enter
    document.addEventListener('keydown', (event) => {
        // Si se presiona 'Enter' y el botón 'Siguiente' no está oculto
        if (event.key === 'Enter' && !nextBtn.classList.contains('hidden')) {
            goToNextQuestion(); // Llama a la misma función que el botón
        }
    });


    function startGame() {
        currentQuestionIndex = 0;
        score = 0;
        startContainer.classList.add('hidden');
        winModal.classList.add('hidden');
        nextBtn.classList.add('hidden');
        quizContainer.classList.remove('hidden');
        quiz.classList.remove('hidden');
        showQuestion();
    }

    function showQuestion() {
        resetState();
        const question = questions[currentQuestionIndex];
        questionEl.innerText = question.question;

        question.options.forEach(option => {
            const button = document.createElement('button');
            button.innerText = option.text;
            button.addEventListener('click', () => selectAnswer(button, option.correct));
            optionsContainer.appendChild(button);
        });

        progressEl.innerText = `Pregunta ${currentQuestionIndex + 1} de ${questions.length}`;
    }

    function resetState() {
        nextBtn.classList.add('hidden');
        while (optionsContainer.firstChild) {
            optionsContainer.removeChild(optionsContainer.firstChild);
        }
    }

    function selectAnswer(selectedButton, isCorrect) {
        if (isCorrect) {
            score++;
            selectedButton.classList.add('correct');
        } else {
            selectedButton.classList.add('incorrect');
        }

        Array.from(optionsContainer.children).forEach(button => {
            button.disabled = true;
            const correctOptionData = questions[currentQuestionIndex].options.find(opt => opt.correct);
            if (button.innerText === correctOptionData.text) {
                button.classList.add('correct');
            }
        });

        nextBtn.classList.remove('hidden');
    }


    async function updateUserProgress() {
    try {
        // El método GET es el predeterminado, no necesitas especificarlo.
        const response = await fetch('../../admin/actualizar.php?juego=1'); 
        const result = await response.json();
        // ...manejar la respuesta
    } catch (error) {
        console.error("Error de red:", error);
    }
}

    function showResults() {
        quiz.classList.add('hidden');
        winModal.classList.remove('hidden');
        scoreEl.innerText = `Tu puntuación es: ${score} de ${questions.length}`;
        
        if(score == questions.length){
            updateUserProgress(); 
        }
    }
});