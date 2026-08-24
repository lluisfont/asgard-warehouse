import { Pipe, PipeTransform } from '@angular/core';

@Pipe({ name: 'appFilter' })
export class FilterPipe implements PipeTransform {
  /**
   * Transform
   *
   * @param {any[]} items
   * @param {string} searchText
   * @returns {any[]}
   */
  transform(items: any[], searchText: string, searchField: string): any[] {
    if (!items) {
      return [];
    }
    if (!searchText) {
      return items;
    }
    searchText = searchText.toLocaleLowerCase();

    if (!searchField) {
        return items.filter(function(obj) {
            //console.log(person);
            var flag = false;
            Object.values(obj).forEach((val) => {
              if(String(val).toLocaleLowerCase().includes(searchText)) {
                flag = true;
                return;
              }    
            });
            return flag;


            //return person.ciudad.toLocaleLowerCase().includes(searchText);
        });
    }else{
        return items.filter(singleItem => {
            return (singleItem != null && singleItem[searchField] != null &&  singleItem[searchField] != undefined && searchText.indexOf(singleItem[searchField]) >= 0);
        });
    }
        
    
    /*
    return items.filter(it => {
      return it.toLocaleLowerCase().includes(searchText);
    });
    */
  }
}