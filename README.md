# SECG4-Project-54314-56172

SECG4-PROJECT
54314-56172


Notion de sécurité 

l'inscription et l'authentification à été fait avec laravel.

il faut savoir que lors de l'inscription le mot de passe et stocker dans la base de donnée de manière sécurisé.
En effet le mot de passe et hasher 50 fois avec un salt par défaut en utilisant Hash::make() cette méthode utilise l'algorithme de hachage bcrypt , couramment utilisé dans Laravel. cela est hasher de façon à ce qu'une personne tentant d'accéder a la base de donnée ne pourra rien lire.
la clé utiliser pour cela est celle ce trouvant dans .env dans APP_KEY (je supose qu'elle est fournis en base64).
APP_KEY=base64:2bzK8JctjNGNRoHpJPY0NT5gg17KqZ2bDj00nl0Hys0=

on peut générer une nouvelle clé en fessant php artisan key:generate


bcyrpt permet en autre de réduire le nombre de mot de passse par seconde qu'un attaquant pourrait hacher lors de l'élaboration d'une attaque par dictionnaire



pour stocker les messages de manière sécuriser laravel propose une méthode de cryptage et de décryptage qui utilise OpenSSL pour fournir un cryptage AES-256.


Pour le mot de passe je demande une lettre majuscule, une miniscule, un nombre et un caractère spéciale cela permet d'éviter entre autre l'attaque par dictionnaires.


pour la connexion et pour le register ajout d'un reCAPTCHA de Google pour éviter tous les points de terminaison pouvant être exploités à l'aide de techniques de force brute. CAPTCHA arrêtera la plupart des attaques automatisées. 

Limitation de taux de connexion après un certain nombre d'essaye l'utilisateur va devoir attendre un certain temps

source = https://www.freecodecamp.org/news/protect-your-laravel-app-against-the-owasp-top-10-security-risks/

Désactivation du débogage sur le serveurs de production. Même sur les serveurs intermédiaires, le débogage peut révéler des informations sensibles sur le serveur en affichant toutes vos variables d'environnement. Utilisez l' option de configuration debug_hide de l'application dans Laravel pour éviter cela.

Méfiez-vous de désérialiser quoi que ce soit provenant de sources non fiables. Cela inclut les cookies que votre application pourrait créer. Un utilisateur malveillant peut modifier ce cookie dans son navigateur et l'utiliser comme vecteur d'attaque contre votre application.

Par défaut, tous les cookies créés par Laravel sont cryptés et signés. Cela signifie qu'ils seront invalides si un client les falsifie.

Pour l'ajout de notre certificat , nous allons utilser un serveur local. Il est nécessaire d'installer Laragon
Lorsque laragon est lancé, il faut activer le SSL se trouvant dans les préferences -> services & ports et cocher la création automatique d'hotes
Ajouter le projet souhaité dans le dossier racine "www".
Clic droit -> appache -> SSL -> ajouter laragon au certificat de confiance ,démarrer le serveur et vous pouvez ouvrir votre site 
ex : "https://{nomduprojet}.test"

1. Do I properly ensure confidentiality?
    • Are sensitive data transmitted and stored securely?
    <strong>pour l'instant les messages sont transmis par un simple post</strong>
    • Are sensitive requests sent to the server transmitted securely?
    <strong>?</strong>
    • Does a system administrator have access to the sensible data of some arbitrary user?
    <strong>Pour l'instant oui vue que la clé utilisé pour crypter les messages ce trouve dans le .env dans le server, par contre pour le mot de passe c'est impossible car il utilise une fonction de hachage bcrypt qui rend la réversibilité impossible</strong>

2. Do I properly ensure integrity of stored data?
    <strong>Oui le mot de passe et haché 10 fois avec la fonction de hachage bcrypt qui rajoute un salt par défaut</strong>

3. Do I properly ensure non-repudiation?
• Do I use signature, certificates, a proper authority?
    <strong>je ne sais pas si le système d'authenfication par défaut de laravel le fait</strong>
    <strong>On utilise un serveur qui nous permettra d'ajouter un certificat et lancer la connexion en https</strong>
    

4. Do I use a proper and strong authentication scheme?
<strong>on utilise le système d'authenfication de laravel qui est framework souvent mise à jours et dont la sécurité évolue rapidement, il utilise un schéma d'authenfication par email mot de passe ou par token si on fait un rember me</strong>

5. Do my security features rely on secrecy, beyond credentials?
<strong> oui les message sont crypter avec d'être stocké mais le client ne crypt pas le message ici</strong>

6. Am I vulnerable to injection?
• URL, SQL, Javascript and dedicated parser injections
<strong>Aucun risque d'injection SQL car toutes les requêtes sont préparées
automatiquement lors de l’utilisation des méthodes de l’interface DB.</strong> 
<strong>Aucun risque de faille XSS puisque les utilisateurs ne peuvent pas entrer de code et 
que tous les codes sont échappés automatiquement lors de l’affichage blade dispose de {{!!!!}} pour contrer cela</strong>

7. Am I vulnerable to data remanence attacks?
<strong>je ne pense pas, pas sur a check</strong>

8. Am I vulnerable to replay attacks?
<strong></strong>

9. Am I vulnerable to fraudulent request forgery?
<strong>non blade propose une fonctionnalité qui est le @CSRF a placer dans chaque formulaire pour éviter ce genre d'attaque, cela fonctionne on rajoutant un token pour chaque session utilisateur active, ce token et utilisé pour vérifier que l'utilisateur authentifié est la personne qui fait réellement les requêtes à l'application</strong>

10. Am I monitoring enough user activity so that I can immediately detect malicious intents,
or analyse an attack a posteriori?
• Do I simply reject invalid entries, or do I analyse them?
<strong>Je rejecte simplement les entrée invalide sans les analyser(je veux dire par la que je ne reste pas devant les log a surveille chaque action) bien que laravel enregsitre chaque action dans le fichier /storage/log et sont configurables dans le serveur de ce fait on peut avoir une trace de ce qui ce passe </strong>

11. Am I using components with know vulnerabilities?
<strong>Pas a ma connaissance a part pour le fait qu'on transfère des les messages client vers server sans les crypté</strong>

12. Is my system updated?
<strong>Etant donné le choix d’un framework récent dont les mises à jour sont effectuées et 
récentes, toutes les failles découvertes à l’heure actuelle sont corrigées. Il est 
nécessaire de se tenir à jour des avancées de Laravel afin d’être toujours à jour d’un 
point de vue sécurité</strong>

13. Is my access control broken (cf. OWASP 10)?
• Do I use indirect references to resource or functions?
<strong>Etant donné qu'on utilise POST pour éviter la modification de l'url et ainsi ne pas permettre à n'importe qui de modifier l'url et récuperer des données confidentielles (par contre je sais pas pour les resource ou fonctions indirect)</strong>

14. Is my authentication broken (cf. OWASP 10)?
<strong>Nous implémentons la limitation du débit et reCAPTCHA sur tous les formulaires de connexion, d'inscription et de mot de passe oublié. Cela empêche les attaques par force brute où un acteur malveillant pourrait utiliser des moyens automatisés pour essayer des milliers de combinaisons de mots de passe pour accéder à votre compte</strong>

15. Are my general security features misconfigured (cf. OWASP 10)?
<strong>Nous désactivons toutes les sorties de débogage qui peuvent exposer des détails sur votre système et même accéder à vos valeurs de configuration sensibles. Laravel a des dispositions qui rendent cela aussi simple qu'un paramètre de configuration.</strong>
