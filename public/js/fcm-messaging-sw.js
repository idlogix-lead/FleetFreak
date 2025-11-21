importScripts('https://www.gstatic.com/firebasejs/10.11.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyCzqd535JYGW_RXtv849TeDLA_MT4Q8KOc",
            authDomain: "zaroon-3f2ae.firebaseapp.com",
            projectId: "zaroon-3f2ae",
            storageBucket: "zaroon-3f2ae.appspot.com",
            messagingSenderId: "674096357358",
            appId: "1:674096357358:web:5a959f3e4514f31564f8ff",
            measurementId: "G-NSE3DC50Z9"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    // Customize notification here
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});
