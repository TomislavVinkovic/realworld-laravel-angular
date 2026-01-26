import { Routes } from '@angular/router';

export const routes: Routes = [
    {
    path: '',
    loadComponent: () => import('./features/home/home')
      .then(m => m.Home),
    title: 'Home',
    runGuardsAndResolvers: 'always'
  },
  {
    path: 'login',
    loadComponent: () => import('./features/auth/login/login')
      .then(m => m.Login),
    title: 'Sign in'
  },
  {
    path: 'register',
    loadComponent: () => import('./features/auth/register/register')
      .then(m => m.Register),
    title: 'Sign up'
  },
  {
    path: 'article/:slug', 
    loadComponent: () => import('./features/article-details/article-details').then(m => m.ArticleDetails),
    title: 'Article Details'
  },
];