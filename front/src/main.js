import "./style.css";
import term from "./terminal";

const app = document.getElementById("terminal");
if (!app) return;
term.open(app);
