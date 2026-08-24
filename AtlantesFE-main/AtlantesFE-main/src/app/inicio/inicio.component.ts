import { Component, OnInit } from '@angular/core';

declare var $: any;

@Component({
  selector: 'app-inicio',
  templateUrl: './inicio.component.html',
  styleUrls: ['./inicio.component.css']
})
export class InicioComponent implements OnInit {
    
    constructor() { 
        //this.clasePrincipal='app-login';
    }

    ngOnInit(): void {
        //$('#exampleModal').modal('show');
    }
    
    

}
