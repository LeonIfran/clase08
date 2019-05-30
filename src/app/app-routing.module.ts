import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SubidaComponent } from './componentes/subida/subida.component';
import { ListadoComponent } from './componentes/listado/listado.component';

const routes: Routes = [  
{ path: 'subida', component: SubidaComponent },
{ path: 'listado', component: ListadoComponent },
{ path: '', redirectTo: '/subida', pathMatch: 'full' },
{ path: '**', redirectTo: '/subida', pathMatch: 'full' },];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
