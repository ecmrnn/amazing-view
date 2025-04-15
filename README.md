# Reservation and Billing System
*A (Capstone Project) system built for Amazing View Mountain Resort*

## 📌 Overview
The Reservation and Billing System is a web-based application designed for Amazing View Mountain Resort to streamline the reservation process, automate billing, and manage resort operations efficiently. It is tailored for three types of users: Admins, Receptionists, and Guests, each with separate access and responsibilities.

## 👥 Target Users
- Admins / Management – Manage users, view reports, and oversee system activity.
- Receptionists – Handle guest reservations, confirmations, and billing.
- Guests – Make and manage their room bookings.

## ✨ Features
- 📧 Transactional Emails: Integrated with [Mailtrap SMTP](https://mailtrap.io/) for sending reservation confirmations, updates, and other emails.
- ⚡ Real-Time Live Updates: Using [Laravel Reverb](https://laravel.com/docs/12.x/reverb) for broadcasting changes to reservation status and availability.
- ⏰ Scheduled Tasks: Periodic jobs handle room availability, OTP expirations, and other background tasks using [Laravel Task Scheduling](https://laravel.com/docs/12.x/scheduling).
- 📄 Report Generation: Generate downloadable PDF reports with [spatie/laravel-pdf](https://spatie.be/docs/laravel-pdf/v1/introduction).
- 🔐 Role-Based Access Control: Fine-grained permissions using [spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v6/introduction).
- 🧑‍🤝‍🧑 Multi-Account System: Role-specific login for admins, receptionists, and guests.
- 🛏️ Room Availability Tracking: Live room availability with temporary locks during the reservation flow.

## 🛠 Teck Stack
- Laravel: PHP backend framework
- Livewire: Dynamic interfaces without leaving Laravel
- TailwindCSS: Utility-first CSS framework
- AlpineJS: Lightweight JavaScript framework for interactivity



