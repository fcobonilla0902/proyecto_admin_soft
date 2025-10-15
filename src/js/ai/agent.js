import { ChatGoogleGenerativeAI } from "@langchain/google-genai";
import { HarmBlockThreshold, HarmCategory } from "@google/generative-ai";
import { createReactAgent } from "@langchain/langgraph/prebuilt";
import { MemorySaver } from "@langchain/langgraph";
import { HumanMessage } from "@langchain/core/messages";
import { get_url } from "./tools.js";

// Configuracion de seguridad para que no nos bloqueen mensajes
const safetySettings = [
  {category: HarmCategory.HARM_CATEGORY_HARASSMENT, threshold: HarmBlockThreshold.BLOCK_NONE},
  {category: HarmCategory.HARM_CATEGORY_DANGEROUS_CONTENT, threshold: HarmBlockThreshold.BLOCK_NONE},
  {category: HarmCategory.HARM_CATEGORY_CIVIC_INTEGRITY, threshold: HarmBlockThreshold.BLOCK_NONE},
  {category: HarmCategory.HARM_CATEGORY_HATE_SPEECH, threshold: HarmBlockThreshold.BLOCK_NONE},
  {category: HarmCategory.HARM_CATEGORY_SEXUALLY_EXPLICIT, threshold: HarmBlockThreshold.BLOCK_NONE},
]

// Modelo de IA de google
const llm = new ChatGoogleGenerativeAI({
  model: "gemini-2.0-flash",
  apiKey: process.env.GOOGLE_API_KEY, // AQUI VA LA API-KEY "abc..."
  safetySettings,
});

// Memoria del agente de IA
const checkpointSaver = new MemorySaver();

// Agente de IA con modelo, memoria y herramientas
const agent = createReactAgent({
  llm,
  checkpointSaver,
  tools: [get_url]
});

// Funcion para llamar al agente agent
// Recibe:
// user_prompt: string = Mensaje del usuario
//
// Retorna:
// response: string = Respuesta del agente de IA
export async function call_agent(user_prompt) {
  const prompt = `
  # Instrucciones del sistema: 
  Eres un amigable asistente de inteligencia artificial. 
  
  Tu tarea unicamente es responder preguntas, y enseñar temas relacionadas a la industria 4.0, y a metodologias de administracion de proyectos de software.

  Si el usuario se sale del tema de conversacion redirijelo para que solo se hablen de los temas principales.

  Tienes las siguientes herramientas:
  - get_url: Retorna la url de la pagina que el usuario quiere visitar, si es que la pagina existe.

  # Instrucciones del usuario:
  ${user_prompt}


  --- 

  Recuerda que solo hablas de: **la industria 4.0, y a metodologias de administracion de proyectos de software**.
  `;

  try {
    const output = await agent.invoke(
      { messages: [new HumanMessage(prompt)] }, 
      { configurable: {"thread_id": "abc"} }
    );
  
    const response = output.messages[output.messages.length - 1].content;
    return response;
  
  } catch (error) {
    console.error(error);
  }
}