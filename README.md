### Laravel Developer Test Task

You are provided with a small Laravel application that displays a list of products and individual product details. Additionally, the application includes an admin interface for editing products, or alternatively, products can be edited using a command-line command.

This document outlines the recent code refactoring and improvements made to enhance the structure, performance, and maintainability of the Laravel project.

---

## 🚀 Getting Started

To run the project locally, use the following commands:

```bash
# Install dependencies
composer install
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start the development servers
php artisan queue:work & php artisan serve
```

## 🛠️ Improvements and Refactoring

### 🔄 Command Bus Pattern Implementation
The product update functionality has been refactored using the Command Bus pattern to improve separation of concerns and maintainability. The following components were added:

1. **Command Class**
   - Location: `app/Commands/UpdateProductCommand.php`
   - Purpose: Represents the update product operation as a command object
   - Contains: Product ID and optional fields (name, description, price)

2. **Command Handler**
   - Location: `app/Handlers/UpdateProductCommandHandler.php`
   - Purpose: Contains the business logic for updating a product
   - Features:
     - Input validation
     - Price change detection
     - Price change notifications

3. **Command Bus**
   - Location: `app/Services/CommandBus.php`
   - Purpose: Handles command dispatching and error handling
   - Features:
     - Automatic handler resolution
     - Comprehensive error handling
     - Detailed logging

4. **Service Provider**
   - Location: `app/bootstrap/providers.php`
   - Purpose: Registers the command bus in the service container

### ✅ Routes & Controllers
- Created resource routes using `Route::resource` for clean and RESTful API architecture
- Developed resource controllers to handle CRUD operations in a structured manner
- Implemented proper route naming conventions and grouping

### ✅ Queued Email Sending
- Converted all email sending processes to use Laravel queues
- Improved application responsiveness and performance
- Implemented proper error handling for failed jobs

### ✅ Service Layer
Created dedicated Service classes for:
- **Exchange Rate Handling**
  - Location: `app/Services/ExchangeRateService.php`
  - Purpose: Manages currency conversion and rate updates
- **Image Upload Handling**
  - Location: `app/Services/ImageUploadService.php`
  - Purpose: Handles image uploads, storage, and deletion

### ✅ Image Handling
- Utilized Laravel Storage system for storing and retrieving uploaded images
- Implemented proper file validation and error handling
- Added support for different storage drivers

### ✅ Model Upgrades
Updated all models to follow Laravel's latest standards:
- Proper use of `$fillable`, `$casts`, and relationships
- Consistent naming conventions
- Separation of concerns

### ✅ UI Bug Fixes
- Resolved bugs related to the login page
- Improved error handling and user experience
- Enhanced form validation feedback

### ✅ Folder Structure Improvements
- Restructured folders for better organization and modularity
- Aligned with best practices for scalable Laravel applications
- Separated concerns into appropriate directories

### ✅ Request Validation
- Created custom Request files for validating inputs
- Kept controllers clean and focused
- Implemented proper validation rules and messages

### ✅ Exception Handling
- Added try-catch blocks throughout the application
- Enhanced error handling and prevention of crashes
- Implemented proper logging for debugging

## 📝 Usage Examples

### Command Line Interface
```bash
# Update product name
php artisan product:update 1 --name="New Product Name"

# Update product price
php artisan product:update 1 --price=99.99

# Update multiple fields
php artisan product:update 1 --name="New Name" --description="New Description" --price=99.99
```

## 🔒 Security Considerations
- Input validation and sanitization
- CSRF protection
- SQL injection protection
- Proper authentication and authorization

## 📈 Future Improvements
- Implementation of GraphQL API
- Microservices architecture adoption
- Serverless deployment options
- AI/ML integration for product recommendations
- Progressive Web App (PWA) features
- Augmented Reality product visualization
- Advanced analytics dashboard
- Automated deployment pipeline

## 🛡️ Security Enhancements (future)
- OAuth2 implementation
- Two-factor authentication
- IP-based access control
- Security scanning integration
- Automated security updates
- Penetration testing setup
- Security headers configuration
- Content Security Policy (CSP)
- SQL injection prevention

## 🔄 Domain Driven Design (DDD) Considerations
For complex or enterprise-level applications:
- Domain layer separation
- Bounded contexts
- Aggregates and value objects
- Repository pattern implementation