import { Component, OnInit } from '@angular/core';
import { MatGridList } from '@angular/material';
import { SFotosService } from 'src/app/servicios/s-fotos.service';
@Component({
  selector: 'app-listado',
  templateUrl: './listado.component.html',
  styleUrls: ['./listado.component.css']
})
export class ListadoComponent implements OnInit {
  public listadoFotos: string[];
  constructor(private uploadService: SFotosService) { }

  ngOnInit() {
    this.listadoFotos = null;
    this.ObtenerFotos();
  }
  public ObtenerFotos(): void {
    this.uploadService.ObtenerFotos().subscribe((data: any) => {
      this.listadoFotos = data;
      console.log(data);
    });
  }
}
