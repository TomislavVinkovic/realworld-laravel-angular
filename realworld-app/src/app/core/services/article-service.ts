import { inject, Injectable } from '@angular/core';
import { ApiService } from './api-service';
import { map, tap } from 'rxjs';
import { Article } from '../models/article';
import { HttpParams } from '@angular/common/http';

@Injectable({
  providedIn: 'root',
})
export class ArticleService {
  private readonly api = inject(ApiService);

  getArticles(config: Partial<ArticleListConfig['filters']> = {}) {
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

    return this.api.get<ArticlesResponse>('/articles', params);
  }

  favorite(slug: string) {
    return this.api.post<ArticleResponse>(`/articles/${slug}/favorite`);
  }

  unfavorite(slug: string) {
    return this.api.delete<ArticleResponse>(`/articles/${slug}/unfavorite`);
  }

  getArticle(slug: string) {
    return this.api.get<ArticleResponse>(`/articles/${slug}`)
      .pipe(
        map(article => article.article)
      );
  }
}

interface ArticleResponse {
  article: Article
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