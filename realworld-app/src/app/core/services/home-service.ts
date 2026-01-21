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

  readonly articles = signal<Article[]>([]);
  readonly articlesCount = signal<number>(0);
  readonly isLoading = signal<boolean>(false);

  getArticles(config: Partial<ArticleListConfig['filters']> = {}) {
    this.isLoading.set(true);

    // Prepare Query Params
    let params = new HttpParams();
    
    // Default limit/offset if not provided
    const limit = config.limit ?? 10;
    const offset = config.offset ?? 0;

    params = params.set('limit', limit);
    params = params.set('offset', offset);

    // Add optional filters
    if (config.tag) params = params.set('tag', config.tag);
    if (config.author) params = params.set('author', config.author);
    if (config.favorited) params = params.set('favorited', config.favorited);

    // Make the request
    // Note: If you have a 'feed' endpoint for followed users, logic would diverge here
    this.api.get<ArticlesResponse>('/articles', params)
      .pipe(
        tap({
          next: (response) => {
            this.articles.set(response.articles);
            this.articlesCount.set(response.articlesCount);
          },
          error: (err) => {
            console.error('Failed to load articles', err);
            // Optionally set an error signal here
          }
        }),
        finalize(() => this.isLoading.set(false))
      )
      .subscribe();
  }
}

interface ArticlesResponse {
  articles: Article[];
  articlesCount: number;
}

export interface ArticleListConfig {
  filters: {
    tag?: string;
    author?: string;
    favorited?: string;
    limit?: number;
    offset?: number;
  };
}