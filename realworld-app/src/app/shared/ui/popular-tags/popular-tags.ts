import { Component, inject, OnInit } from '@angular/core';
import { TagService } from '../../../core/services/tag-service';

@Component({
  selector: 'app-popular-tags',
  imports: [],
  templateUrl: './popular-tags.html',
  styleUrl: './popular-tags.css',
})
export class PopularTags implements OnInit{
  private readonly tagService = inject(TagService);
  readonly tags = this.tagService.tags;
  
  ngOnInit(): void {
    this.tagService.getTags();
  }
}