import { Component, input, output } from '@angular/core';
import { Article } from '../../../core/models/article';
import { RouterLink } from '@angular/router';
import { DatePipe, NgOptimizedImage } from '@angular/common';

@Component({
  selector: 'app-article-preview',
  imports: [RouterLink, DatePipe, NgOptimizedImage],
  templateUrl: './article-preview.html',
  styleUrl: './article-preview.css',
})
export class ArticlePreviewComponent {
  readonly article = input.required<Article>();
  readonly toggleFavorite = output<Article>();
}