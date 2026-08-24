import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-principal',
  templateUrl: './principal.component.html',
  styleUrls: ['./principal.component.css']
})
export class PrincipalComponent implements OnInit {
    public classApplied: boolean = false;
    public tabIndex: number = 0;
    constructor() { }

    ngOnInit(): void {
    }
    toggleClass() {
        this.classApplied = !this.classApplied;
    }
    
    onTabClick(index: number){
        this.tabIndex = index;
    }

}
