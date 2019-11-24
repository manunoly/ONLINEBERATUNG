import { HttpClient, HttpHeaders } from '@angular/common/http';

import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  headers = new HttpHeaders({ 'Content-Type': 'application/json; charset=utf-8' });
  public url = 'backend/';

  constructor(private http: HttpClient) { }

  /**
   * data to send to the server
 * @param data
 */
  public uploadFile(data) {
    let uploadURL = `${this.url}upload.php`;
    return this.http.post<any>(uploadURL, data);
  }

  /**
   * data to send to the server
 * @param data
 */
  public processFile(data) {
    let uploadURL = `${this.url}process.php`;
    return this.http.post<any>(uploadURL, data);
  }

}
