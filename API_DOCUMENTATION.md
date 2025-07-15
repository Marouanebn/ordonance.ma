# API Documentation - Ordonance.ma

## Base URL
```
http://localhost:8000/api
```

## Authentication

### Register
**POST** `/register`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "patient"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "roles": [...]
        },
        "token": "1|abc123..."
    }
}
```

### Login
**POST** `/login`

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "roles": [...]
        },
        "token": "1|abc123..."
    }
}
```

### Logout
**POST** `/logout`

**Headers:**
```
Authorization: Bearer {token}
```

## User Roles

### 1. Patient
- `GET` `/patient/profile` - Get patient profile
- `PUT` `/patient/profile` - Update patient profile
- `GET` `/patient/ordonnances` - Get patient's prescriptions

### 2. Medecin (Doctor)
- `GET` `/medecin/profile` - Get doctor profile
- `PUT` `/medecin/profile` - Update doctor profile
- `GET` `/medecin/patients` - Get doctor's patients
- `POST` `/medecin/ordonnances` - Create prescription
- `GET` `/medecin/ordonnances` - Get doctor's prescriptions
- `PUT` `/medecin/ordonnances/{id}` - Update prescription
- `DELETE` `/medecin/ordonnances/{id}` - Delete prescription

### 3. Pharmacien (Pharmacist)
- `GET` `/pharmacien/profile` - Get pharmacist profile
- `PUT` `/pharmacien/profile` - Update pharmacist profile
- `GET` `/pharmacien/medicaments` - Get available medications
- `POST` `/pharmacien/demandes-laboratoire` - Create lab request
- `GET` `/pharmacien/demandes-laboratoire` - Get lab requests

### 4. Laboratoire (Laboratory)
- `GET` `/laboratoire/profile` - Get laboratory profile
- `PUT` `/laboratoire/profile` - Update laboratory profile
- `GET` `/laboratoire/demandes` - Get lab requests
- `PUT` `/laboratoire/demandes/{id}` - Update request status

### 5. Admin
- `GET` `/admin/users` - Get all users
- `GET` `/admin/statistics` - Get system statistics

## Available Roles
- `patient`
- `medecin`
- `pharmacien`
- `laboratoire`
- `admin`

## Permissions
Each role has specific permissions:

### Patient Permissions
- `view_own_profile`
- `update_own_profile`
- `view_own_ordonnances`

### Medecin Permissions
- `view_medecin_profile`
- `update_medecin_profile`
- `view_patients`
- `create_ordonnance`
- `update_ordonnance`
- `delete_ordonnance`
- `view_ordonnances`

### Pharmacien Permissions
- `view_pharmacien_profile`
- `update_pharmacien_profile`
- `view_medicaments`
- `create_demande_laboratoire`
- `view_demandes_laboratoire`

### Laboratoire Permissions
- `view_laboratoire_profile`
- `update_laboratoire_profile`
- `view_laboratoire_demandes`
- `update_demande_status`

### Admin Permissions
- All permissions

## Error Responses

### Validation Error (422)
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

### Unauthorized (401)
```json
{
    "status": "error",
    "message": "Invalid credentials"
}
```

### Not Found (404)
```json
{
    "status": "error",
    "message": "Resource not found"
}
```

## Setup Instructions

1. Install dependencies:
```bash
composer install
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Generate application key:
```bash
php artisan key:generate
```

4. Configure database in `.env` file

5. Run migrations:
```bash
php artisan migrate
```

6. Seed the database:
```bash
php artisan db:seed
```

7. Start the server:
```bash
php artisan serve
```

## Default Admin User
- Email: `admin@ordonance.ma`
- Password: `password`
- Role: `admin` 
