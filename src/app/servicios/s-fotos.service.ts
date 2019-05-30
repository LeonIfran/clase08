import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
@Injectable({
  providedIn: 'root'
})
export class SFotosService {

  constructor(private http: HttpClient) { }

  public SubirArchivo(archivo: File) {
    const fd: FormData = new FormData();
    fd.append('fotos', archivo, archivo.name);

    return this.http.post('http://localhost/lab_IV/clase06/src/backend/', fd);
  }

  public ObtenerFotos() {
    return this.http.get('http://localhost/2ppNEW/media/traerTodas');
  }
}
