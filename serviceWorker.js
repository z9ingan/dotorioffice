const CACHE_NAME = 'my-cache-v1';
const CACHED_URLS = [
	'/',
	'/index.html',
	'/styles.css',
	'/script.js',
	'https://fonts.googleapis.com/css?family=Noto+Sans+KR&display=swap', // Google Fonts
	'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', // Bootstrap
	'https://cdn-uicons.flaticon.com/2.0.0/uicons-regular-straight/css/uicons-regular-straight.css', // Uicons Font
	'https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.css', // SweetAlert2 CSS
	'https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.all.min.js' // SweetAlert2 JS
	// Add other URLs you want to cache
];

self.addEventListener('install', (event) => {
	event.waitUntil(
		caches.open(CACHE_NAME).then((cache) => {
			return cache.addAll(CACHED_URLS);
		})
	);
});

self.addEventListener('fetch', (event) => {
	event.respondWith(
		caches.match(event.request).then((response) => {
			return response || fetch(event.request);
		})
	);
});

self.addEventListener('push', (event) => {
	const pushData = event.data.json();

	const options = {
		body: pushData.body,
		icon: '/icon.png',
		badge: '/badge.png',
		data: {
			url: pushData.url
		}
	};

	event.waitUntil(
		self.registration.showNotification(pushData.title, options)
	);
});

self.addEventListener('notificationclick', (event) => {
	const clickedNotification = event.notification;
	clickedNotification.close();

	const urlToOpen = event.notification.data.url || '/';

	event.waitUntil(
		clients.openWindow(urlToOpen)
	);
});