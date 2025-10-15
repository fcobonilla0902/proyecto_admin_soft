const chatContainer = document.getElementById('chat-container');
const chatForm = document.getElementById('chat-form');
const chatInput = document.getElementById('chat-input');

function appendMessage(role, text) {
  const wrapper = document.createElement('div');
  wrapper.className = `message ${role}`;
  wrapper.style.marginBottom = '12px';
  const strong = document.createElement('strong');
  strong.textContent = role === 'user' ? 'Tú: ' : 'Bot: ';
  wrapper.appendChild(strong);
  const span = document.createElement('span');
  span.textContent = text;
  wrapper.appendChild(span);
  chatContainer.appendChild(wrapper);
  chatContainer.scrollTop = chatContainer.scrollHeight;
}

async function sendMessage(text) {
  appendMessage('user', text);
  chatInput.value = '';

  const loading = document.createElement('div');
  loading.textContent = 'Escribiendo...';
  loading.style.color = '#666';
  chatContainer.appendChild(loading);
  chatContainer.scrollTop = chatContainer.scrollHeight;

  try {
    const url = 'http://localhost:3000/agent';
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ prompt: text })
    });

    const data = await res.json();
    loading.remove();

    if (!res.ok) {
      const detail = data?.details || data?.error || 'Error desconocido';
      const code = res.status;
      appendMessage('bot', `Error ${code}: ${detail}`);
      return;
    }

    appendMessage('bot', data.response ?? '');
  } catch (e) {
    loading.remove();
    appendMessage('bot', 'No se pudo conectar con el servidor.');
  }
}

chatForm.addEventListener('submit', (e) => {
  e.preventDefault();
  const text = chatInput.value.trim();
  if (!text) return;
  sendMessage(text);
});


