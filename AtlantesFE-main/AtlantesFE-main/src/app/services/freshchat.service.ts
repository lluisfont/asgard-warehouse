import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';

declare global {
  interface Window {
    fcWidget?: any;      // Freshchat
    FreshWidget?: any;   // Freshservice
  }
}

@Injectable({
  providedIn: 'root',
})
export class FreshchatService {
    private freshchatLoaded = false;
    private freshserviceLoaded = false;
    
    loadFreshchat(): void {
        if (this.freshchatLoaded) {
            this.initFreshchat();
            return;
        }

        const scriptId = 'Freshchat-js-sdk';
        const existingScript = document.getElementById(scriptId);

        if (existingScript) {
            this.freshchatLoaded = true;
            this.initFreshchat();
            return;
        }

        const script = document.createElement('script');
        script.id = scriptId;
        script.async = true;
        script.src = 'https://kposrlhelpdesk-help.freshchat.com/js/widget.js';
        script.onload = () => {
            this.freshchatLoaded = true;
            this.initFreshchat();
        };

        document.head.appendChild(script);
    }

    private initFreshchat(): void {
        if (!window.fcWidget) {
            console.warn('Freshchat: fcWidget no está disponible todavía');
            return;
        }

        if (!environment.freshchatToken) {
            console.warn('Freshchat: token no configurado');
            return;
        }

        window.fcWidget.init({
            token: environment.freshchatToken,
            host: 'https://kposrlhelpdesk-help.freshchat.com',
        });
    }
    
    loadFreshserviceWidget(): void {
        if (this.freshserviceLoaded) {
            this.initFreshserviceWidget();
            return;
        }

        const scriptId = 'Freshservice-widget-js';
        const existingScript = document.getElementById(scriptId);

        if (existingScript) {
            this.freshserviceLoaded = true;
            this.initFreshserviceWidget();
            return;
        }

        const script = document.createElement('script');
        script.id = scriptId;
        script.async = true;
        script.src = 'https://assets.freshservice.com/widget/freshwidget.js';
        script.onload = () => {
            this.freshserviceLoaded = true;
            this.initFreshserviceWidget();
        };

        document.head.appendChild(script);
    }

    private initFreshserviceWidget(): void {
        if (!window.FreshWidget) {
            console.warn('Freshservice: FreshWidget no está disponible todavía');
            return;
        }

        window.FreshWidget.init('', {
            queryString:
                '&widgetType=popup&formTitle=Centro%20de%20Soporte%20Asgard&submitThanks=Gracias%20por%20contactarnos.%20%0D%0AHemos%20creado%20un%20ticket%20de%20soporte%20para%20tu%20solicitud.%20Por%20favor%2C%20revisa%20tu%20bandeja%20de%20correo%20para%20dar%20seguimiento.%20En%20breve%2C%20un%20agente%20de%20soporte%20se%20comunicar%C3%A1%20contigo.&searchArea=no',
            utf8: '✓',
            widgetType: 'popup',
            buttonType: 'text',
            buttonText: 'Centro de Soporte Asgard',
            buttonColor: "#ffffff",     // Texto del botón en blanco
            buttonBg: "#16215b",       // Fondo del botón en azul oscuro
            //buttonColor: 'black',
            //buttonBg: '#dde386',
            alignment: '2',
            submitThanks:
                'Gracias por contactarnos. \r\nHemos creado un ticket de soporte para tu solicitud. Por favor, revisa tu bandeja de correo para dar seguimiento. En breve, un agente de soporte se comunicará contigo.',
            formHeight: '500px', // corregido
            url: 'https://soporte.kpogroup.bo',
        });
    }
}
