import { Component, inject, input, OnInit, signal } from '@angular/core';
import { ArticleService } from '../../core/services/article-service';
import { Article } from '../../core/models/article';
import { DatePipe } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
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
  
  private readonly router = inject(Router);

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

  editArticle() {
    // Navigate to the editor with the slug parameter
    this.router.navigate(['/editor', this.article!.slug]);
  }

  deleteArticle() {
    const confirmed = window.confirm('Are you sure you want to delete this article?');

    if (confirmed) {
      this.articleService.deleteArticle(this.article!.slug!).subscribe({
        next: () => {
          this.router.navigate(['/']);
        },
        error: (err) => {
          console.error('Failed to delete', err);
          alert('Could not delete article.');
        }
      });
    }
  }
}
