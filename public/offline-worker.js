/* Any copyright is dedicated to the Public Domain.
 * http://creativecommons.org/publicdomain/zero/1.0/ */


(function (self) {
  'use strict';

  // On install, cache resources and skip waiting so the worker won't
  // wait for clients to be closed before becoming active.
  self.addEventListener('install', event =>
    event.waitUntil(
      oghliner.cacheResources()
      .then(() => self.skipWaiting())
    )
  );

  // On activation, delete old caches and start controlling the clients
  // without waiting for them to reload.
  self.addEventListener('activate', event =>
    event.waitUntil(
      oghliner.clearOtherCaches()
      .then(() => self.clients.claim())
    )
  );

  // Retrieves the request following oghliner strategy.
  self.addEventListener('fetch', event => {
    if (event.request.method === 'GET') {
      event.respondWith(oghliner.get(event.request));
    } else {
      event.respondWith(self.fetch(event.request));
    }
  });

  var oghliner = self.oghliner = {

    // This is the unique prefix for all the caches controlled by this worker.
    CACHE_PREFIX: 'offline-cache:phpminds/website:' + (self.registration ? self.registration.scope : '') + ':',

    // This is the unique name for the cache controlled by this version of the worker.
    get CACHE_NAME() {
      return this.CACHE_PREFIX + '7c9b403fd489a19088d5bcc0cacaf3f7215aab70';
    },

    // This is a list of resources that will be cached.
    RESOURCES: [
      './android-chrome-144x144.png', // 692945ca200dae0bcc7286cffea30ec93424e869
      './android-chrome-192x192.png', // 3b9b0e3a8fbf6ad4be5869cd3414979d3164a1d1
      './android-chrome-36x36.png', // 24f22f54ebb9f5aed7a90e868180501637395dff
      './android-chrome-48x48.png', // 65d4e1ab518e2a60bc48c3bdab5b9d164f8cd78e
      './android-chrome-72x72.png', // 9955614306c691ed04acb96a33d8ab531b5a4e91
      './android-chrome-96x96.png', // eb408e781ecda0780daab4f4998b1676139437a8
      './apple-touch-icon-114x114.png', // 58586a85bc52c3dc864f12be0409bc8628d53044
      './apple-touch-icon-120x120.png', // 16ef7bb801e70985a03fe4cdd28e1a8a45e2780b
      './apple-touch-icon-144x144.png', // dd2f5e01225734ddae49ab8b8c708afb94cecf8d
      './apple-touch-icon-152x152.png', // 3cf4f350a4facc076b65001a5d69cab0c66bd06a
      './apple-touch-icon-180x180.png', // 7d9f15098bab7c0ad91911007ccbad6ed08c46f4
      './apple-touch-icon-57x57.png', // d5ace6ce7f2c2f3b20cef92c58ad0c2f6eedd1d1
      './apple-touch-icon-60x60.png', // 5682354384aea13e63b36c2b4b4351636421caad
      './apple-touch-icon-72x72.png', // 60dae69ed9a31e722fe37ab774f3932cc10620d2
      './apple-touch-icon-76x76.png', // 4426e0103dadc6fbe4951afd925fffc484cf8fc6
      './apple-touch-icon-precomposed.png', // b095a5fec1a45f4b5208a0a2b299ed299a30fffb
      './apple-touch-icon.png', // 7d9f15098bab7c0ad91911007ccbad6ed08c46f4
      './browserconfig.xml', // b1a8ba03d10f7034ab73e083c5584db22ef7dfd3
      './favicon-16x16.png', // b39db1807135dc33fbaecc9b245f7ad9f9fbf67e
      './favicon-194x194.png', // 5ee783877ccb7642b811ae7817fa6915ad04f87b
      './favicon-32x32.png', // 1d03e66dd53b30c90bea78f7e0874e17da045278
      './favicon-96x96.png', // 1867736dd1531adaed97b433365d93fe6733e12e
      './favicon.ico', // a909d3845bcae18517877d36b92bf45435723e13
      './index.php', // b33317590cb848b0fda9b31509df4f5801bbd277
      './manifest.json', // 8730768610bf0f33c6b2c84d4eec90a37bbf9d27
      './mstile-144x144.png', // 550b372323189a874da8bbfb6d77dee4cd614147
      './mstile-150x150.png', // e0aeaca96d714794f4bf82bee6edec7d653de3bb
      './mstile-310x150.png', // cdfe71f056d527c1a9a9249ccdbb9353860240c6
      './mstile-310x310.png', // 99d95ef71235f7420481f7d060019a780066923c
      './mstile-70x70.png', // 968946ae7530d968f7186acd7cc3d6da354c3c95
      './safari-pinned-tab.svg', // bfe7b205cdc4606f2799de1edcd2015abf40457a

    ],

    // Adds the resources to the cache controlled by this worker.
    cacheResources: function () {
      var now = Date.now();
      var baseUrl = self.location;
      return this.prepareCache()
      .then(cache => Promise.all(this.RESOURCES.map(resource => {
        // Bust the request to get a fresh response
        var url = new URL(resource, baseUrl);
        var bustParameter = (url.search ? '&' : '') + '__bust=' + now;
        var bustedUrl = new URL(url.toString());
        bustedUrl.search += bustParameter;

        // But cache the response for the original request
        var requestConfig = { credentials: 'same-origin' };
        var originalRequest = new Request(url.toString(), requestConfig);
        var bustedRequest = new Request(bustedUrl.toString(), requestConfig);
        return fetch(bustedRequest)
        .then(response => {
          if (response.ok) {
            return cache.put(originalRequest, response);
          }
          console.error('Error fetching ' + url + ', status was ' + response.status);
        });
      })));
    },

    // Remove the offline caches not controlled by this worker.
    clearOtherCaches: function () {
      var outOfDate = cacheName => cacheName.startsWith(this.CACHE_PREFIX) && cacheName !== this.CACHE_NAME;

      return self.caches.keys()
      .then(cacheNames => Promise.all(
        cacheNames
        .filter(outOfDate)
        .map(cacheName => self.caches.delete(cacheName))
      ));
    },

    // Get a response from the current offline cache or from the network.
    get: function (request) {
      return this.openCache()
      .then(cache => cache.match(() => this.extendToIndex(request)))
      .then(response => {
        if (response) {
          return response;
        }
        return self.fetch(request);
      });
    },

    // Make requests to directories become requests to index.html
    extendToIndex: function (request) {
      var url = new URL(request.url, self.location);
      var path = url.pathname;
      if (path[path.length - 1] !== '/') {
        return request;
      }
      url.pathname += 'index.html';
      return new Request(url.toString(), request);
    },

    // Prepare the cache for installation, deleting it before if it already exists.
    prepareCache: function () {
      return self.caches.delete(this.CACHE_NAME)
      .then(() => this.openCache());
    },

    // Open and cache the offline cache promise to improve the performance when
    // serving from the offline-cache.
    openCache: function () {
      if (!this._cache) {
        this._cache = self.caches.open(this.CACHE_NAME);
      }
      return this._cache;
    }

  };
}(self));
