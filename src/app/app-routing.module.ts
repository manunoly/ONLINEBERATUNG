import { InicioComponent } from './inicio/inicio.component';
import { UploadComponent } from './upload/upload.component';
import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

const routes: Routes = [
  { path: 'upload', component: UploadComponent },
  { path: 'inicio', component: InicioComponent },
  {
    path: '',
    redirectTo: '/inicio',
    pathMatch: 'full'
  },
  { path: '**', component: InicioComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
