# Developer Skill Test

A comprehensive full-stack web application for managing school operations including students, staff, classes, notices, and leave management. This project serves as a skill assessment platform for **Frontend**, **Backend**, and **Blockchain** developers.

## 🏗️ Project Architecture

```
react-.net/
├── Frontend/           # React.js, Redux, React Router DOM, Tailwind CSS
└── Backend/             # C#, .NET, PostgreSQL
```
```
react-java/
├── Frontend/           # React.js, Redux, React Router DOM, Tailwind CSS
└── Backend/            # Spring (Boot, Data, Security), JPA / Hibernate, PostgreSQL, Razorpay
```
```
react-node/
├── frontend/           # React + TypeScript + Material-UI
└── backend/            # Node.js + Express + PostgreSQL
```
```
react-php/
├── Frontend/           # React.js, Redux, React Router DOM, Tailwind CSS
└── Backend/             # PHP + MySQL
```
```
react-python/
├── Frontend/           # React.js, Redux, React Router DOM, Tailwind CSS
└── Backend/             # Django-MongoDB integration
```

## 🎯 Skill Test Problems

### 🧪**Problem 1: Frontend Developer Challenge - React - Typescript**
**Fix "Add New Notice" Page**
```bash
- Location: '/react-node/frontend'
- Target: '/app/notices/add'
- Issue: When clicking the 'Save' button, the 'description' field does not get saved
- Skills Tested: React, Form handling, State management, API integration
- Expected Fix: Ensure description field is properly bound and submitted
```

### 🧪**Problem 2: Backend Developer Challenge - Java - Spring Boot**
**Complete CRUD Operations in Student Management**
```bash
- Location: '\react-java\Backend\DAA\DAA\src\main\java\com\nam\controller\AuthController.java'
- Issue: Implement missing CRUD operations
- Skills Tested: Spring Boot, Data, Security, JPA / Hibernate, PostgreSQL, Razorpay
- Expected Implementation: 'SignUp', 'SignIn', 'RefreshToken' and 'SignOut' operations
- After completing, check that frontend part works well concurrently with backend without any issues
```

### 🧪**Problem 3: Backend Developer Challenge - Python**
**Complete Register & SignIn Operations in Student Management**
```bash
- Location: '\react-python\Backend\accounts\user_views.py'
- Issue: Implement missing 'Register' and 'SignIn' operations for student management
- Skills Tested: Django-MongoDB integration
- Expected Implementation: Full 'Register' and 'SignIn' operations
- After completing, check that frontend part works well concurrently with backend without any issues
```

### 🧪**Problem 4: Backend Developer Challenge - Node.js**
**Complete CRUD Operations in Student Management**
```bash
- Location: '/react-node/backend/src/modules/students/students-controller.js'
- Issue: Implement missing CRUD operations for student management
- Skills Tested: Node.js, Express, PostgreSQL, API design, Error handling
- Expected Implementation: Full Create, Read, Update, Delete operations
```

### 🧪**Problem 5: Backend Developer Challenge - .NET**
**Complete CRUD Operations in Student Management**
```bash
- Location: '\react-.net\Backend\Controllers\StudentController.cs'
- Issue: Implement missing CRUD operations for student management
- Skills Tested: C#, .NET, PostgreSQL, API design, Error handling
- Expected Implementation: Full GET, ADD, Update, Delete operations
- After completing, check that frontend part works well concurrently with backend without any issues
```

### 🧪**Problem 6: Backend Developer Challenge - PHP**
**Complete Getting Users Operations in Student Marketplace**
```bash
- Location: '\react-php\backend\admin\get_users.php'
- Issue: Implement missing Getting Users operations for student marketplace
- Skills Tested: PHP, MySQL, API design, Error handling
- Expected Implementation: Full Getting Users operation
- After completing, check that frontend part works well concurrently with backend without any issues
```

### 🧪**Problem 7: Backend Developer Challenge - Golang**
**Build PDF Report Generation Microservice via API Integration**
```bash
- Objective: Create a standalone microservice in Go to generate PDF reports for students by consuming the existing Node.js backend API.
- Location: A new 'go-service/' directory at the root of the project.
- Description: This service will connect to the existing Node.js backend '/api/v1/students/:id' endpoint to fetch student data, and then use the returned JSON to generate a downloadable PDF report.
- Skills Tested: Golang, REST API consumption, JSON parsing, file generation, microservice integration.
- Requirements:
  - Create a new endpoint 'GET /api/v1/students/:id/report' in the Go service.
  - The Go service must not connect directly to the database; it must fetch data from the Node.js API.
  - The developer must have the PostgreSQL database and the Node.js backend running to complete this task.
- After completing, check that frontend part works well concurrently with backend without any issues
```

### 🧪**Problem 8: Blockchain Developer Challenge**
**Implement Certificate Verification System**
```bash
- Objective: Add blockchain-based certificate verification for student achievements
- Location: In '\react-node\backend\' side and write the code there

- Requirements:
  - Create smart contract for certificate issuance and verification
  - Integrate Web3 wallet connection in frontend
  - Add certificate management in admin panel
  - Implement IPFS for certificate metadata storage
  - Check that there is no issue when backend running
```

### 🧪**Problem 9: Smart Contract Developer Challenge**
**Basic User Registration Smart Contract**
```bash
- Objective: Create a Solidity contract where users can register with their address.

- Location: Create a 'test.sol' in '\react-node\backend\' side and write the code there

  The contract should:
  Allow each user to register once
  Store the user address and a username (string)
  Provide a function to retrieve the username of a registered user.
  Emit an event when a user registers.

- Requirements:  
  Use a mapping to store user data.
  Prevent multiple registrations from the same address.
  Keep the contract simple with minimal functions.

```

### 🧪**Problem 10: DevOps Engineer Challenge**
**Containerize the Full Application Stack**
```bash
- Objective: Create a multi-container setup to run the entire application stack (Frontend, Backend, Database) using Docker and Docker Compose.
- Location: 'Dockerfile' in the 'frontend' and 'backend' directories, and a 'docker-compose.yml' file at the project root.
- Description: The goal is to make the entire development environment reproducible and easy to launch with a single command. The candidate must ensure all services can communicate with each other inside the Docker network.
- Skills Tested: Docker, Docker Compose, container networking, database seeding in a container, environment variable management.
- Requirements:
  - Write a 'Dockerfile' for the 'frontend' service.
  - Write a 'Dockerfile' for the 'backend' service.
  - Create a 'docker-compose.yml' at the root to define and link the 'frontend', 'backend', and 'postgres' services.
  - The 'postgres' service must be automatically seeded with the data from the 'seed_db/' directory on its first run.
  - The entire application should be launchable with 'docker-compose up'.
```


## 🛠️ Technology Stack

### Frontend(React)
- **Framework**: React 18 + TypeScript
- **UI Library**: Material-UI (MUI) v6
- **State Management**: Redux Toolkit + RTK Query
- **Form Handling**: React Hook Form + Zod validation
- **Build Tool**: Vite
- **Code Quality**: ESLint, Prettier, Husky

### Backend(Java - Spring Boot)
- **Back-end:** Spring (Boot, Data, Security), JPA / Hibernate, PostgreSQL, Razorpay
- **Front-end:** React.js, Redux, React Router DOM, Tailwind CSS
- **Security:** JWT, Refresh Token
- **Testing:** JUnit5, AssertJ, Mockito. (given/when/then format - BDD style)
- **Deploy:** Vercel, Render, Docker

### Backend(Python - Django)
- **Django 4.1.13** - Web framework
- **Django REST Framework** - API development
- **Djongo** - Django-MongoDB integration
- **PyMongo** - MongoDB driver
- **QRCode** - QR code generation
- **Haversine** - Distance calculation
- **Geopy** - Geocoding and distance calculations

### Backend(Node.js)
- **Runtime**: Node.js
- **Framework**: Express.js
- **Database**: PostgreSQL
- **Authentication**: JWT + CSRF protection
- **Password Hashing**: Argon2
- **Email Service**: Resend API
- **Validation**: Zod

### Backend(C# - .NET)
- **Runtime**: C#
- **Framework**: .NET
- **Database**: PostgreSQL
- **Authentication**: JWT + CSRF protection
- **Password Hashing**: Argon2
- **Email Service**: Resend API
- **Validation**: Zod

### Database
- **Primary DB**: PostgreSQL
- **Schema**: Comprehensive school management schema
- **Features**: Role-based access control, Leave management, Notice system

## 📋 Features

### Core Functionality
- **Dashboard**: User statistics, notices, birthday celebrations, leave requests
- **User Management**: Multi-role system (Admin, Student, Teacher, Custom roles)
- **Academic Management**: Classes, sections, students, class teachers
- **Leave Management**: Policy definition, request submission, approval workflow
- **Notice System**: Create, approve, and distribute notices
- **Staff Management**: Employee profiles, departments, role assignments
- **Access Control**: Granular permissions system

### Security Features
- JWT-based authentication with refresh tokens
- CSRF protection
- Role-based access control (RBAC)
- Password reset and email verification
- Secure cookie handling

## 🔧 Development Guidelines

### Code Standards
- **File Naming**: kebab-case for consistency across OS
- **Import Style**: Absolute imports for cleaner code
- **Code Formatting**: Prettier with consistent configuration
- **Git Hooks**: Husky for pre-commit quality checks


## 🧪 Testing Instructions

### For Frontend Developers - React - Typescript
1. Navigate to the notices section
2. Try to create a new notice with description
3. Verify the description is saved correctly
4. Test form validation and error handling

### For Backend Developers - Java - Spring Boot
1. Test all CRUD endpoints using Postman/curl
2. Verify proper error handling and validation
3. Check database constraints and relationships
4. Test authentication and authorization

### For Backend Developers - Python - Django
1. Test Register and Signin operation using Postman/curl
2. Verify proper error handling and validation
3. Check database constraints and relationships
4. Test authentication and authorization

### For Backend Developers - Node.js
1. Test all student CRUD endpoints using Postman/curl
2. Verify proper error handling and validation
3. Check database constraints and relationships
4. Test authentication and authorization

### For Backend Developers - .NET
1. Test all student CRUD endpoints using Postman/curl
2. Verify proper error handling and validation
3. Check database constraints and relationships
4. Test authentication and authorization

### For Backend Developers - PHP
1. Complete Getting Users Operations in Student Marketplace
2. Implement Full Getting Users operation
3. Check database constraints and relationships
4. Test authentication and authorization

### For Golang Developers
1. Set up the PostgreSQL database using `seed_db/` files.
2. Set up and run the Node.js backend by following its setup instructions.
3. Run the Go service.
4. Use a tool like `curl` or Postman to make a GET request to the Go service's `/api/v1/students/:id/report` endpoint.
5. Verify that the Go service correctly calls the Node.js backend and that a PDF file is successfully generated.
6. Check the contents of the PDF for correctness.

### For Blockchain Developers
1. Set up local blockchain environment (Hardhat/Ganache)
2. Deploy certificate smart contract
3. Integrate Web3 wallet connection
4. Test certificate issuance and verification flow

### For Smart Contract Developers
1. Set up local blockchain environment (Hardhat/Ganache)
2. Deploy the certificate smart contract to the local network
3. Connect Web3 wallet (MetaMask) to the demo interface
4. Test issuance and verification of certificates through smart contract calls

### For DevOps Engineers
1. Ensure Docker and Docker Compose are installed on your machine.
2. From the project root, run the command `docker-compose up --build`.
3. Wait for all services to build and start.
4. Access the frontend at `http://localhost:5173` and verify the application is running.
5. Log in with the demo credentials to confirm that the frontend, backend, and database are all communicating correctly.

## 📚 API Documentation

### Authentication Endpoints
- `POST /api/v1/auth/login` - User login
- `POST /api/v1/auth/logout` - User logout
- `GET /api/v1/auth/refresh` - Refresh access token

### Student Management
- `GET /api/v1/students` - List all students
- `POST /api/v1/students` - Create new student
- `PUT /api/v1/students/:id` - Update student
- `DELETE /api/v1/students/:id` - Delete student

### Notice Management
- `GET /api/v1/notices` - List notices
- `POST /api/v1/notices` - Create notice
- `PUT /api/v1/notices/:id` - Update notice
- `DELETE /api/v1/notices/:id` - Delete notice

### PDF Generation Service (Go)
- `GET /api/v1/students/:id/report` - Generate and download a PDF report for a specific student.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

**Happy Coding! 🚀**