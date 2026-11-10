# Xpectrum

Visuales en vivo para acompañar sets de DJ, controladas con un **Akai MPK mini** y reactivas a la música. Están pensadas para electrónica: house, circuit y trance.

Corre en el navegador (Chrome o Edge), sin instalar nada. Hecho por [Oh! Valentino](https://ohvalentino.substack.com).

## Qué hace

- **11 visuales:** Túnel, Espectro, Partículas (bokeh, niebla y destello anamórfico), Flujo, Distorsión (esfera de vidrio en blanco y negro sobre un video), Ondas, Caleidoscopio, Estrobo, Rejilla, Solo media y Corazón.
- **Biblioteca de visuales con miniaturas:** arrastra cualquier visual a uno de los 8 pads (o usa 📌). Las miniaturas se capturan solas.
- **Detección de bombo y BPM** (110–160), bajo y hats por separado, más breakdowns y drops automáticos.
- **Acabado de cine:** bloom, viñeta y grano de película.
- **Audio:** comparte una pestaña de Chrome (YouTube, etc.) con "compartir audio", o usa el micrófono o una entrada de audio (la salida del mixer).
- **8 espacios de media** para logos, fotos o videos, lanzados con los pads.
- **Biblioteca** de videos e imágenes que se guarda en el disco (requiere PHP).
- **Mensaje** en pantalla, por ejemplo "FELIZ 2027", que late con el bajo.
- **MIDI learn** para asignar cualquier pad o perilla.

## Controles (MPK mini mk3 / IV)

| Control | Acción |
|---|---|
| Pads banco A | Los 8 visuales que asignes en el panel |
| Pads banco B | Espacios de media Q–I |
| Perillas 1–8 | Reactividad, velocidad, color, estela, zoom, giro, espejo, opacidad del media |
| Teclas | Ráfagas de partículas (la tecla define el color, la fuerza el tamaño) |
| Joystick | Color y zoom |

**Teclado del computador:** 1–8 pads · 9/0 visual anterior/siguiente · Q–I media · ⌫ quitar media · Espacio tap tempo · Enter mensaje · H ocultar panel · F pantalla completa.

## Cómo usarlo

### Con XAMPP (recomendado, activa la biblioteca)
1. Copia esta carpeta a `htdocs/xpectrum`.
2. Enciende Apache: `sudo /Applications/XAMPP/xamppfiles/xampp startapache`
3. Abre `http://localhost/xpectrum/` en Chrome, con el MPK mini conectado.

### Sin servidor
Abre `index.html` en Chrome. Todo funciona menos la biblioteca, que necesita `api.php`.

## Archivos

- `index.html`: la aplicación completa.
- `api.php`: biblioteca (listar, subir por trozos, borrar). Solo responde a peticiones desde el mismo computador.
- `biblioteca/`: tus videos e imágenes. Está ignorada en git.
