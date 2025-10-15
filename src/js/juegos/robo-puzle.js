
document.addEventListener('DOMContentLoaded', () => {
    const targetsContainer = document.getElementById('targets-container');
    const partsContainer = document.getElementById('parts-container');
    const matchCounterEl = document.getElementById('match-counter');
    const winModal = document.getElementById('win-modal');
    const gameContainer = document.getElementById('game-container');
    const playAgainBtn = document.getElementById('play-again-btn');

    const robotParts = [
        { id: 'sensor', name: 'Sensor de proximidad', imgSrc: '/src/img/sensor.png' },
        { id: 'microcontrolador', name: 'Microcontrolador', imgSrc: '/src/img/microcontrolador.png' },
        { id: 'servo_motor', name: 'Servo Motor', imgSrc: '/src/img/servo_motor.png' },
        { id: 'chasis', name: 'Chasis', imgSrc: '/src/img/chasis.png' },
        { id: 'fuente', name: 'Fuente de energía', imgSrc: '/src/img/fuente.png' }
    ];

    let correctMatches = 0;
    let draggedPartId = null;

    function initializeGame() {
        targetsContainer.innerHTML = '';
        partsContainer.innerHTML = '';
        correctMatches = 0;
        updateMatchCounter();
        gameContainer.classList.remove('hide');
        winModal.classList.add('hide');

        const shuffledParts = [...robotParts].sort(() => Math.random() - 0.5);

        robotParts.forEach(part => {
            const targetSlot = document.createElement('div');
            targetSlot.classList.add('target-slot');
            targetSlot.dataset.id = part.id;
            targetSlot.textContent = part.name;
            targetsContainer.appendChild(targetSlot);
        });

        shuffledParts.forEach(part => {
            const robotPart = document.createElement('div');
            robotPart.classList.add('robot-part');
            robotPart.dataset.id = part.id;
            robotPart.style.backgroundImage = `url('${part.imgSrc}')`;
            robotPart.draggable = true;
            partsContainer.appendChild(robotPart);
        });

        addDragAndDropListeners();
    }

    function addDragAndDropListeners() {
        const allParts = document.querySelectorAll('.robot-part');
        const allTargets = document.querySelectorAll('.target-slot');

        allParts.forEach(part => {
            part.addEventListener('dragstart', e => {
                draggedPartId = e.target.dataset.id;
                setTimeout(() => e.target.classList.add('dragging'), 0);
            });

            part.addEventListener('dragend', e => {
                e.target.classList.remove('dragging');
            });
        });

        allTargets.forEach(target => {
            target.addEventListener('dragover', e => {
                e.preventDefault();
                if (!target.querySelector('.robot-part')) { 
                    target.classList.add('drag-over');
                }
            });

            target.addEventListener('dragleave', () => {
                target.classList.remove('drag-over');
            });

            target.addEventListener('drop', e => {
                e.preventDefault();
                target.classList.remove('drag-over');
                
                if (target.querySelector('.robot-part')) return; // No permitir soltar si ya hay una pieza

                if (draggedPartId === target.dataset.id) {
                    const draggedElement = document.querySelector(`.robot-part[data-id="${draggedPartId}"]`);
                    
                    target.textContent = ''; 
                    target.appendChild(draggedElement);
                    draggedElement.classList.add('placed');
                    draggedElement.draggable = false;
                    
                    target.classList.add('correct');

                    correctMatches++;
                    updateMatchCounter();
                    
                    if (correctMatches === robotParts.length) {
                        setTimeout(() => gameContainer.classList.add('hide'), 100);
                        setTimeout(() => winModal.classList.remove('hide'), 100);
                        updateUserProgress();
                    }
                }
            });
        });
    }


    async function updateUserProgress() {
        try {
            // El método GET es el predeterminado, no necesitas especificarlo.
            const response = await fetch('../../admin/actualizar.php?juego=4'); 
            const result = await response.json();
            // ...manejar la respuesta
        } catch (error) {
            console.error("Error de red:", error);
        }
    }

    
    function updateMatchCounter() {
        matchCounterEl.textContent = `${correctMatches} / ${robotParts.length}`;
    }

    playAgainBtn.addEventListener('click', initializeGame);

    initializeGame();
});