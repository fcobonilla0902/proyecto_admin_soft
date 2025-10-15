document.addEventListener('DOMContentLoaded', () => {
    // --- REFERENCIAS A ELEMENTOS DEL DOM ---
    const words = ["ROBOT", "IA", "ALGORITMO", "DATOS", "RED", "NEXO", "NUBE", "CODIGO", "DRON", "SENSOR", "LOGICA"];
    const gridSize = 15;
    const grid = Array.from({ length: gridSize }, () => Array(gridSize).fill(''));
    const gridContainer = document.getElementById('grid-container');
    const wordsList = document.getElementById('words-list');
    const winModal = document.getElementById('win-modal');
    const playAgainBtn = document.getElementById('play-again-btn');
    const gameContainer = document.getElementById('game-container');
    const wordsContainer = document.getElementById('words-container');

    let selection = [];
    let isSelecting = false;
    let startCell = null;

    // --- LÓGICA DE COLOCACIÓN DE PALABRAS ---
    function placeWords() {
        // Limpia la cuadrícula interna para reinicios
        for (let r = 0; r < gridSize; r++) {
            for (let c = 0; c < gridSize; c++) {
                grid[r][c] = '';
            }
        }
        words.forEach(word => {
            let placed = false;
            let attempts = 0;
            while (!placed && attempts < 500) {
                const direction = Math.floor(Math.random() * 3);
                const row = Math.floor(Math.random() * gridSize);
                const col = Math.floor(Math.random() * gridSize);
                if (canPlaceWord(word, row, col, direction)) {
                    placeWord(word, row, col, direction);
                    placed = true;
                }
                attempts++;
            }
            if (!placed) console.warn(`No se pudo colocar la palabra: ${word}`);
        });
    }

    function canPlaceWord(word, row, col, direction) {
        if (direction === 0) { // Horizontal
            if (col + word.length > gridSize) return false;
            if (col > 0 && grid[row][col - 1] !== '') return false;
            if (col + word.length < gridSize && grid[row][col + word.length] !== '') return false;
            for (let i = 0; i < word.length; i++) {
                if (grid[row][col + i] !== '' && grid[row][col + i] !== word[i]) return false;
            }
        } else if (direction === 1) { // Vertical
            if (row + word.length > gridSize) return false;
            if (row > 0 && grid[row - 1][col] !== '') return false;
            if (row + word.length < gridSize && grid[row + word.length][col] !== '') return false;
            for (let i = 0; i < word.length; i++) {
                if (grid[row + i][col] !== '' && grid[row + i][col] !== word[i]) return false;
            }
        } else { // Diagonal
            if (row + word.length > gridSize || col + word.length > gridSize) return false;
            if (row > 0 && col > 0 && grid[row - 1][col - 1] !== '') return false;
            if (row + word.length < gridSize && col + word.length < gridSize && grid[row + word.length][col + word.length] !== '') return false;
            for (let i = 0; i < word.length; i++) {
                if (grid[row + i][col + i] !== '' && grid[row + i][col + i] !== word[i]) return false;
            }
        }
        return true;
    }

    function placeWord(word, row, col, direction) {
        for (let i = 0; i < word.length; i++) {
            if (direction === 0) grid[row][col + i] = word[i];
            else if (direction === 1) grid[row + i][col] = word[i];
            else grid[row + i][col + i] = word[i];
        }
    }

    function fillGridWithRandomLetters() {
        const alphabet = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
        for (let r = 0; r < gridSize; r++) {
            for (let c = 0; c < gridSize; c++) {
                if (grid[r][c] === '') {
                    grid[r][c] = alphabet[Math.floor(Math.random() * alphabet.length)];
                }
            }
        }
    }

    function createGrid() {
        gridContainer.innerHTML = '';
        for (let r = 0; r < gridSize; r++) {
            for (let c = 0; c < gridSize; c++) {
                const cell = document.createElement('div');
                cell.classList.add('grid-cell');
                cell.textContent = grid[r][c];
                cell.dataset.row = r;
                cell.dataset.col = c;
                gridContainer.appendChild(cell);
            }
        }
    }

    function updateWordsList() {
        wordsList.innerHTML = '';
        words.sort().forEach(word => {
            const li = document.createElement('li');
            li.textContent = word;
            li.id = `word-${word}`;
            wordsList.appendChild(li);
        });
    }

    function checkSelection() {
        if (selection.length === 0) return;
        let selectedString = selection.map(cell => cell.textContent).join('');
        let reversedSelectedString = selectedString.split('').reverse().join('');

        words.forEach(word => {
            if (word === selectedString || word === reversedSelectedString) {
                const wordLi = document.getElementById(`word-${word}`);
                if (wordLi && !wordLi.classList.contains('found')) {
                    wordLi.classList.add('found');
                    selection.forEach(cell => {
                        cell.classList.remove('selected');
                        cell.classList.add('found');
                    });
                    
                    checkWinCondition();
                }
            }
        });
    }
    
    function checkWinCondition() {
        const foundWordsCount = document.querySelectorAll('#words-list li.found').length;
        if (foundWordsCount === words.length) {
            setTimeout(() => {
                gameContainer.style.display = 'none';
                wordsContainer.style.display = 'none';
                winModal.classList.remove('hide');
                updateUserProgress();
                
            }, 500);
        }
    }

    function checkWinCondition2() {
            setTimeout(() => {
                gameContainer.style.display = 'none';
                wordsContainer.style.display = 'none';
                winModal.classList.remove('hide');
                updateUserProgress();
                
            }, 500);
    }

    gridContainer.addEventListener('mousedown', (e) => {
        const cell = e.target.closest('.grid-cell');
        if (cell) {
            isSelecting = true;
            startCell = cell;
            selection = [startCell];
            startCell.classList.add('selected');
        }
    });

    gridContainer.addEventListener('mouseover', (e) => {
        if (!isSelecting) return;
        const endCell = e.target.closest('.grid-cell');
        if (!endCell) return;
        selection.forEach(cell => cell.classList.remove('selected'));
        selection = [];
        const startRow = parseInt(startCell.dataset.row);
        const startCol = parseInt(startCell.dataset.col);
        const endRow = parseInt(endCell.dataset.row);
        const endCol = parseInt(endCell.dataset.col);
        const deltaRow = endRow - startRow;
        const deltaCol = endCol - startCol;
        if (deltaRow === 0 || deltaCol === 0 || Math.abs(deltaRow) === Math.abs(deltaCol)) {
            const stepRow = Math.sign(deltaRow);
            const stepCol = Math.sign(deltaCol);
            const steps = Math.max(Math.abs(deltaRow), Math.abs(deltaCol));
            for (let i = 0; i <= steps; i++) {
                const currentRow = startRow + i * stepRow;
                const currentCol = startCol + i * stepCol;
                const cellNode = document.querySelector(`[data-row='${currentRow}'][data-col='${currentCol}']`);
                if (cellNode) {
                    selection.push(cellNode);
                }
            }
        } else {
            selection = [startCell];
        }
        selection.forEach(cell => cell.classList.add('selected'));
    });

    document.addEventListener('mouseup', () => {
        if (isSelecting) {
            checkSelection();
            if (!selection.every(cell => cell.classList.contains('found'))) {
                selection.forEach(cell => cell.classList.remove('selected'));
            }
            isSelecting = false;
            startCell = null;
            selection = [];
        }
    });
    
    playAgainBtn.addEventListener('click', () => {
        gameContainer.style.display = 'block';
        wordsContainer.style.display = 'block';
        winModal.classList.add('hide');
        initGame();
    });


    async function updateUserProgress() {
    try {
        // El método GET es el predeterminado, no necesitas especificarlo.
        const response = await fetch('../../admin/actualizar.php?juego=2'); 
        const result = await response.json();
        // ...manejar la respuesta
    } catch (error) {
        console.error("Error de red:", error);
    }
}

    function initGame() {
        placeWords();
        fillGridWithRandomLetters();
        createGrid();
        updateWordsList();
    }

    initGame();
});