# ltw14g07

## Features

**User:**
- [x] Register a new account.
- [x] Log in and out.
- [x] Edit their profile, including their name, username, password, and email.

**Freelancers:**
- [x] List new services, providing details such as category, pricing, delivery time, and service description, along with images or videos.
- [x] Track and manage their offered services.
- [x] Respond to inquiries from clients regarding their services and provide custom offers if needed.
- [x] Mark services as completed once delivered.

**Clients:**
- [!] Browse services using filters like category, price, and rating.
- [x] Engage with freelancers to ask questions or request custom orders.
- [x] Hire freelancers and proceed to checkout (simulate payment process).
- [ ] Leave ratings and reviews for completed services.

**Admins:**
- [!] Elevate a user to admin status.
- [!] Introduce new service categories and other pertinent entities.
- [!] Oversee and ensure the smooth operation of the entire system.

**Extra:**
- [X] Messages displaying warnings, errors and successes.

## Running

    sqlite3 database/database.db < database/database.sql
    php -S localhost:9000

## Credentials

- test@gmail.com/1234
- request@gmail.com/1234
- admin@example.com/admin123

## Problems

Due to time limit constraints, a participant was not able to merge their work to the main branch. So the code remins in a branch 'new', and the respective features were pointed as '!'.