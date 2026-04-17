# The Shoe Vault - E-commerce Website

A complete e-commerce website for an online shoes shop built with PHP, MySQL, HTML, CSS, and JavaScript. This is a college project demonstrating full-stack web development skills.

## Features

### Customer Features

- 🏠 **Home Page** - Featured products and categories
- 👤 **User Registration & Login** - Secure authentication system
- 🛍️ **Product Browsing** - View products by category
- 🔍 **Product Search** - Search products by name
- 📦 **Product Details** - Detailed product information
- 🛒 **Shopping Cart** - Add, update, remove items
- 💳 **Checkout** - Place orders with COD
- 📋 **Order History** - View past orders
- 📱 **Responsive Design** - Mobile-friendly interface

### Admin Features

- 📊 **Dashboard** - Statistics and overview
- 📂 **Category Management** - Add, edit, delete categories
- 📦 **Product Management** - Full CRUD operations for products
- 🛍️ **Order Management** - View and update order status
- 👥 **Customer Management** - View customer information
- 📈 **Sales Reports** - Revenue and order statistics

## Technology Stack

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Server:** XAMPP (Apache + MySQL)

## Project Structure

```
ecomm/
├── admin/                  # Admin panel pages
│   ├── dashboard.php       # Admin dashboard
│   ├── categories.php      # Category management
│   ├── products.php        # Product management
│   ├── orders.php          # Order management
│   └── customers.php       # Customer listing
├── assets/                 # Static assets
│   ├── css/
│   │   └── style.css       # Main stylesheet
│   ├── js/
│   │   └── main.js         # JavaScript functionality
│   └── images/             # Site images
├── includes/               # Reusable PHP components
│   ├── config.php          # Database & configuration
│   ├── header.php          # Common header
│   └── footer.php          # Common footer
├── uploads/                # Product images upload folder
├── index.php               # Home page
├── login.php               # Login page
├── register.php            # Registration page
├── logout.php              # Logout handler
├── products.php            # Products listing
├── product-detail.php      # Product details
├── cart.php                # Shopping cart
├── checkout.php            # Checkout page
├── order-success.php       # Order confirmation
├── orders.php              # Customer orders
├── database.sql            # Database schema & sample data
└── README.md               # This file
```

## Installation & Setup

### Prerequisites

- XAMPP (or similar Apache + MySQL + PHP environment)
- Web browser (Chrome, Firefox, Edge, etc.)
- Text editor (VS Code recommended)

### Step-by-Step Setup

#### 1. Install XAMPP

- Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
- Install XAMPP in `C:\xampp` (Windows) or `/opt/lampp` (Linux)
- Start Apache and MySQL services from XAMPP Control Panel

#### 2. Clone/Copy Project Files

```bash
# Copy the project folder to XAMPP htdocs
# Windows: C:\xampp\htdocs\ecomm
# Linux: /opt/lampp/htdocs/ecomm
```

#### 3. Create Database

1. Open phpMyAdmin: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Click on "New" to create a new database
3. Name it: `shoes_shop`
4. Set collation: `utf8mb4_unicode_ci`
5. Click "Create"

#### 4. Import Database

1. Select the `shoes_shop` database
2. Click on "Import" tab
3. Click "Choose File" and select `database.sql` from the project folder
4. Click "Go" to import
5. Wait for success message

#### 5. Configure Database Connection

Edit `includes/config.php` if needed (default settings work with XAMPP):

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');           // Empty password for XAMPP
define('DB_NAME', 'shoes_shop');
```

#### 6. Set Permissions (Linux/Mac only)

```bash
chmod -R 755 /opt/lampp/htdocs/ecomm
chmod -R 777 /opt/lampp/htdocs/ecomm/uploads
```

#### 7. Access the Website

Open your web browser and navigate to:

```
http://localhost/ecomm
```

## Demo Accounts

### Admin Account

- **Email:** admin@theshoevault.com
- **Password:** admin123
- **Access:** Full admin panel access

### Customer Accounts

- **Email:** john@example.com
- **Password:** customer123

- **Email:** jane@example.com
- **Password:** customer123

## Database Schema

### Tables

- **users** - User accounts (admin & customers)
- **categories** - Product categories
- **products** - Product information
- **cart** - Shopping cart items
- **orders** - Order information
- **order_items** - Order line items

### Sample Data Included

- 1 Admin user
- 2 Customer users
- 5 Product categories
- 15 Sample products
- 2 Sample orders with items

## Features Breakdown

### Authentication System

- Secure password hashing with bcrypt
- Session-based login
- Role-based access control (Admin/Customer)
- Registration with validation

### Product Management

- Category-wise organization
- Product images
- Stock management
- Featured products
- Search functionality

### Shopping Cart

- Session-based cart
- Add/Update/Remove items
- Stock validation
- Real-time total calculation

### Order Processing

- Customer information collection
- Order placement with transaction
- Stock deduction
- Order status tracking
- Order history

### Admin Panel

- Statistics dashboard
- CRUD operations for categories
- CRUD operations for products
- Order management with status updates
- Customer listing

## Security Features

- ✅ Password hashing with bcrypt
- ✅ SQL injection prevention (parameterized queries)
- ✅ XSS protection (input sanitization)
- ✅ Session management
- ✅ Role-based access control
- ✅ Input validation (client & server-side)

## Responsive Design

The website is fully responsive and works on:

- 💻 Desktop (1200px+)
- 💻 Laptop (992px - 1199px)
- 📱 Tablet (768px - 991px)
- 📱 Mobile (< 768px)

## Browser Compatibility

Tested and working on:

- Chrome 90+
- Firefox 88+
- Edge 90+
- Safari 14+

## Troubleshooting

### Common Issues

**1. Database connection error**

- Verify XAMPP MySQL is running
- Check database name in `includes/config.php`
- Ensure database is imported correctly

**2. Images not displaying**

- Check if `uploads` folder exists
- Verify folder permissions (777 on Linux/Mac)
- Check image paths in database

**3. Login not working**

- Clear browser cache and cookies
- Verify user exists in database
- Check password hash in database

**4. Page not found (404)**

- Verify Apache is running in XAMPP
- Check project is in correct htdocs folder
- Verify .htaccess if using URL rewriting

**5. Cart not updating**

- Enable JavaScript in browser
- Check browser console for errors
- Clear browser cache

## Development Notes

### Adding New Products

1. Login as admin
2. Navigate to Admin Panel → Products
3. Fill in product details
4. Upload product image
5. Select category
6. Set stock quantity
7. Click "Add Product"

### Managing Orders

1. Login as admin
2. Navigate to Admin Panel → Orders
3. Click "View Details" on any order
4. Update order status from dropdown
5. Click "Update"

### Adding Categories

1. Login as admin
2. Navigate to Admin Panel → Categories
3. Enter category details
4. Upload category image (optional)
5. Click "Add Category"

## Future Enhancements

Possible improvements for future versions:

- [ ] Product reviews and ratings
- [ ] Wishlist functionality
- [ ] Advanced search with filters
- [ ] Email notifications
- [ ] Payment gateway integration
- [ ] Inventory alerts
- [ ] Sales analytics
- [ ] Discount/Coupon system
- [ ] Product variants (size, color)
- [ ] Multiple product images
- [ ] PDF invoice generation
- [ ] Export data to Excel/CSV

## Credits

**Developer:** [Your Name]  
**Project Type:** College Project  
**Course:** [Your Course Name]  
**Year:** 2024

## License

This is a college project for educational purposes. Feel free to use and modify as needed for learning.

## Support

For any issues or questions:

- Check the troubleshooting section above
- Review the code comments
- Verify XAMPP is properly configured
- Ensure all files are in correct locations

## Screenshots

### Home Page

- Featured products display
- Category navigation
- Responsive layout

### Admin Panel

- Dashboard with statistics
- Product management
- Order processing

### Shopping Experience

- Product browsing
- Cart management
- Checkout process

---

**Note:** This is a demonstration project for educational purposes. For production use, additional security measures, testing, and optimizations would be recommended.

## Quick Start Commands

```bash
# Start XAMPP services
sudo /opt/lampp/lampp start    # Linux/Mac
# Or use XAMPP Control Panel on Windows

# Access the application
http://localhost/ecomm

# Access admin panel
http://localhost/ecomm/admin/dashboard.php

# Access phpMyAdmin
http://localhost/phpmyadmin
```

---

**Developed with ❤️ for learning purposes**
