# SECG4-Project-54314-56172

SECG4-PROJECT
56172 Youssef El Majdoul
54314 Younes Oudahya

<h1>Build Project: Command line</h1>
<b>chmod +x script.sh<br>
./script.sh<br>
cd secureChat<br>
composer install<br></b>

<h1> Launch project<h1>
<h3>With server on laragon</h3>
Install laragon<br>
Configure preferences :General -> activate Auto-create virtual hosts, Services & Ports -> activate SSL <br>
Add project to root of laragon -> "www"<br>
DataBase : create a new session -> attach the DB from the project<br>
Start the server<br>
Add the certificate trust -> Right click -> Appache -> ssl -> Add laragon...<br>
Open the website -> example <b>https://{nameProject}.test</b><br>

<h3>Without server</h3>
php artisan migrate refresh<br>
php artisan server<br>
get the URL and open it on website<br>
example : <b>http://127.0.0.1:8000</b>

