document.addEventListener('DOMContentLoaded', () => {

    const puzzles = [
        {
            scenario: "Una empresa de retail quiere analizar sus ventas diarias para encontrar el producto más popular en cada región.",
            blocks: ["Limpiar y Formatear", "Extraer Logs de Ventas", "Agregar Ventas por Región"],
            solution: ["Extraer Logs de Ventas", "Limpiar y Formatear", "Agregar Ventas por Región"]
        },
        {
            scenario: "Un hospital necesita predecir los reingresos de pacientes analizando su historial médico.",
            blocks: ["Entrenar Modelo", "Recolectar Historiales", "Preprocesar Datos"],
            solution: ["Recolectar Historiales", "Preprocesar Datos", "Entrenar Modelo"]
        },
        {
            scenario: "Una red social quiere detectar 'fake news' analizando el texto de nuevas publicaciones en tiempo real.",
            blocks: ["Clasificar Noticias", "Procesar Texto (NLP)", "Ingesta de Publicaciones"],
            solution: ["Ingesta de Publicaciones", "Procesar Texto (NLP)", "Clasificar Noticias"]
        },
        {
            scenario: "Una ciudad inteligente busca optimizar el tráfico monitoreando los datos de sensores en las calles.",
            blocks: ["Analizar Tráfico", "Visualizar en Mapa", "Recopilar de Sensores"],
            solution: ["Recopilar de Sensores", "Analizar Tráfico", "Visualizar en Mapa"]
        }
    ];

    let currentPuzzleIndex = 0;

    const scenarioEl = document.getElementById('scenario');
    const pipelineContainer = document.getElementById('pipeline-container');
    const sourceBlocksContainer = document.getElementById('source-blocks');
    const checkBtn = document.getElementById('check-btn');
    const nextBtn = document.getElementById('next-btn');
    const feedbackEl = document.getElementById('feedback');
    const gameContainer = document.getElementById('game-container');
    const winModal = document.getElementById('win-modal');
    const playAgainBtn = document.getElementById('play-again-btn');

    let draggedBlock = null;

    function shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    function loadPuzzle(index) {
        const puzzle = puzzles[index];
        scenarioEl.textContent = puzzle.scenario;
        pipelineContainer.innerHTML = '';
        sourceBlocksContainer.innerHTML = '';
        feedbackEl.className = '';
        feedbackEl.textContent = '';
        
        const shuffledBlocks = shuffle([...puzzle.blocks]);
        shuffledBlocks.forEach(blockText => {
            const block = document.createElement('div');
            block.className = 'block';
            block.textContent = blockText;
            block.draggable = true;
            sourceBlocksContainer.appendChild(block);
        });

        checkBtn.classList.remove('hide');
        nextBtn.classList.add('hide');
        checkBtn.disabled = false;
        addDragAndDropListeners();
    }
    
    function getClosestElement(container, x, y) {
        const draggableElements = [...container.querySelectorAll('.block:not(.dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const centerX = box.left + box.width / 2;
            const centerY = box.top + box.height / 2;
            const distance = Math.sqrt(Math.pow(x - centerX, 2) + Math.pow(y - centerY, 2));

            if (distance < closest.distance) {
                return { distance: distance, element: child };
            } else {
                return closest;
            }
        }, { distance: Number.POSITIVE_INFINITY, element: null }).element;
    }

    function addDragAndDropListeners() {
        document.querySelectorAll('.block').forEach(block => {
            block.addEventListener('dragstart', (e) => {
                draggedBlock = e.target;
                setTimeout(() => e.target.classList.add('dragging'), 0);
            });
            block.addEventListener('dragend', () => {
                if(draggedBlock) draggedBlock.classList.remove('dragging');
            });
        });

        pipelineContainer.addEventListener('dragover', (e) => {
            e.preventDefault();
            pipelineContainer.classList.add('drag-over');
        });

        pipelineContainer.addEventListener('dragleave', () => {
            pipelineContainer.classList.remove('drag-over');
        });

        pipelineContainer.addEventListener('drop', (e) => {
            e.preventDefault();
            pipelineContainer.classList.remove('drag-over');
            if (!draggedBlock) return;

            const closestElement = getClosestElement(pipelineContainer, e.clientX, e.clientY);

            if (closestElement == null) {
                pipelineContainer.appendChild(draggedBlock);
            } else {
                const rect = closestElement.getBoundingClientRect();
                if (e.clientX < rect.left + rect.width / 2) {
                    pipelineContainer.insertBefore(draggedBlock, closestElement);
                } else {
                    pipelineContainer.insertBefore(draggedBlock, closestElement.nextSibling);
                }
            }
        });
        
        sourceBlocksContainer.addEventListener('dragover', e => e.preventDefault());
        sourceBlocksContainer.addEventListener('drop', (e) => {
            e.preventDefault();
            if (draggedBlock) sourceBlocksContainer.appendChild(draggedBlock);
        });
    }

    function checkAnswer() {
        // Evita comprobar si no se han movido todos los bloques
        if (pipelineContainer.children.length !== puzzles[currentPuzzleIndex].blocks.length) {
            return; 
        }

        const userSolution = Array.from(pipelineContainer.children).map(block => block.textContent);
        const correctSolution = puzzles[currentPuzzleIndex].solution;

        if (JSON.stringify(userSolution) === JSON.stringify(correctSolution)) {
            feedbackEl.textContent = '¡Correcto! Has construido el flujo de datos perfecto.';
            feedbackEl.className = 'correct';
            nextBtn.classList.remove('hide');
            checkBtn.classList.add('hide');
            document.querySelectorAll('.block').forEach(b => b.draggable = false);
        } else {
            feedbackEl.textContent = 'Casi... El orden de los pasos no es el correcto. ¡Inténtalo de nuevo!';
            feedbackEl.className = 'incorrect';
        }
    }


async function updateUserProgress() {
    try {
        // El método GET es el predeterminado, no necesitas especificarlo.
        const response = await fetch('../../admin/actualizar.php?juego=5'); 
        const result = await response.json();
        // ...manejar la respuesta
    } catch (error) {
        console.error("Error de red:", error);
    }
}




function handleNext() {
    currentPuzzleIndex++;
    if (currentPuzzleIndex < puzzles.length) {
        loadPuzzle(currentPuzzleIndex);
    } else {
        // Antes de mostrar que ganaste, llama a la función para guardar en la BD
        updateUserProgress(); 
        
        // Luego, muestra la pantalla de victoria
        gameContainer.classList.add('hide');
        winModal.classList.remove('hide');
    }
}


    function resetGame() {
        currentPuzzleIndex = 0;
        gameContainer.classList.remove('hide');
        winModal.classList.add('hide');
        loadPuzzle(currentPuzzleIndex);
    }
    
    // *** TECLA ENTER ***
    function handleKeyPress(e) {
        // Si la tecla presionada no es "Enter", no hace nada
        if (e.key !== 'Enter') {
            return;
        }

        // Si el botón "Siguiente" está visible, lo acciona
        if (!nextBtn.classList.contains('hide')) {
            handleNext();
            return;
        }

        // Si el botón "Comprobar" está visible, lo acciona
        if (!checkBtn.classList.contains('hide')) {
            checkAnswer();
            return;
        }
    }

    // Event Listeners
    checkBtn.addEventListener('click', checkAnswer);
    nextBtn.addEventListener('click', handleNext);
    playAgainBtn.addEventListener('click', resetGame);
    // Agregamos el listener para las teclas a todo el documento
    document.addEventListener('keydown', handleKeyPress);
    
    // Iniciar juego
    loadPuzzle(currentPuzzleIndex);
});