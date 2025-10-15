import { call_agent } from './agent.js';

console.log("🧠 Agent server started and waiting for prompt...");

let input = "";
for await (const chunk of process.stdin) {
  input += chunk;
}

// 🧹 Elimina BOM (Byte Order Mark) si lo hay
input = input.replace(/^\uFEFF/, "");

let prompt = "";
try {
  const data = JSON.parse(input || "{}");
  prompt = data.prompt || "";
} catch (e) {
  console.error("❌ Error al parsear input:", e);
}

if (!prompt) {
  console.log(JSON.stringify({ response: "Solicitud vacía." }));
  process.exit(0);
}

const apiKey = process.env.GOOGLE_API_KEY || "";
if (!apiKey) {
  console.error("GOOGLE_API_KEY is not set");
  process.exit(1);
}

function withTimeout(promise, ms) {
  return new Promise((resolve, reject) => {
    const t = setTimeout(() => reject(new Error("Agent call timed out")), ms);
    promise.then((v) => { clearTimeout(t); resolve(v); }).catch((e) => { clearTimeout(t); reject(e); });
  });
}

try {
  const response = await withTimeout(call_agent(prompt), 40000);
  console.log(JSON.stringify({ response }));
  process.exit(0);
} catch (e) {
  console.error(e?.message || String(e));
  process.exit(1);
}
