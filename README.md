# SECG4-Project-54314-56172

SECG4-PROJECT
54314-56172


Notion de sécurité 

l'inscription et l'authentification à été fait avec laravel.

il faut savoir que lors de l'inscription le mot de passe et stocker dans la base de donnée de manière sécurisé.
En effet le mot de passe et hasher avec un salt par défaut en utilisant Hash::make() cette méthode utilise l'algorithme de hachage bcrypt , couramment utilisé dans Laravel. cela est hasher de façon à ce qu'une personne tentant d'accéder a la base de donnée ne pourra rien lire.
la clé utiliser pour cela est celle ce trouvant dans .env dans APP_KEY (je supose qu'elle est fournis en base64).
APP_KEY=base64:2bzK8JctjNGNRoHpJPY0NT5gg17KqZ2bDj00nl0Hys0=

on peut générer une nouvelle clé en fessant php artisan key:generate


bcyrpt permet en autre de réduire le nombre de mot de passse par seconde qu'un attaquant pourrait hacher lors de l'élaboration d'une attaque par dictionnaire



pour stocker les messages de manière sécuriser laravel propose une méthode de cryptage et de décryptage qui utilise OpenSSL pour fournir un cryptage AES-256.
