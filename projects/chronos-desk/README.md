# README for ChronosDesk Project

## ChronosDesk

ChronosDesk is a ticket management application designed to streamline the process of incident tracking and resolution. Built using PHP with an MVC architecture, this application allows users to create, manage, and resolve tickets efficiently while providing real-time updates and communication through an asynchronous chat feature.

### Features

- **User Authentication**: Secure login and registration for users with role-based access control (RBAC).
- **Ticket Management**: Create, update, and view tickets with detailed information and status tracking.
- **Real-Time Messaging**: Asynchronous chat functionality for communication between users and technicians.
- **SLA Tracking**: Service Level Agreement (SLA) timer to monitor response and resolution times.

### Architecture

The application follows the Model-View-Controller (MVC) design pattern, separating the application logic into three interconnected components:

- **Models**: Handle data and business logic (e.g., `User`, `Ticket`, `Database`).
- **Views**: Present the user interface (e.g., `dashboard.php`, `ticket_detail.php`).
- **Controllers**: Manage user input and interaction (e.g., `AuthController`, `TicketController`).

### Setup Instructions

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/abdelazizdiane94-hash/EPITA.git
   cd EPITA/projects/chronos-desk
   ```

2. **Database Configuration**:
   - Import the SQL schema located in `sql/schema.sql` into your MySQL database.
   - Update the database connection settings in `app/Models/Database.php` as needed.

3. **Run the Application**:
   - Ensure you have a local server environment set up (e.g., AMPPS).
   - Place the project in the server's root directory.
   - Access the application via your web browser at `http://localhost/chronos-desk/public/index.php`.

### Usage

- **Login**: Use the login page to access the application.
- **Dashboard**: View ticket statistics and manage tickets from the dashboard.
- **Create Ticket**: Use the form to submit new tickets.
- **Chat**: Engage in real-time chat for ticket discussions.

### Contributing

Contributions are welcome! Please fork the repository and submit a pull request for any enhancements or bug fixes.

### License

This project is licensed under the MIT License. See the LICENSE file for details.

### Contact

For any inquiries, please contact:
- **Name**: M'Fabdel-Aziz (Abdel-Aziz) DIANÉ
- **Email**: abdelaziz.diane94@gmail.com
- **GitHub**: [abdelazizdiane94-hash](https://github.com/abdelazizdiane94-hash)