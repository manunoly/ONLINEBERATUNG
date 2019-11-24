import { ApiService } from './../service/api.service';
import { Component, OnInit } from '@angular/core';

import { FormBuilder, FormGroup } from '@angular/forms';

@Component({
  selector: 'app-upload',
  templateUrl: './upload.component.html',
  styleUrls: ['./upload.component.sass']
})
export class UploadComponent implements OnInit {

  form: FormGroup;
  uploadResponse;
  fileProcess;
  topic;
  dataShow;

  constructor(private formBuilder: FormBuilder, private api: ApiService) { }

  ngOnInit() {
    this.form = this.formBuilder.group({
      conferences: ['']
    });
  }

  /**
   * process the upload file
   */
  async processFile() {
    const formData = new FormData();
    formData.append('filename', this.uploadResponse.url);

    this.api.processFile(formData).subscribe((res) => {
      this.fileProcess = res;
      this.dataShow = JSON.parse(res['data']);
      console.log(this.dataShow);
    },
      (err) => {
        console.log(err);
        this.uploadResponse = {
          message: "Unexpected Error, please report!",
          status: "error"
        }
      }
    )
  }

  /**
   * assign the upload file to be send
   * @event fire with file
   */
  onFileSelect(event) {
    if (event.target.files.length > 0) {
      const file = event.target.files[0];
      this.form.get('conferences').setValue(file);
      this.uploadResponse = undefined;
      this.fileProcess = undefined;
      this.dataShow = "";
    }
  }

  /**
 * submit the file to the server
 */
  onSubmit() {
    const formData = new FormData();
    formData.append('conferences', this.form.get('conferences').value);

    this.api.uploadFile(formData).subscribe(
      (res) => {
        console.log(res);
        this.uploadResponse = res;
      },
      (err) => {
        console.log(err);
        this.uploadResponse = {
          message: "Unexpected Error, please report!",
          status: "error"
        }
      }
    );
  }
}
