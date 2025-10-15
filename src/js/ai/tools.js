import { tool } from '@langchain/core/tools';
import { z } from 'zod';

// Tool para retornar al usuario la pagina que quiere visitar
export const get_url = tool(async (input) => {
  // Devolver url completa
  if (typeof window !== "undefined") {
    return {
      success: true,
      message: "Usuario redirigido exitosamente.",
      url: `${window.location.origin}/${input.pagina}`
    };
  }
  
  // Devolver url parcial si la tool no se ejecuta en el cliente
  return {
    success: true,
    message: "Usuario redirigido exitosamente.",
    url: `/${input.pagina}`
  };
}, {
  // Tool schema and metadata
  name: 'get_url',
  description: 'Redirige o devuelve la url de alguna de las páginas de la aplicación web.',
  schema: z.object({
    pagina: z.enum([
      "inicio", 
      "juegos", 
      "comunidad", 
      "soporte", 
      "industria"
    ]).describe('Nombre de la página a la que el usuario quiere ir.'), 
  })
})
