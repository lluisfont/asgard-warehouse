import { Injectable } from '@angular/core';
import { Workbook } from 'exceljs';
import {ExcelModel} from "../models/excel.model";
import * as fs from 'file-saver';

@Injectable({
    providedIn: 'root'
})
export class ExportExcelService {

    constructor() { }
    
    exportExcel(excelData: ExcelModel){
        const title = excelData.titulo;
        
        let workbook = new Workbook();
        let worksheet = workbook.addWorksheet(title);
        
        for (let cc = 0; cc < excelData.cabecera.length; cc++){
            worksheet.getCell(this.colName(cc)+'1').value = excelData.cabecera[cc].titulo;
            worksheet.getCell(this.colName(cc)+'1').border = {
                top: {style:'thin'},
                left: {style:'thin'},
                bottom: {style:'thin'},
                right: {style:'thin'}
            };
            worksheet.getCell(this.colName(cc)+'1').fill = {
                type: 'pattern',
                pattern:'solid',
                fgColor:{argb:'CCCCCC'},
            };
            if(excelData.cabecera[cc].ancho){
                worksheet.getColumn(cc+1).width = excelData.cabecera[cc].ancho;
            }else{
                worksheet.getColumn(cc+1).width = 20;
            }
            worksheet.getCell(this.colName(cc)+'1').font = {
                //name: 'Comic Sans MS',
                //family: 4,
                //size: 16,
                //underline: true,
                bold: true
            };
        }
        
        for (let dd = 0; dd < excelData.data.length; dd++){
            for (let ii = 0; ii < excelData.data[dd].length; ii++){
                if(excelData.data[dd][ii].borde!='none'){
                    worksheet.getCell(this.colName(ii)+''+(dd+2)).border = {
                        top: {style:'thin'},
                        left: {style:'thin'},
                        bottom: {style:'thin'},
                        right: {style:'thin'}
                    };
                }
                    
                switch(excelData.cabecera[ii].tipo){
                    case 'number':
                        worksheet.getCell(this.colName(ii)+''+(dd+2)).numFmt = '#,##0.00';
                        worksheet.getCell(this.colName(ii)+''+(dd+2)).value = excelData.data[dd][ii].valor;
                        break;
                    case 'date':
                        if (this.isValidDate(excelData.data[dd][ii].valor)){
                            let fecha=new Date(excelData.data[dd][ii].valor);
                            let fechaLocal = new Date(
                                fecha.getFullYear(),
                                fecha.getMonth(),
                                fecha.getDate()
                            );
                            worksheet.getCell(this.colName(ii)+''+(dd+2)).value = fechaLocal;
                            worksheet.getCell(this.colName(ii)+''+(dd+2)).numFmt = 'dd/mm/yyyy';
                        }else{
                            worksheet.getCell(this.colName(ii)+''+(dd+2)).value = excelData.data[dd][ii].valor;
                        }
                        break;
                    case 'datetime':
                        if (this.isValidDate(excelData.data[dd][ii].valor)){
                            let fecha=new Date(excelData.data[dd][ii].valor);
                            let fechahora = (fecha.getTime() - fecha.getTimezoneOffset() * 60000) / 86400000 + 25569;
                            worksheet.getCell(this.colName(ii)+''+(dd+2)).value = fechahora;
                            worksheet.getCell(this.colName(ii)+''+(dd+2)).numFmt = 'dd/mm/yyyy hh:mm';
                        }else{
                            worksheet.getCell(this.colName(ii)+''+(dd+2)).value = excelData.data[dd][ii].valor;
                        }
                        break;
                    default:
                        worksheet.getCell(this.colName(ii)+''+(dd+2)).value = excelData.data[dd][ii].valor;
                }
                
                if(excelData.data[dd][ii].color && excelData.data[dd][ii].color!='transparent'){
                    worksheet.getCell(this.colName(ii)+''+(dd+2)).fill = {
                        type: 'pattern',
                        pattern:'solid',
                        fgColor:{argb:excelData.data[dd][ii].color.replace("#", "FF")},
                    };
                }
                
                
            }
        }
                
        
        //worksheet.getCell('A1').value = this.colName(1);
        
        
        workbook.xlsx.writeBuffer().then((data) => {
            let blob = new Blob([data], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            });
            fs.saveAs(blob, title + '.xlsx');
        });
        
        
    }
    
    colName(n) {
        var ordA = 'a'.charCodeAt(0);
        var ordZ = 'z'.charCodeAt(0);
        var len = ordZ - ordA + 1;
      
        var s = "";
        while(n >= 0) {
            s = String.fromCharCode(n % len + ordA) + s;
            n = Math.floor(n / len) - 1;
        }
        return s.toUpperCase();
    }
    
    isValidDate(dateString) {
        return !isNaN(Date.parse(dateString));
        //const date = new Date(dateString);
        //return date instanceof Date && !isNaN(date);
    }
}
