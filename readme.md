<h3>Push Notification</h3>
<p>app create on laravel 5.8. Data base - MongoDB</p>

<p>Must install <a href="https://pecl.php.net/package/zmq">ZMQ</a></p>
<p>Clone repositories</p>
<code>
    git clone https://github.com/Vasylevs/push-notification.git
</code>
<p>Install require</p>
<code>
    composer install
</code>

<p>Start pusher server with console command</p>
<code>
    php artisan socketpush:start
</code>

<p>Start send notifications subscribed users with console command</p>
<code>
    sendnotification:start {data}
</code><br>

<p>Where <em> {data} </ em> is the data to send and is not required. Random number sent by default (0-999)</p>
<p>Change url for socket in file public/js/socket.js lihe: 13 on you`r domain</p>
<p>If you use ssl, change socket connect on <strong>wss:/</strong></p>

<hr>
<h4>Push Notification with without socket</h4>
<p>You mast have ssl sertificate.</p>
<p>In file resources/views/home.blade.php delete connect file js/socket.js and uncomment file js/client.js</p>

<p>Generate public and secret key</p>
<code>
    $ openssl ecparam -genkey -name prime256v1 -out private_key.pem<br>
    $ openssl ec -in private_key.pem -pubout -outform DER|tail -c 65|base64|tr -d '=' |tr '/+' '_-' >> public_key.txt<br>
    $ openssl ec -in private_key.pem -outform DER|tail -c +8|head -c 32|base64|tr -d '=' |tr '/+' '_-' >> private_key.txt<br>
</code>
<p>or use </p>
<code>var_dump(VAPID::createVapidKeys()); // store the keys afterwards</code>

<p>Write this keys in .env file</p>
<code>
    PUBLIC_WEB_PUSH_KEY=<br>
    PRIVATE_WEB_PUSH_KEY=
</code>
<p>Write publick key in file public/js/client.js line: 21 - var clientPublicKey</p>
<p>You can edit auth info in file app/Console/Commands/SendNoSocketNotification.php</p>

<p>Send notifications users who subscribed on notification</p>
<code>
    nosocketnotifications:start {data?}
</code>
<p>More info in https://github.com/web-push-libs/web-push-php</p>
