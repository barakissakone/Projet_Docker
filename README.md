# Site de prise de rendez-vous médicaux --- RDV Medical

> **⚠️ MEMBRE DU GROUPE :**\
> - **Koné Barakissa** (DevOps & Déploiement)



## 1. Présentation du Projet

Ce projet est une application web de gestion de rendez-vous médicaux
déployée dans une architecture **conteneurisée (Docker)** avec un
**reverse proxy (Caddy)** et une exposition publique sécurisée via
**Cloudflare Tunnel**.

L'objectif principal du travail est moins le code applicatif que la mise
en place d'une **infrastructure DevOps professionnelle**, conforme aux
bonnes pratiques : isolation réseau, persistance des données,
redémarrage automatique et exposition sécurisée.

### Fonctionnalités principales :

-   Inscription et connexion des patients\
-   Prise et consultation de rendez-vous\
-   Gestion des données via MySQL\
-   Administration de la base via **phpMyAdmin**\
-   Accès public sécurisé en HTTPS via Cloudflare Tunnel

**Lien accessible (si tunnel actif) :**\
👉 https://rdv.sozdoc.com

**Screenshot de l'application déployée :**\
![](screenshot.jpg)

-

## 2. Architecture Technique

### Schéma d'infrastructure

Ce schéma est généré dynamiquement à partir du fichier
`docs/architecture.puml` présent dans ce dépôt.

![Architecture du
Projet](http://www.plantuml.com/plantuml/proxy?cache=no&src=https://raw.githubusercontent.com/barakissakone/Projet_Docker/main/docs/architecture.puml)

### Description des services

  
  Service           Image Docker               Rôle              Port interne
  ----------------- -------------------------- ----------------- -----------------
  **Caddy (Proxy)** `caddy:2`                  Reverse proxy &   80
                                               point d'entrée    
                                               unique            

  **App PHP**       Image build locale         Site RDV médical  80

  **MySQL**         `mysql:8.0`                Base de données   3306
                                               persistante       

  **phpMyAdmin**    `phpmyadmin/phpmyadmin`    Interface admin   80
                                               BDD               

  **Cloudflared**   `cloudflare/cloudflared`   Exposition        N/A
                                               Internet          
                                               sécurisée         


## 3. Guide d'installation

### 1️⃣ Cloner le dépôt

``` bash
git clone https://github.com/barakissakone/Projet_Docker.git
cd Projet_Docker
```

### 2️⃣ Créer un fichier `.env` (NON COMMITTÉ)

``` env
MYSQL_ROOT_PASSWORD=root_password
CLOUDFLARE_TUNNEL_TOKEN=TON_TOKEN_ICI
```

### 3️⃣ Lancer la stack

``` bash
docker compose up -d --build
```

### 4️⃣ Accéder aux services en local

-   Application : http://localhost\
-   phpMyAdmin : http://localhost:8082

### 5️⃣ Vérifier le tunnel Cloudflare

``` bash
docker logs -f rdv_cloudflared
```

Tu dois voir :

    Tunnel connected successfully



## 4. Méthodologie & Transparence IA

### Organisation du travail

-   Développement en solo\
-   Utilisation de Docker Compose pour orchestrer les services\
-   Configuration progressive : d'abord local → puis tunnel Cloudflare\
-   Tests systématiques via `docker logs` et `docker ps`

### Utilisation de l'IA (ChatGPT)

**Outils utilisés :**\
- ChatGPT

**Usage :** - **Génération de code :**\
- Structuration du `docker-compose.yml`\
- Ajout de `healthcheck` pour la robustesse des services

-   **Débogage :**
    -   Résolution des erreurs Docker et PowerShell\
    -   Aide à comprendre et configurer **Cloudflare (DNS, Tunnels, Zero
        Trust, hostname public)**
-   **Documentation :**
    -   Aide à rédiger et structurer ce `README.md`\
    -   Création du schéma PlantUML

**Apprentissage :**\
- L'IA a aidé à accélérer la configuration et le diagnostic,\
- Mais j'ai compris et validé chaque étape : Docker, réseaux internes,
Caddy et Cloudflare.

------------------------------------------------------------------------

## 5. Difficultés rencontrées & Solutions

**Problème 1 :** Le domaine `sozdoc.com` n'était pas reconnu par
Cloudflare.\
**Solution :** Mise à jour des serveurs DNS chez le registrar vers
Cloudflare.

**Problème 2 :** Erreur Cloudflare "Adresse IPv6 requise".\
**Solution :** Correction de l'ajout DNS en utilisant le bon type
d'enregistrement.

**Problème 3 :** Cloudflare Tunnel ne se connectait pas.\
**Solution :**\
- Création d'un `.env` propre\
- Lancement du tunnel via Docker Compose\
- Vérification des logs.


