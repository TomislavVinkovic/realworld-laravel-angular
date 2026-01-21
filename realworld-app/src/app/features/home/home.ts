import { Component, computed, inject, OnInit, signal } from '@angular/core';
import { HomeService } from '../../core/services/home-service';
import { ArticlePreviewComponent } from '../../shared/ui/article-preview/article-preview';
import { PopularTags } from "../../shared/ui/popular-tags/popular-tags";

@Component({
  selector: 'app-home',
  imports: [ArticlePreviewComponent, PopularTags],
  templateUrl: './home.html',
  styleUrl: './home.css',
})
export class Home implements OnInit {
  private readonly homeService = inject(HomeService);

  readonly articles = this.homeService.articles;
  readonly isLoading = this.homeService.isLoading;
  readonly articlesCount = this.homeService.articlesCount;
  
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
    // Calculate offset based on page: (page - 1) * limit
    const offset = (this.currentPage() - 1) * this.limit;
    this.homeService.getArticles({ limit: this.limit, offset });
  }

  onToggleFavorite(slug: string) {
    console.log('Toggle favorite for:', slug);
    // Call service method here
  }
}
