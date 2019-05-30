import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { FileDropModule } from 'ngx-file-drop';

import { MatGridListModule, } from '@angular/material';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { SubidaComponent } from './componentes/subida/subida.component';
import { ListadoComponent } from './componentes/listado/listado.component';
import { SFotosService } from './servicios/s-fotos.service';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

@NgModule({
  declarations: [
    AppComponent,
    SubidaComponent,
    ListadoComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FileDropModule,
    MatGridListModule,
    BrowserAnimationsModule //angular material
  ],
  providers: [SFotosService],
  bootstrap: [AppComponent]
})
export class AppModule { }
