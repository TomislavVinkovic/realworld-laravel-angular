import { inject, Injectable } from '@angular/core';
import { ApiService } from './api-service';
import { Article } from '../models/article';
import { ArticleComment } from '../models/article-comment';

@Injectable({
  providedIn: 'root',
})
export class CommentService {
  private readonly api = inject(ApiService);

  getComments(article: Article) {
    return this.api.get<CommentsResponse>(`/articles/${article.slug}/comments`);
  }
}

export interface CommentsResponse {
  comments: ArticleComment[];
};