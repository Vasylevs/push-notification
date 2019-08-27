(async function () {
    const user = localStorage.getItem('userId');
    if (user){
        await fetch("/get-user/" + user, {
            method: "GET",
            headers: {
                "content-type": "application/json"
            }
        })
            .then(response => {return response.json()})
            .then(user => {
                if(user.active === 1){
                    document.getElementById('sub').style.display = 'none';
                    document.getElementById('unsub').style.display = 'block';
                }
                console.log(user)
            });
    }
}());

const clientPublicKey = "BPUy63XfVic6IlQACRySRXaVXDVi0IbHxJbPwwOsPaq3AUXgyz05NYF-KFLtulgSv5lRteS6kbujnm5suZrJRqA";

// Check for service worker
if (!("serviceWorker" in navigator)) {
    console.log('serviceWorker not support')
}

async function subscribe() {
    document.getElementById('sub').style.display = 'none';
    document.getElementById('unsub').style.display = 'block';
    // Register Service Worker
    console.log("Registering service worker...");
    const register = await navigator.serviceWorker.register(window.location.origin+"/js/worker.js");
    console.log("Service Worker Registered...");

    // Register Push
    console.log("Registering Push...");
    const subscription = await register.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(clientPublicKey)
    });
    console.log('Push Registered...');
    // Send Push Notification
    console.log("Sending Push...");

    const user = localStorage.getItem('userId');

    await fetch("/create-subscription", {
        method: "POST",
        body: JSON.stringify({subscription,_id:user}),
        headers: {
            "content-type": "application/json"
        },
    })
        .then(response => {return  response.json()})
        .then(data => {
            localStorage.setItem('userId',data._id)
        });

    console.log("Push Sent...");
}

async function unsubscribe() {
    const user = localStorage.getItem('userId');
    await fetch("/deactivate-subscription", {
        method: "POST",
        body: JSON.stringify({_id:user}),
        headers: {
            "content-type": "application/json"
        },
    })
        .then(response => {return  response.json()})
        .then(data => {
            if (data.active === 0) {
                document.getElementById('sub').style.display = 'block';
                document.getElementById('unsub').style.display = 'none';
            }
        });
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
