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
  // Use input.required to ensure type safety
  readonly article = input.required<Article>();
  
  // Output event for the parent to handle the API call
  readonly toggleFavorite = output<string>();
}