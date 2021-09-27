
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', name: 'connexion', component: () => import('pages/Index.vue') },
      { path: '/inscription', name: 'inscription', component: () => import('pages/Inscription.vue') }
    ]
  },

  {
    path: '',
    component: () => import('layouts/AdminLayout.vue'),
    children: [
      { path: '/todolist', name: 'todolist', component: () => import('pages/ToDoList.vue') },
      { path: '/historique', name: 'historique', component: () => import('pages/Historique.vue') }
    ]
  },


  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
