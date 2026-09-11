# MENO CLEAN SERVICES — Professional Cleaning Company Website

![Symfony](https://img.shields.io/badge/Symfony-8.1-black?style=for-the-badge\&logo=symfony)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge\&logo=php\&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge\&logo=mysql\&logoColor=white)
![Doctrine](https://img.shields.io/badge/Doctrine-ORM-FC6A31?style=for-the-badge)
![Twig](https://img.shields.io/badge/Twig-Templates-BACC00?style=for-the-badge\&logo=twig\&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6%2B-F7DF1E?style=for-the-badge\&logo=javascript\&logoColor=black)
![Stimulus](https://img.shields.io/badge/Stimulus-3.2-77E8B9?style=for-the-badge\&logo=stimulus\&logoColor=black)
![Turbo](https://img.shields.io/badge/Turbo-8.0-4B5563?style=for-the-badge)
![OVHcloud](https://img.shields.io/badge/OVHcloud-123F6D?style=for-the-badge&logo=ovh&logoColor=white)
![FileZilla](https://img.shields.io/badge/FileZilla-BF0000?style=for-the-badge&logo=filezilla&logoColor=white)

A modern showcase website developed for **MENO CLEAN SERVICES**, a professional cleaning company operating in the Île-de-France region.

The project is built with **Symfony 8.1** and was reconstructed from an existing static HTML/CSS/JavaScript website to provide a more maintainable, secure and scalable architecture.

---

## Table of Contents

* [Project Overview](#-project-overview)
* [Project Objectives](#-project-objectives)
* [Key Features](#-key-features)
* [Technology Stack](#-technology-stack)
* [Application Architecture](#-application-architecture)
* [Project Structure](#-project-structure)
* [Database Model](#-database-model)
* [Form Processing](#-form-processing)
* [JavaScript Architecture](#-javascript-architecture)
* [SEO](#-seo)
* [Security](#-security)
* [Installation](#-installation)
* [Useful Commands](#-useful-commands)
* [Deployment](#-deployment)
* [Known Limitations](#-known-limitations)
* [Future Improvements](#-future-improvements)
* [Development Principles](#-development-principles)
* [Project Status](#-project-status)

---

# Project Overview

The main purpose of the website is to **convert visitors into quote requests**.

The website presents MENO CLEAN SERVICES and its professional cleaning services through a modern one-page interface.

The main sections include:

* Company presentation
* Services
* Before/after gallery
* Customer testimonials
* Quote request form
* Contact form
* FAQ
* Legal information
* Call-to-action sections

The application focuses on a simple business objective:

> **Turn a website visitor into a potential customer.**

The quote and contact forms are therefore critical components of the application.

---

# Project Objectives

The original website was developed using static HTML, CSS and JavaScript, with a large JavaScript file and PHP scripts responsible for sending emails.

The Symfony migration was designed to improve the project in several areas.

### Maintainability

Centralize business data inside the database instead of duplicating services inside templates.

### Security

Introduce:

* Server-side validation
* CSRF protection
* Anti-spam mechanisms
* Secure environment variables
* SQL injection protection
* Automatic Twig escaping

### Reliability

Store requests in the database **before sending notification emails**.

This ensures that a quote request is not lost if the SMTP server becomes temporarily unavailable.

### Scalability

Prepare the application for future features such as:

* Authentication
* Administration dashboard
* Service management
* Request management
* Automated workflows

---

# Key Features

## Service Management

The application currently contains **18 cleaning services** stored in the database.

Services can be:

* Categorized
* Filtered
* Reordered
* Activated or deactivated
* Displayed dynamically in the website
* Used inside the quote request form

The same database source feeds both the service cards and the quote form.

This avoids duplicating service information across the application.

---

## Quote Request System

Visitors can submit a quote request through a dedicated form.

The process is:

```text
Visitor submits form
        ↓
CSRF token verification
        ↓
Anti-spam verification
        ↓
Server-side validation
        ↓
Request stored in database
        ↓
Notification email sent
        ↓
Email status updated
        ↓
HTTP 303 redirect
        ↓
Success message displayed
```

If email delivery fails, the request remains stored in the database.

This prevents the loss of customer requests.

---

## Contact Form

The website also provides a dedicated contact form allowing visitors to send a message directly to the company.

The form benefits from the same security mechanisms as the quote request system.

---

## Email Notifications

Emails are handled through:

* Symfony Mailer
* SMTP
* OVH infrastructure
* Twig email templates

The visitor's email address is used as the `Reply-To` address rather than the sender address.

This helps maintain correct email authentication and improves reply handling.

---

## Interactive Service Filtering

Services can be filtered according to their categories.

The available filters are generated from the database.

This means that filters cannot point to categories that do not contain any service.

---

## Before / After Gallery

The website contains interactive before/after image comparisons.

Users can interact with the comparison using:

* Mouse
* Touch
* Keyboard

---

## FAQ

The website contains an accordion-based FAQ section.

FAQ content is centralized in:

```text
src/Content/FaqProvider.php
```

The same source is used for:

* Visible FAQ content
* Structured JSON-LD data

This prevents duplicated FAQ information.

---

## Responsive Navigation

The website includes a mobile navigation system with:

* Mobile menu
* Click-to-close behavior
* Escape-key support
* Responsive layout

---

# Technology Stack

| Technology         | Purpose                          |
| ------------------ | -------------------------------- |
| **PHP 8.x**        | Backend programming language     |
| **Symfony 8.1**    | Main web framework               |
| **Twig**           | Server-side templating           |
| **Doctrine ORM**   | Database abstraction and ORM     |
| **MySQL**          | Relational database              |
| **JavaScript**     | Client-side interactions         |
| **Stimulus 3.2**   | Frontend behavior controllers    |
| **Turbo 8**        | Navigation and form interactions |
| **Symfony Mailer** | Email management                 |
| **SMTP OVH**       | Email delivery                   |
| **AssetMapper**    | Asset management                 |
| **CSS**            | Responsive interface styling     |
| **JSON-LD**        | Structured SEO data              |

The application does not rely on external JavaScript frameworks or CSS frameworks.

---

# Application Architecture

The application follows a structured Symfony architecture.

```text
                    ┌─────────────────────────┐
                    │         Visitor         │
                    └────────────┬────────────┘
                                 │
                                 ▼
                    ┌─────────────────────────┐
                    │       Symfony App       │
                    │                         │
                    │ Controllers             │
                    │ Forms                   │
                    │ Services                │
                    │ Twig Templates          │
                    └────────────┬────────────┘
                                 │
                ┌────────────────┼────────────────┐
                │                │                │
                ▼                ▼                ▼
        ┌─────────────┐  ┌──────────────┐  ┌─────────────┐
        │   MySQL     │  │ Symfony      │  │ Stimulus /  │
        │  Database   │  │   Mailer     │  │    Turbo    │
        └─────────────┘  └──────┬───────┘  └─────────────┘
                                │
                                ▼
                         ┌─────────────┐
                         │ SMTP / OVH  │
                         └─────────────┘
```

---

# Project Structure

```text
menoCleanService/
│
├── src/
│   ├── Content/
│   │   └── FaqProvider.php
│   │
│   ├── Controller/
│   │   ├── HomeController.php
│   │   ├── LegalController.php
│   │   └── SitemapController.php
│   │
│   ├── DataFixtures/
│   │   └── ServiceFixtures.php
│   │
│   ├── Entity/
│   │   ├── Service.php
│   │   ├── DemandeDevis.php
│   │   └── MessageContact.php
│   │
│   ├── Enum/
│   │   ├── CategorieService.php
│   │   └── StatutDemande.php
│   │
│   ├── Form/
│   │   ├── DemandeDevisType.php
│   │   └── MessageContactType.php
│   │
│   ├── Repository/
│   │   ├── ServiceRepository.php
│   │   ├── DemandeDevisRepository.php
│   │   └── MessageContactRepository.php
│   │
│   └── Service/
│       ├── DemandeHandler.php
│       └── NotificationMailer.php
│
├── templates/
│   ├── base.html.twig
│   │
│   ├── partials/
│   │   ├── _header.html.twig
│   │   ├── _footer.html.twig
│   │   ├── _icons.html.twig
│   │   ├── _back_to_top.html.twig
│   │   ├── _loading_screen.html.twig
│   │   ├── _schema_organization.html.twig
│   │   └── _schema_faq.html.twig
│   │
│   ├── home/
│   │   ├── index.html.twig
│   │   └── _sections/
│   │       ├── _hero.html.twig
│   │       ├── _trustbar.html.twig
│   │       ├── _about.html.twig
│   │       ├── _services.html.twig
│   │       ├── _why.html.twig
│   │       ├── _gallery.html.twig
│   │       ├── _testimonials.html.twig
│   │       ├── _quote.html.twig
│   │       ├── _contact.html.twig
│   │       ├── _faq.html.twig
│   │       └── _cta.html.twig
│   │
│   ├── legal/
│   │   ├── mentions_legales.html.twig
│   │   ├── politique_confidentialite.html.twig
│   │   └── cgu.html.twig
│   │
│   ├── sitemap/
│   │   └── sitemap.xml.twig
│   │
│   └── emails/
│       ├── _base.html.twig
│       ├── _macros.html.twig
│       ├── demande_devis.html.twig
│       └── message_contact.html.twig
│
├── assets/
│   ├── app.js
│   ├── stimulus_bootstrap.js
│   ├── styles/
│   │   └── app.css
│   ├── images/
│   └── controllers/
│
├── config/
├── migrations/
├── public/
├── var/
├── vendor/
├── .env
├── .env.local
├── composer.json
└── README.md
```

---

# Website Pages

The application uses a one-page architecture with dedicated legal pages.

| Route                        | Purpose              | Indexing          |
| ---------------------------- | -------------------- | ----------------- |
| `/`                          | Main website         | `index, follow`   |
| `/mentions-legales`          | Legal notice         | `noindex, follow` |
| `/politique-confidentialite` | Privacy policy       | `noindex, follow` |
| `/cgu`                       | Terms and conditions | `noindex, follow` |
| `/sitemap.xml`               | XML sitemap          | —                 |

The homepage contains eleven main sections.

```text
#accueil
#apropos
#services
#pourquoi
#galerie
#avis
#devis
#contact
#faq
CTA
```

---

# Database Model

The application uses **MySQL** with **Doctrine ORM**.

## Service

Represents one of the 18 available cleaning services.

```text
Service
├── id
├── nom
├── description
├── icone
├── categorie
├── slug
├── position
└── estActif
```

---

## DemandeDevis

Represents a quote request submitted by a visitor.

```text
DemandeDevis
├── id
├── service
├── prestationLibelle
├── consentement
├── statut
├── createdAt
└── emailEnvoye
```

The `prestationLibelle` field stores the service name at the time the request was created.

This prevents historical requests from changing if a service is renamed later.

---

## MessageContact

Represents a standard contact message.

```text
MessageContact
├── id
├── nom
├── email
├── telephone
└── message
```

Quote requests and contact messages are intentionally represented by two separate entities because they correspond to different business processes.

---

# Form Processing & Validation

Symfony's validation system is used to validate all submitted data server-side.

The application implements:

* CSRF protection
* Server-side validation
* Anti-spam honeypot field
* GDPR consent validation
* Database persistence
* Email notification
* Error handling
* HTTP status management

### Success

```text
Valid submission
      ↓
Database persistence
      ↓
Email notification
      ↓
HTTP 303 redirect
```

### Failure

```text
Invalid submission
      ↓
Validation errors
      ↓
HTTP 422 response
      ↓
Form displayed again
```

This behavior is particularly important because **Turbo requires appropriate HTTP responses for form submissions**.

---

# JavaScript Architecture

The application uses **Stimulus** for frontend interactions.

There are ten dedicated controllers:

| Controller    | Responsibility                |
| ------------- | ----------------------------- |
| `loader`      | Loading screen                |
| `header`      | Header behavior               |
| `mobile_nav`  | Mobile navigation             |
| `reveal`      | Section animations            |
| `counter`     | Animated counters             |
| `compare`     | Before/after comparison       |
| `filter`      | Service and gallery filtering |
| `carousel`    | Testimonials carousel         |
| `accordion`   | FAQ accordion                 |
| `back_to_top` | Back-to-top button            |

The JavaScript architecture follows a progressive enhancement approach.

The content remains accessible without JavaScript whenever possible.

JavaScript is primarily responsible for:

* Interaction
* Animation
* State management
* Dynamic behavior

---

# Accessibility & User Experience

The project implements several accessibility-oriented principles.

### Reduced Motion

The application respects:

```css
prefers-reduced-motion
```

Users who prefer reduced motion are not forced to experience unnecessary animations.

### Keyboard Interaction

Interactive components such as the before/after comparison support keyboard interaction.

### Progressive Enhancement

The website content remains available even when JavaScript is disabled.

---

# SEO

The website includes several SEO mechanisms.

### Structured Data

JSON-LD structured data is used for:

* `LocalBusiness`
* `FAQPage`

### Social Sharing

The application includes:

* Open Graph metadata
* Twitter Card metadata

### XML Sitemap

The sitemap is generated dynamically through Symfony instead of being maintained as a static file.

### Legal Pages

Legal pages use `noindex` to prevent unnecessary indexing.

---

# Security

Security was one of the main reasons for migrating the original website to Symfony.

| Security Measure         | Implementation                 |
| ------------------------ | ------------------------------ |
| Server-side validation   | Symfony validation constraints |
| CSRF protection          | Symfony CSRF tokens            |
| Anti-spam                | Honeypot field                 |
| GDPR consent             | `IsTrue` validation constraint |
| SQL Injection protection | Doctrine parameterized queries |
| XSS protection           | Twig automatic escaping        |
| Secrets protection       | `.env.local`                   |
| Error handling           | Server-side logging            |

### Environment Variables

Sensitive information such as:

* Database credentials
* SMTP credentials

must never be committed to Git.

Example:

```dotenv
DATABASE_URL="mysql://username:password@127.0.0.1:3306/database"
MAILER_DSN="smtp://username:password@smtp.example.com:465"
```

The `.env.local` file must remain outside version control.

---

# Installation

## Requirements

Before installing the project, make sure you have:

* PHP 8.x
* Composer
* MySQL
* Symfony CLI
* Required PHP extensions:

  * `pdo_mysql`
  * `intl`
  * `mbstring`
  * `xml`
  * `curl`
  * `zip`

---

## Clone the Repository

```bash
git clone <repository-url>
cd menoCleanService
```

---

## Install Dependencies

```bash
composer install
```

---

## Configure the Database

Create the database:

```sql
CREATE DATABASE menoclean
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Create the database user:

```sql
CREATE USER 'meno'@'localhost'
IDENTIFIED BY 'your_password';

GRANT ALL PRIVILEGES
ON menoclean.*
TO 'meno'@'localhost';

FLUSH PRIVILEGES;
```

---

## Configure Environment Variables

Create:

```text
.env.local
```

Example:

```dotenv
DATABASE_URL="mysql://meno:your_password@127.0.0.1:3306/menoclean?serverVersion=8.0.43&charset=utf8mb4"

MAILER_DSN="smtp://contact%40example.com:your_password@smtp.example.com:465"
```

Do not commit this file.

---

## Run Database Migrations

```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

Load development fixtures:

```bash
php bin/console doctrine:fixtures:load --no-interaction
```

> Warning: `doctrine:fixtures:load` clears existing tables. Use it only in development environments.

---

## Start the Development Server

```bash
symfony server:start --no-tls
```

The application should then be available locally.

---

# Useful Commands

## Symfony Diagnostics

```bash
php bin/console doctrine:schema:validate
```

```bash
php bin/console doctrine:schema:update --dump-sql
```

```bash
php bin/console debug:dotenv
```

```bash
php bin/console debug:router
```

```bash
php bin/console debug:asset-map
```

---

## Database

```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

```bash
php bin/console doctrine:fixtures:load --no-interaction
```

```bash
php bin/console dbal:run-sql "SELECT COUNT(*) FROM service"
```

---

## Email Testing

```bash
php bin/console mailer:test recipient@example.com
```

---

## Cache

```bash
php bin/console cache:clear
```

---

# Deployment

The original deployment environment is an **OVH shared hosting environment without SSH access**.

The deployment process therefore relies on local preparation and SFTP.

```text
Local Development
       ↓
composer install --no-dev
       ↓
asset-map:compile
       ↓
SQL export
       ↓
SFTP transfer
       ↓
Configure web root
       ↓
Import database
       ↓
Configure .env.local
       ↓
Production Website
```

### Production Preparation

```bash
composer install --no-dev --optimize-autoloader
```

```bash
php bin/console asset-map:compile
```

The web server must point specifically to:

```text
/public
```

The project root must never be directly exposed to the web.

This prevents sensitive files such as `.env.local` from becoming publicly accessible.

---

# Known Limitations

The current version has several known limitations.

### No Authentication System

There is currently no authentication or administration interface.

### Request Management

Quote requests are stored in the database but there is currently no dedicated interface for staff to review and manage them.

### Email Dependency

Visitors may experience a delay while the SMTP server connection is established.

### Image Optimization

Further image optimization is planned for future versions.

### Accessibility Audit

A complete accessibility audit has not yet been performed.

### External Google Resources

The website uses external Google resources, which must be considered in the privacy policy.

### Shared Hosting Constraints

The current hosting environment does not provide SSH access, limiting certain server-side operations.

---

# Future Improvements

The planned V2 improvements are prioritized according to business value.

## 1. Authentication

Introduce a secure authentication system for administrators.

## 2. Administration Dashboard

Create an interface allowing staff to:

* View quote requests
* Manage contact messages
* Update request statuses
* Monitor incoming requests

## 3. Service CRUD

Add a complete CRUD interface for:

* Creating services
* Editing services
* Deleting services
* Reordering services
* Activating/deactivating services

## 4. Automated Tests

Introduce automated tests to protect the existing V1 behavior during future development.

## 5. Symfony Messenger

Move email delivery into an asynchronous queue.

This would prevent visitors from waiting for the SMTP connection.

## 6. Image Optimization

Implement:

* Modern image formats
* Lazy loading
* Better compression
* Responsive images

## 7. Accessibility Audit

Perform a complete accessibility review and improve WCAG compliance.

---

# Development Principles

Several architectural principles guide the project.

### Single Source of Truth

Services are stored in the database instead of being duplicated inside templates.

### Separation of Responsibilities

Controllers remain lightweight.

Business logic is delegated to dedicated services such as:

```text
DemandeHandler
NotificationMailer
```

### Reusable Components

Twig partials and reusable components prevent unnecessary duplication.

### Secure by Design

Security mechanisms are implemented at the application level instead of relying exclusively on frontend validation.

### Progressive Enhancement

JavaScript enhances the experience without becoming a requirement for accessing the core content.

### Maintainability

The application is structured so that future features can be introduced without significantly modifying existing components.

---

# Project Status

**Current version:** V1

**Status:** Active / Evolving

The current version provides a complete professional showcase website with:

* Dynamic service management
* Quote requests
* Contact forms
* Database persistence
* Email notifications
* Interactive UI components
* SEO optimization
* Security mechanisms
* Responsive design

The project is not considered a final production architecture yet. Future versions are planned to introduce authentication, administration tools, automated testing, asynchronous email processing and additional optimizations.

---

# Skills Demonstrated

This project demonstrates practical experience in:

* PHP development
* Symfony framework
* MVC architecture
* Twig templating
* Doctrine ORM
* MySQL
* SQL
* Database design
* REST-oriented application concepts
* Form handling
* Server-side validation
* CSRF protection
* XSS protection
* Secure environment configuration
* Email systems
* SMTP
* JavaScript
* Stimulus
* Turbo
* Responsive web development
* SEO
* JSON-LD
* Accessibility
* Git/GitHub workflow
* Application architecture
* Debugging and maintenance

---

# Author

**Kylian Siewe Tiague**

Cybersecurity Student — ESILV

Bachelor in Digital Engineering — Cybersecurity

---

# Acknowledgements

This project was developed as a practical web development project with a focus on:

* Modern Symfony architecture
* Security
* Maintainability
* User experience
* Business-oriented web development
* Future scalability

---

# License

This project is an academic/personal project.

Unless otherwise specified, the source code and project assets are not intended for commercial redistribution.
