import { Component, OnInit } from '@angular/core';
import { UploadEvent, UploadFile, FileSystemFileEntry, FileSystemDirectoryEntry, FileDropModule } from 'ngx-file-drop';
import {HttpHeaders, HttpClient} from '@angular/common/http';

@Component({
  selector: 'app-subida',
  templateUrl: './subida.component.html',
  styleUrls: ['./subida.component.css']
})
export class SubidaComponent implements OnInit {
  public files: UploadFile[] = [];

  constructor(private http: HttpClient) { }

  ngOnInit() {
  }

  public dropped(event: UploadEvent) {
    this.files = event.files;
    for (const droppedFile of event.files) {

      // Is it a file?
      if (droppedFile.fileEntry.isFile) {
        const fileEntry = droppedFile.fileEntry as FileSystemFileEntry;
        fileEntry.file((file: File) => {

          // Here you can access the real file
          console.log(droppedFile.relativePath, file);


          // You could upload it like this:
          const formData = new FormData();
          formData.append('foto', file, droppedFile.relativePath);
          formData.append('dato', 'datos');
          //formData.append();
          this.http.post('http://localhost/2ppNEW/media/altaFoto', formData, {})
          .subscribe(data => {
            // Sanitized logo returned from backend
          });


        });
      } else {
        // It was a directory (empty directories are added, otherwise only files)
        const fileEntry = droppedFile.fileEntry as FileSystemDirectoryEntry;
        console.log(droppedFile.relativePath, fileEntry);
      }
    }
  }

  public fileOver(event){
    console.log(event);
  }

  public fileLeave(event){
    console.log(event);
  }
}
