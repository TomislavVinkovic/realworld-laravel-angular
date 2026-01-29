import { Component, inject, input, OnInit, signal } from '@angular/core';
import { Article } from '../../../core/models/article';
import { CommentService } from '../../../core/services/comment-service';
import { AuthService } from '../../../core/auth/auth-service';
import { ArticleComment } from '../../../core/models/article-comment';
import { RouterLink } from '@angular/router';
import { DatePipe } from '@angular/common';

@Component({
  selector: 'app-comment-list',
  imports: [RouterLink, DatePipe],
  templateUrl: './comment-list.html',
  styleUrl: './comment-list.css',
})
export class CommentList implements OnInit {
  readonly article = input.required<Article>();

  private readonly authService = inject(AuthService);
  private readonly commentService = inject(CommentService);

  readonly user = this.authService.currentUser;

  comments = signal<ArticleComment[]>([]);

  ngOnInit(): void {
    this.fetchData();
  }

  fetchData(): void {
    this.commentService.getComments(this.article()).subscribe({
      next: (response) => {
        console.log(response.comments)
        this.comments.set(response.comments);
      },
      error: (err: any) => {
        console.log(err);
      }
    });
  }
}