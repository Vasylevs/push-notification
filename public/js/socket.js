class Push{
    constructor(){
        this.socket = null;
        this.sub = false;
    }

    subscribe() {

        Notification.requestPermission().then(function(result) {
            console.log(result);
        });

        this.socket = new WebSocket('ws://localhost:8080',["soap", "wamp"]);

        this.socket.onopen = () => {
            console.log("Connect open.");
            if (!this.sub){
                this.socket.send(JSON.stringify([5,"randomNotification"]))
                this.sub = true;
            }
        };

        this.socket.onclose = function(event) {
            console.log('Code: ' + event.code + ' detail: ' + event.reason)
        };

        this.socket.onmessage = event => {
            const data = JSON.parse(event.data);
            if (data[0] === 8){
                this.notifyMe(data[2].data)
            }
        };

        this.socket.onerror = function(error) {
            console.log(error.message)
        };
    }

    unsubscribe() {
        this.socket.close()
    }

    notifyMe(data) {
        // Let's check if the browser supports notifications
        if (!("Notification" in window)) {
            alert("This browser does not support system notifications");
        }

        // Let's check whether notification permissions have already been granted
        else if (Notification.permission === "granted") {
            var notification = new Notification(data);
        }

        // Otherwise, we need to ask the user for permission
        else if (Notification.permission !== 'denied') {
            Notification.requestPermission(function (permission) {
                if (permission === "granted") {
                    var notification = new Notification(data);
                }
            });
        }
    }
}

const socket = new Push();

function subscribe(){
    document.getElementById('sub').style.display = 'none';
    document.getElementById('unsub').style.display = 'block';
    socket.subscribe()
}

function unsubscribe() {
    document.getElementById('sub').style.display = 'block';
    document.getElementById('unsub').style.display = 'none';
    socket.unsubscribe()
}
