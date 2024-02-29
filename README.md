# Auth Task

This is a task project.

## Table of Contents

- [Introduction](#introduction)
- [Installation](#installation)

## Introduction

# Technology
1. Backend: Laravel 10 with sqllite
2. Frontend Framwork: Vuejs3
3. CSS Library: TailwindCSS 

# Key Feature
1. Authenticate user can create multiple board
2. Drag and Drop for list and card both.
3. Board owner can invite member to join the board
4. Board owner can manage members and assign to specefic task
5. Card feature-> Description, Labels, Priority level, assigne to member
6. Invitaion list-> user can see the all invitations in the invitation page.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/ziaulh53/auth-task.git

   cd auth-task

   composer install

   yarn or npm i

   cp .env.example .env

   php artisan key:generate

   php artisan migrate

2. Run the project:
   ```bash
   php artisan serve

   yanr dev