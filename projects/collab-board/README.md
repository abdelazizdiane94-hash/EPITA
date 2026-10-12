# CollabBoard Project Documentation

## Overview
CollabBoard is an interactive Agile retrospective tool designed to facilitate team collaboration and feedback collection. It allows users to create, manage, and vote on retrospective cards using a drag-and-drop interface. The application is built with JavaScript for the front-end and PHP for the back-end, utilizing a MySQL database for data persistence.

## Features
- **Drag-and-Drop Interface**: Users can easily move cards between different categories (Start, Stop, Continue) using HTML5 Drag and Drop API.
- **Voting System**: Each user can vote on cards, with a limit of 5 votes per session to prioritize feedback.
- **Data Synchronization**: The application syncs the state of the board with the database, ensuring that all changes are saved and can be retrieved later.
- **Export Functionality**: Users can export the board state as Markdown or PDF for documentation purposes.

## Project Structure
```
collab-board/
├── README.md
├── api/
│   └── sync.php
├── public/
│   ├── board.css
│   ├── board.js
│   └── index.html
└── sql/
    └── schema.sql
```

## Setup Instructions
1. **Clone the Repository**: 
   ```
   git clone https://github.com/abdelazizdiane94-hash/EPITA.git
   ```

2. **Database Setup**:
   - Import the SQL schema located in `sql/schema.sql` into your MySQL database.
   - Ensure that the database connection settings in `api/sync.php` are correctly configured to match your database credentials.

3. **Run the Application**:
   - Start your local server (e.g., using AMPPS).
   - Access the application via your web browser at `http://localhost/collab-board/public/index.html`.

## Usage
- Open the application in your browser.
- Drag and drop cards into the appropriate categories.
- Click on cards to vote and provide feedback.
- Use the export feature to save your retrospective results.

## Technologies Used
- **Front-End**: HTML5, CSS3, JavaScript (ES6)
- **Back-End**: PHP
- **Database**: MySQL

## Contribution
Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License
This project is licensed under the MIT License. See the LICENSE file for more details.