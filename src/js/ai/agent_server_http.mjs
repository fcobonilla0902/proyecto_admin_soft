// src/js/ai/agent_server_http.mjs
import express from 'express';
import cors from 'cors';
import { call_agent } from './agent.js';

const app = express();
const PORT = 3000;

// Middleware
app.use(cors());
app.use(express.json());

// Ruta POST para recibir prompts
app.post('/agent', async (req, res) => {
  const prompt = req.body.prompt || '';

  if (!prompt) {
    return res.status(400).json({ error: 'Missing prompt' });
  }

  try {
    const response = await call_agent(prompt);
    res.json({ response });
  } catch (e) {
    console.error(e);
    res.status(500).json({ error: 'Agent failed', details: e?.message });
  }
});

// Start server
app.listen(PORT, () => {
  console.log(`🧠 Agent HTTP server running at http://localhost:${PORT}/agent`);
});
