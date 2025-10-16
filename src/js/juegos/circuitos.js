document.addEventListener('DOMContentLoaded', () => {

    const levels = [
        { name: "Compuerta AND", gate: "AND", inputs: 2, image: "/src/img/juegos/circuitos/and_gate.png", instruction: "Una compuerta AND solo se enciende si AMBAS entradas están activas (1)." },
        { name: "Compuerta OR", gate: "OR", inputs: 2, image: "/src/img/juegos/circuitos/or_gate.png", instruction: "Una compuerta OR se enciende si CUALQUIERA de sus entradas está activa (1)." },
        { name: "Compuerta NAND", gate: "NAND", inputs: 2, image: "/src/img/juegos/circuitos/nand_gate.png", instruction: "Una compuerta NAND se enciende siempre, EXCEPTO cuando ambas entradas están activas." },
        { name: "Compuerta NOR", gate: "NOR", inputs: 2, image: "/src/img/juegos/circuitos/nor_gate.png", instruction: "Una compuerta NOR solo se enciende cuando AMBAS entradas están inactivas (0)." },
        { name: "Compuerta NOT", gate: "NOT", inputs: 1, image: "/src/img/juegos/circuitos/not_gate.png", instruction: "Una compuerta NOT INVIERTE la entrada. Si la entrada es 0, la salida es 1." },
        { name: "Compuerta XOR", gate: "XOR", inputs: 2, image: "/src/img/juegos/circuitos/xor_gate.png", instruction: "Una compuerta XOR se enciende solo si las entradas son DIFERENTES." }
    ];

    let currentLevelIndex = 0;

    // --- ELEMENTOS DEL DOM ---
    const gameContainer = document.getElementById('game-container');      
    const winModal = document.getElementById('win-modal');               
    const playAgainBtn = document.getElementById('play-again-btn');       
    
    const levelTitle = document.getElementById('level-title');
    const input1 = document.getElementById('input1');
    const input2 = document.getElementById('input2');
    const inputContainer2 = document.getElementById('input-container-2');
    const gateImage = document.getElementById('gate-image');
    const led = document.getElementById('led');
    const instructions = document.querySelector('#instructions p');
    const winMessage = document.getElementById('win-message');
    const nextLevelBtn = document.getElementById('next-level-btn');

    function calculateOutput(gate, val1, val2) {
        switch (gate) {
            case 'AND': return val1 && val2;
            case 'OR':  return val1 || val2;
            case 'NOT': return !val1;
            case 'XOR': return val1 !== val2;
            case 'NAND': return !(val1 && val2);
            case 'NOR':  return !(val1 || val2);
            default: return false;
        }
    }

    function loadLevel(levelIndex) {
        const level = levels[levelIndex];

        levelTitle.textContent = `Nivel ${levelIndex + 1}: ${level.name}`;
        gateImage.src = level.image;
        instructions.textContent = level.instruction;
        instructions.classList.remove('hidden');
        winMessage.classList.add('hidden');

        if (level.inputs === 1) {
            inputContainer2.style.display = 'none';
        } else {
            inputContainer2.style.display = 'flex';
        }

        let initialInput1 = false;
        let initialInput2 = false;

        if (level.gate === 'NAND') {
            initialInput1 = true;
            initialInput2 = true;
        } else {
            const isInitialStateWin = calculateOutput(level.gate, false, false);
            if (isInitialStateWin) {
                initialInput1 = true;
            }
        }

        input1.checked = initialInput1;
        input2.checked = initialInput2;

        updateCircuit();
    }

    function updateCircuit() {
        const val1 = input1.checked;
        const val2 = input2.checked;
        const currentGate = levels[currentLevelIndex].gate;
        const output = calculateOutput(currentGate, val1, val2);

        if (output) {
            led.classList.remove('led-off');
            led.classList.add('led-on');
            winMessage.classList.remove('hidden');
            instructions.classList.add('hidden');
        } else {
            led.classList.remove('led-on');
            led.classList.add('led-off');
        }
    }

    function goToNextLevel() {
        currentLevelIndex++;
        // --- LÓGICA DE FIN DE JUEGO---
        if (currentLevelIndex >= levels.length) {
            // Si ya no hay más niveles, oculta el juego y muestra el modal
            gameContainer.style.display = 'none';
            winModal.classList.remove('hide');
            updateUserProgress();
        } else {
            // Si todavía hay niveles, carga el siguiente
            loadLevel(currentLevelIndex);
        }
    }


        async function updateUserProgress() {
    try {
        // El método GET es el predeterminado, no necesitas especificarlo.
        const response = await fetch('../../admin/actualizar.php?juego=3'); 
        const result = await response.json();
        // ...manejar la respuesta
    } catch (error) {
        console.error("Error de red:", error);
    }
}

    // --- NUEVO EVENTO PARA EL BOTÓN "JUGAR DE NUEVO" ---
    playAgainBtn.addEventListener('click', () => {
        winModal.classList.add('hide');      
        gameContainer.style.display = 'block'; 
        currentLevelIndex = 0;                 
        loadLevel(currentLevelIndex);          
    });

    input1.addEventListener('change', updateCircuit);
    input2.addEventListener('change', updateCircuit);
    nextLevelBtn.addEventListener('click', goToNextLevel);

    loadLevel(currentLevelIndex);
});