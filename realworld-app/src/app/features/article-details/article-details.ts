import { Component, inject, input, OnInit, signal } from '@angular/core';
import { ArticleService } from '../../core/services/article-service';
import { Article } from '../../core/models/article';
import { DatePipe, NgOptimizedImage } from '@angular/common';
import { RouterLink } from '@angular/router';
import { AuthService } from '../../core/auth/auth-service';
import { CommentList } from "../../shared/ui/comment-list/comment-list";

@Component({
  selector: 'app-article-details',
  imports: [DatePipe, RouterLink, CommentList],
  templateUrl: './article-details.html',
  styleUrl: './article-details.css',
})
export class ArticleDetails implements OnInit {
  readonly slug = input.required<string>();
  private readonly articleService = inject(ArticleService);
  private readonly authService = inject(AuthService);

  readonly currentUser = this.authService.currentUser;

  isLoading = signal(true);;
  article: Article|null = null;

  ngOnInit(): void {
    this.articleService.getArticle(this.slug()).subscribe({
      next: (article: Article) => {
        this.article = article;
        this.isLoading.set(false);
      },
      error: (err: any) => {
        console.log(err);
        this.isLoading.set(false);
      }
    });
  }
}
