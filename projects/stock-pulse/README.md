# StockPulse Project Documentation

## Overview
StockPulse is a secure inventory management solution that utilizes a RESTful API built with PHP and MySQL. The application is designed to provide users with a comprehensive dashboard for managing stock levels, tracking inventory movements, and generating analytical reports.

## Architecture
The StockPulse project is structured into two main components: the API and the client-side application.

### API
The API is responsible for handling all data operations related to inventory management. It is built using PHP and follows a RESTful architecture. The API includes the following components:
- **Controllers**: Manage the logic for handling requests and responses.
- **Middleware**: Handles JWT authentication for secure access to the API.
- **Database Configuration**: Contains the settings for connecting to the MySQL database.

### Client
The client-side application is a dashboard that consumes the API to display inventory data. It is built using HTML, CSS, and JavaScript, and includes:
- **Dashboard Interface**: Displays stock levels and movements using charts.
- **JavaScript Logic**: Fetches data from the API and updates the UI dynamically.

## Setup Instructions
1. **Clone the Repository**
   ```
   git clone https://github.com/abdelazizdiane94-hash/EPITA.git
   cd EPITA/projects/stock-pulse
   ```

2. **Database Setup**
   - Import the SQL schema located in `sql/schema.sql` into your MySQL database.
   - Update the database configuration in `api/config/db.php` with your database credentials.

3. **Run the API**
   - Ensure your local server (e.g., AMPPS) is running.
   - Access the API via `http://localhost/EPITA/projects/stock-pulse/api/index.php`.

4. **Run the Client**
   - Open `client/index.html` in your web browser to access the dashboard.

## Usage
- Use the dashboard to view and manage inventory.
- The API endpoints can be accessed directly for integration with other applications.

## Features
- Secure JWT authentication for API access.
- Real-time inventory tracking and management.
- Analytical dashboard with visual representations of stock data.

## License
This project is licensed under the MIT License. See the LICENSE file for more details.

## Author
M'Fabdel-Aziz (Abdel-Aziz) DIANÉ  
Email: abdelaziz.diane94@gmail.com  
GitHub: [abdelazizdiane94-hash](https://github.com/abdelazizdiane94-hash)