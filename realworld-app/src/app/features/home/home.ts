import { Component, computed, inject, OnInit, signal } from '@angular/core';
import { HomeService } from '../../core/services/home-service';
import { ArticlePreviewComponent } from '../../shared/ui/article-preview/article-preview';
import { PopularTags } from "../../shared/ui/popular-tags/popular-tags";
import { Article } from '../../core/models/article';
import { ArticleService } from '../../core/services/article-service';
import { finalize, pipe, tap } from 'rxjs';

@Component({
  selector: 'app-home',
  imports: [ArticlePreviewComponent, PopularTags],
  templateUrl: './home.html',
  styleUrl: './home.css',
})
export class Home implements OnInit {
  private readonly articleService = inject(ArticleService);

  articles = signal<Article[]>([]);
  isLoading = signal(false);
  articlesCount = signal(0);
  
  readonly limit = 10;
  readonly currentPage = signal(1);

  readonly totalPages = computed(() => {
    const count = this.articlesCount();
    const pages = Math.ceil(count / this.limit);
    return Array.from({ length: pages }, (_, i) => i + 1);
  });

  ngOnInit() {
    this.fetchData();
  }

  setPage(page: number) {
    this.currentPage.set(page);
    this.fetchData();
  }

  fetchData() {
    const offset = (this.currentPage() - 1) * this.limit;
    this.articleService.getArticles({ limit: this.limit, offset }).
      pipe(
        tap({
          next: (response) => {
            this.articles.set(response.articles);
            this.articlesCount.set(response.articlesCount);
          },
          error: (err) => {
            console.error('Failed to load articles', err);
          }
        }),
        finalize(() => this.isLoading.set(false))
      ).subscribe();
  }

  toggleFavorite(article: Article) {
    // 1. Calculate the new state immediately
    const isFavorited = article.favorited;
    const newCount = isFavorited ? article.favoritesCount - 1 : article.favoritesCount + 1;

    // 2. Optimistically update the Signal (Update the UI instantly)
    // We map over the array, find the matching article, and change only that one
    this.articles.update(currentArticles => 
      currentArticles.map(a => 
        a.slug === article.slug 
          ? { ...a, favorited: !isFavorited, favoritesCount: newCount } 
          : a
      )
    );

    // 3. Send the API request in the background
    const request$ = isFavorited
      ? this.articleService.unfavorite(article.slug)
      : this.articleService.favorite(article.slug);

    request$.subscribe({
      error: (err) => {
        // 4. If it fails, revert the change (Optional but safe)
        this.articles.update(currentArticles => 
          currentArticles.map(a => 
            a.slug === article.slug 
              ? { ...a, favorited: isFavorited, favoritesCount: article.favoritesCount } 
              : a
          )
        );
      }
    });
  }
}
