import "@xterm/xterm/css/xterm.css";
import "./style.css";
import { Terminal } from "@xterm/xterm";

const term = new Terminal({
  cursorBlink: true,
  windowOptions: {
    fullscreenWin: true,
  },
});

const app = document.getElementById("terminal");
term.open(app);
