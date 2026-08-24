import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';

@Component({
    selector: 'app-chatbot',
    templateUrl: './chatbot.component.html',
    styleUrl: './chatbot.component.css'
})
export class ChatbotComponent implements OnInit {
    API_URL = 'https://n8n.kpogroup.bo/webhook/asgard-chatbot';
    

  isOpen = false;
  initialized = false;
  message = '';
  sending = false;
  files: File[] = [];
  fileLabel = '';

  sessionId!: string;

  ngOnInit(): void {
    this.sessionId = localStorage.getItem('asgardSessionId') || crypto.randomUUID();
    localStorage.setItem('asgardSessionId', this.sessionId);
  }

  toggleChat(): void {
    this.isOpen = !this.isOpen;

    if (this.isOpen && !this.initialized) {
      this.addMessage(
        'bot',
        'Hola, soy KPO_Bot, un asistente de IA de KPOGROUP. Puedo ayudarte con Asgard y crear tickets de soporte.'
      );
      this.initialized = true;
    }
  }

  onFileChange(event: any): void {
    this.files = Array.from(event.target.files);
    this.fileLabel =
      this.files.length === 1
        ? this.files[0].name
        : `${this.files.length} archivos seleccionados`;
  }

  async sendMessage(event: Event): Promise<void> {
    event.preventDefault();

    if (!this.message && !this.files.length) return;

    const userText = this.message || '[Archivo(s) enviado(s)]';
    this.addMessage('user', userText);

    const formData = new FormData();
    formData.append(
      'message',
      this.message || 'Envío un archivo adjunto relacionado con el problema.'
    );
    formData.append('sessionId', this.sessionId);

    this.files.forEach(f => formData.append('file', f));

    this.message = '';
    this.files = [];
    this.fileLabel = '';
    this.sending = true;

    try {
      const res = await fetch(this.API_URL, {
        method: 'POST',
        body: formData,
      });

      const data = await res.json();
      this.addMessage('bot', data.reply || 'He procesado tu mensaje.');
    } catch {
      this.addMessage('bot', 'Error de conexión con el servidor.');
    } finally {
      this.sending = false;
    }
  }

  addMessage(who: 'user' | 'bot', text: string): void {
    const container = document.getElementById('chat-messages');
    if (!container) return;

    const div = document.createElement('div');
    div.className = `msg ${who}`;
    div.textContent = text;

    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
  }
}
