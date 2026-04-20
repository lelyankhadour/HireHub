# 🚀 HireHub — Freelance Marketplace API

## 📝 Introduction
**HireHub** is a Laravel 12 REST API designed to manage freelancers, clients, projects, bids, reviews, and advanced filtering.  
The system focuses on clean architecture, scalability, and maintainability, with strict separation between HTTP controllers and business logic using Services + Interfaces.

---

## 📚 Table of Contents
1. [Project Overview](#project-overview)  
2. [Tech Stack](#tech-stack)  
3. [Installation](#installation)  
4. [Run the Application](#run-the-application)  
5. [API Endpoints](#api-endpoints)  



---

## 📌 Project Overview
HireHub is a backend API for a freelance marketplace where:

- Clients post projects  
- Freelancers submit bids  
- Clients accept one bid → system auto‑rejects the rest  
- Projects and freelancers can be filtered and sorted  
 

I am working on The system is built to be **extensible**, allowing future features like notifications, messaging, and analytics without modifying existing controllers."not completed"

---

## ⚙️ Tech Stack

- **Framework:** Laravel 12  
- **Language:** PHP 8.3  
- **Database:** MySQL  
- **Auth:** Laravel Sanctum  
- **Architecture:** Service Layer + Interfaces + Clean Controllers  
- **API Format:** JSON Only  

---

## 📦 Installation

```bash
git clone 
cd hirehub
composer install
 ```
 
## 📦 API Endpoints


# 🔗 API Endpoints

## 🔐 Authentication
POST /api/v1/auth/register
POST /api/v1/auth/login
POST /api/v1/auth/logout  
 
---

## 📊 Dashboard
GET /api/v1/dashboard
---


## 📁 Projects
GET    /api/v1/projects
GET    /api/v1/projects/{project}
POST   /api/v1/projects              (auth required)
POST   /api/v1/projects/{project}/bids   (auth + freelancer.verified)

### Project Filters
?tag=Laravel
?min_budget=100
?max_budget=500
?last_month=1
?sort=newest
?sort=top_rated

---

## 💼 Bids
POST /api/v1/projects/{id}/bids
POST /api/v1/bids/{id}/accept

---

## 👤 Freelancers
GET /api/v1/freelancers
GET /api/v1/freelancers/{user}

---

## 🧑 Profile (Authenticated User)
GET  /api/v1/profile                 (auth required)
PUT  /api/v1/profile                 (auth required)
PUT  /api/v1/profile/skills          (auth + freelancer.verified)


## 📬 Postman Collection

The API collection is included inside the project:

/postman/HireHub.postman_collection.json