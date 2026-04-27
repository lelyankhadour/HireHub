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
 

I am working on The system is built to be **extensible**, allowing future features like notifications, messaging, and analytics without modifying existing controllers.

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
POST /api/v1/projects/{project}/close(only client owner the project)


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
---
## Reviews
POST /api/v1/projects/{project}/reviews  
POST /api/v1/projects/{freelancer}/reviews


## 📬 Postman Collection

The API collection is included inside the project:

/postman/HireHub.postman_collection.json


## Caching
The system uses Laravel Cache Tags to improve performance and organize cached data in a scalable way.
Instead of clearing the entire cache or forgetting individual keys manually, cache tags allow selective invalidation of related cached entries.

Why Cache Tags?
To group related cached data (e.g., all project-related cache under projects tag).

To invalidate only the affected cache when a project is updated.

To avoid deleting unrelated cached data.

To keep the API fast and consistent even as the system grows.




This ensures that only project‑related cache is cleared, keeping the rest of the system cached and fast.

Performance Impact
Using cache tags improved API response time by up to ~70%, especially on endpoints that load multiple relationships or perform heavy filtering.

## Jobs & Queue Workers
Background jobs are used to handle heavy or non‑urgent operations asynchronously, improving API responsiveness.

Jobs Implemented
RejectOtherBidsJob — Automatically rejects all other bids when one bid is accepted.

SendBidAcceptedEmailJob — Sends an email notification to the accepted freelancer.

RecalculateFreelancerRatingJob — Recalculates freelancer rating after each review.

These jobs run in the background using Laravel’s queue system, ensuring the API remains fast, scalable, and responsive.
