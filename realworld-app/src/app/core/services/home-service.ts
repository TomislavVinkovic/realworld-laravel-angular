import { inject, Injectable, signal } from '@angular/core';
import { Article } from '../models/article';
import { ApiService } from './api-service';
import { finalize, tap } from 'rxjs';
import { HttpParams } from '@angular/common/http';

@Injectable({
  providedIn: 'root',
})
export class HomeService {
  private readonly api = inject(ApiService);

  
}