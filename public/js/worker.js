console.log("Service Worker Loaded...");

self.addEventListener('push',  (event) => {
    console.log("Push Recieved...");

    const sendNotification = function(message, tag) {

        const title = "Laravel - MongoDB",
            icon = 'https://cdn4.iconfinder.com/data/icons/logos-3/504/Laravel-512.png';

        message = message || 'Notification massage!';
        tag = tag || 'general';

        return self.registration.showNotification(title, {
            body: message,
            icon: icon,
            tag: tag
        });
    };

    if (event.data) {
        const data = event.data.json();
        event.waitUntil(
            sendNotification(data.message, data.tag)
        );
    }
});

