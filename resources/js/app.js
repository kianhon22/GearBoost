import './bootstrap';
import { createApp } from 'vue';

import ProductCard from './components/ProductCard.vue';
import ProductCatalogue from './components/ProductCatalogue.vue';
import ProductDetail from './components/ProductDetail.vue';
import SearchBar from './components/SearchBar.vue';
import CartItem from './components/CartItem.vue';
import StatusBadge from './components/StatusBadge.vue';
import LoadingButton from './components/LoadingButton.vue';
import EmptyState from './components/EmptyState.vue';
import AuthForm from './components/AuthForm.vue';
import ConfirmDialog from './components/ConfirmDialog.vue';
import Notification from './components/Notification.vue';

const app = createApp({});

app.component('product-card', ProductCard);
app.component('product-catalogue', ProductCatalogue);
app.component('product-detail', ProductDetail);
app.component('search-bar', SearchBar);
app.component('cart-item', CartItem);
app.component('status-badge', StatusBadge);
app.component('loading-button', LoadingButton);
app.component('empty-state', EmptyState);
app.component('auth-form', AuthForm);
app.component('confirm-dialog', ConfirmDialog);
app.component('notification', Notification);

app.mount('#app');
