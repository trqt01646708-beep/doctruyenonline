# 📚 Truyện Hay TT - Online Story Reading System

**Truyện Hay TT** is an online story-reading web application built with PHP, providing a smooth reading experience for users and a professional content management toolkit for administrators.

---

## ✨ Key Features

### 📖 For Users
- **Homepage**: Displays a list of new and HOT stories with pagination support.
- **Discovery**: Categorize stories into various genres (Xianxia, Romance, Martial Arts, Rebirth, ...).
- **Search**: A quick search system by story title.
- **Reading**: A clean and easy-to-follow chapter reading interface.
- **Personal Account**:
  - Member Registration and Login.
  - Follow favorite story lists.
  - Manage reading history.
- **Interaction**: Allows users to leave comments and rate story series.

### 🛠️ For Administrators (Admin)
- **Overview Statistics**: Monitor website activity through statistical data.
- **Content Management**:
  - **Story Management**: Add new stories, update information, and add chapters.
  - **Genre Management**: Flexible in changing and updating the list of genres.
- **Community Management**:
  - Manage member user lists.
  - Moderate and manage comments within the system.

---

## 🚀 Technologies Used

- **Backend**: PHP (Uses MySQLi for database connection)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Development Environment**: XAMPP / WAMP / Laragon

---

## 📁 Directory Structure

The project is organized clearly:

- `web/`: Main directory containing the application's PHP source code.
  - `admin/`: Administration module (Admin Panel).
  - `database/`: Contains files for handling data query logic (Model).
  - `frontend/`: Contains UI components.
- `public/`: Directory for static resources (Assets).
  - `anhlogo.png`: Website logo.
  - `timkiem.png`: Search icon.
  - Other story cover images.

---

## 🛠️ Installation Guide

To run this project on your personal computer, follow these steps:

1.  **Environment Preparation**:
    - Install local server software such as **XAMPP**.
2.  **Download Source Code**:
    - Download or clone the project folder into XAMPP's `htdocs` directory (typically `C:\xampp\htdocs\`).
3.  **Database Setup**:
    - Start **Apache** and **MySQL** from the XAMPP Control Panel.
    - Access `localhost/phpmyadmin`.
    - Create a new database named: `truyenhaytt`.
    - Import the project's SQL file (if available) into the newly created database.
4.  **Connection Configuration**:
    - Check and edit the `web/database.php` file if your MySQL connection information differs from the default (User: `root`, Password: "").
5.  **Access the Application**:
    - Open your browser and navigate to:
      ```
      http://localhost/baithiphpcb/webtruyen/web/Trangchu.php
      ```

---

⚡ *This project was developed for learning purposes and building a basic story reading application.*

