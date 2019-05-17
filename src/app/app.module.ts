import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';
import { FileDropModule } from 'ngx-file-drop';


import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { SubidaComponent } from './componentes/subida/subida.component';

@NgModule({
  declarations: [
    AppComponent,
    SubidaComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FileDropModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
