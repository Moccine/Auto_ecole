OISE TUNEL DE RESERVATION

Ce projet est constitué d'une base Docker, d'une application Symfony 4.3.
### Why ?
---
Cet outil permet de faire une resevation des séjours lingustiques de haute gamme.

### Requirements
---
* Node >= 12.x
* yarn = 1.12.*
* Serveur Apache 2.4
* PHP 7.2 avec extension opcache et APCu
* Mysql Ver 14.14 Distrib 5.7
* composer
* Docker (pour le développement)
### Usage
---

### Installation
---

```
make install    

```

Installer la base de données:

### Installation Front-End development
---

Installation des dépendances :

```
cd app/integration && yarn start
```

Production :

```
cd app/integration && yarn start:prod
```

Développement :

```
cd app/integration && yarn start:dev && yarn watch
```

### Mail
---
To create the mail, we use https://mjml.io/
```
cd app/integration/mail/ && yarn run start
```
The source file is app/integration/mail/mail.mjml

The pre-final file is app/integration/mail/mail.html

The final files for symfony is app/symfony/templates/mail/*

### Configuration
---

Surcharge de la configuration Docker :

```
cp docker-compose.override.yml.dist docker-compose.override.yml
```

Modification de la configuration d'envionnement :

```
cp .env.dist .env
```

### Troubleshooting
---

### FAQ
---

### Deployment
---


### Documentation
---
Docker operations :

Lancer le docker : ```make up```

Arrêter le docker : ```make stop```

Lancer le bash : ```make bash_app```

Redemarrer le docker: ```make restart```

### Authors / Maintainers
---


- Mouctar  Sow

