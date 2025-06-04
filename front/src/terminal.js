import "@xterm/xterm/css/xterm.css";
import { Terminal } from "@xterm/xterm";
import { FitAddon } from "@xterm/addon-fit";

const term = new Terminal({
  cursorBlink: true,
});
const fitAddon = new FitAddon();

term.loadAddon(fitAddon);

export default term;
