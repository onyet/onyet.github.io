/*
Copyright 2015, 2019, 2020, 2021 Google LLC. All Rights Reserved.
 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at
 http://www.apache.org/licenses/LICENSE-2.0
 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
*/

// Incrementing OFFLINE_VERSION will kick off the install event and force
// previously cached resources to be updated from the network.
// This variable is intentionally declared and unused.
// Add a comment for your linter if you want:
// eslint-disable-next-line no-unused-vars
const OFFLINE_VERSION = 1;
const CACHE_NAME = "onyet-offline";
// Customize this with a different URL if needed.
const OFFLINE_URL = [
    "/assets/js/jquery-3.6.0.min.js",
    "/assets/js/jquery-3.6.0.min.map",
    "/assets/js/particle-ball.js",
    "/assets/js/popper.min.js",
    "/assets/libs/particles.js/particles.js",
    "/assets/libs/bootstrap-5.0.0-beta3/css/bootstrap.min.css",
    "/assets/libs/bootstrap-icons-1.4.1/bootstrap-icons.css",
    "/assets/libs/bootstrap-icons-1.4.1/fonts/bootstrap-icons.woff",
    "/assets/libs/bootstrap-icons-1.4.1/fonts/bootstrap-icons.woff2",
    "/assets/libs/bootstrap-5.0.0-beta3/js/bootstrap.bundle.min.js",
    "/assets/clients/logo-cipta-prima.png",
    "/assets/clients/logo-egrotek.png",
    "/assets/clients/logo-kopkarsentra.png",
    "/assets/clients/logo-lapas-pwt.png",
    "/assets/clients/logo-pelita-anugerah.png",
    "/assets/clients/logo-perjuangan-family.jpg",
    "/assets/clients/logo-telon-doodle.ico",
    "/assets/clients/logo-telkomsat.png",
    "/assets/clients/logo-spm-pwt.png",
    "/assets/clients/logo-sdvip-nu-kemiri.png",
    "/assets/clients/logo-smp-n1-kupang.png",
    "/assets/clients/logo-smkppn-bima.png",
    "/assets/clients/logo-smam-gempol.png",
    "/assets/styles.css",
    "/assets/favicon.ico",
    "/assets/logomini-black.svg",
    "/assets/logomini.png",
    "/assets/logomini.svg",
    "/assets/onyetapps-black.png",
    "/assets/res_512.png",
    "/assets/banana.svg",
    "/assets/g1016.png",
    "/assets/g896.png",
    "/assets/g935.png",
    "/assets/github-enterprise-garden-1.h264.mp4",
    "/assets/github-enterprise-garden-2.h264.mp4",
    "/assets/particlesjs-config.json",
    "/assets/mefo3.png",
    "/assets/mefo2.png",
    "/assets/mefo1.png",
    "/assets/roboc-github.png",
    "/assets/fly-land-github.png",
    "/assets/res/144/icons.png",
    "/assets/res/192/icons.png",
    "/assets/res/48/icons.png",
    "/assets/res/72/icons.png",
    "/assets/res/96/icons.png",
    "/index.html"
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        (async () => {
            const cache = await caches.open(CACHE_NAME);
            // Setting {cache: 'reload'} in the new request will ensure that the
            // response isn't fulfilled from the HTTP cache; i.e., it will be from
            // the network.
            await cache.addAll(OFFLINE_URL);
        })()
    );
    // Force the waiting service worker to become the active service worker.
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        (async () => {
            // Enable navigation preload if it's supported.
            // See https://developers.google.com/web/updates/2017/02/navigation-preload
            if ("navigationPreload" in self.registration) {
                await self.registration.navigationPreload.enable();
            }
        })()
    );

    // Tell the active service worker to take control of the page immediately.
    self.clients.claim();
});

// self.addEventListener("fetch", (event) => {
//     // We only want to call event.respondWith() if this is a navigation request
//     // for an HTML page.
//     if (event.request.mode === "navigate") {
//         event.respondWith(
//             (async () => {
//                 try {
//                     // First, try to use the navigation preload response if it's supported.
//                     const preloadResponse = await event.preloadResponse;
//                     if (preloadResponse) {
//                         return preloadResponse;
//                     }

//                     // Always try the network first.
//                     const networkResponse = await fetch(event.request);
//                     return networkResponse;
//                 } catch (error) {
//                     // catch is only triggered if an exception is thrown, which is likely
//                     // due to a network error.
//                     // If fetch() returns a valid HTTP response with a response code in
//                     // the 4xx or 5xx range, the catch() will NOT be called.
//                     console.log("Fetch failed; returning offline page instead.", error);

//                     const cache = await caches.open(CACHE_NAME);
//                     const cachedResponse = await cache.match("/index.html");
//                     return cachedResponse;
//                 }
//             })()
//         );
//     }

//     // If our if() condition is false, then this fetch handler won't intercept the
//     // request. If there are any other fetch handlers registered, they will get a
//     // chance to call event.respondWith(). If no fetch handlers call
//     // event.respondWith(), the request will be handled by the browser as if there
//     // were no service worker involvement.
// });