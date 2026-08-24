import { Component, OnInit } from '@angular/core';
import {formatDate} from '@angular/common';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
    title = 'atlantes';
    public mesactual: number;
    
    constructor() {
        this.mesactual = parseInt(formatDate(new Date(), 'MM', 'en'));
    }
    
}
