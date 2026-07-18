aqlam-mural/
│
├── 📄 composer.json                              ✅ Dependencies
├── 📄 .env.example                              ✅ Environment template
├── 📄 README.md                                 ✅ Project documentation
├── 📄 IMPLEMENTATION_GUIDE.md                   ✅ Development guide
│
├── 📁 app/
│   ├── 📁 Constants/
│   │   └── AppConstants.php                     ✅ App constants & enums
│   │
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/
│   │   │   ├── AuthController.php               ✅ Authentication
│   │   │   └── CatalogController.php            ✅ Catalog browsing
│   │   │
│   │   └── 📁 Middleware/
│   │       └── AdminMiddleware.php              ✅ Admin authorization
│   │
│   ├── 📁 Models/
│   │   ├── User.php                             ✅ User model
│   │   ├── Category.php                         ✅ Category model
│   │   ├── Design.php                           ✅ Design model
│   │   ├── Order.php                            ✅ Order model
│   │   ├── OrderItem.php                        ✅ OrderItem model
│   │   ├── CustomOrder.php                      ✅ CustomOrder model
│   │   ├── CustomOrderFile.php                  ✅ CustomOrderFile model
│   │   ├── Payment.php                          ✅ Payment model
│   │   ├── OrderResult.php                      ✅ OrderResult model
│   │   ├── CustomerCredit.php                   ✅ CustomerCredit model
│   │   └── Notification.php                     ✅ Notification model
│   │
│   └── 📁 Services/
│       ├── OrderService.php                     ⏳ To be created
│       ├── PaymentService.php                   ⏳ To be created
│       ├── NotificationService.php              ⏳ To be created
│       ├── FileUploadService.php                ⏳ To be created
│       └── WhatsAppService.php                  ⏳ To be created
│
├── 📁 database/
│   ├── 📁 migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php                     ✅
│   │   ├── 2024_01_01_000002_create_categories_table.php                ✅
│   │   ├── 2024_01_01_000003_create_designs_table.php                   ✅
│   │   ├── 2024_01_01_000004_create_orders_table.php                    ✅
│   │   ├── 2024_01_01_000005_create_order_items_table.php               ✅
│   │   ├── 2024_01_01_000006_create_custom_orders_table.php             ✅
│   │   ├── 2024_01_01_000007_create_custom_order_files_table.php        ✅
│   │   ├── 2024_01_01_000008_create_payments_table.php                  ✅
│   │   ├── 2024_01_01_000009_create_order_results_table.php             ✅
│   │   ├── 2024_01_01_000010_create_customer_credits_table.php          ✅
│   │   └── 2024_01_01_000012_create_notifications_table.php             ✅
│   │
│   ├── 📁 factories/
│   │   ├── UserFactory.php                      ⏳ To be created
│   │   ├── DesignFactory.php                    ⏳ To be created
│   │   └── OrderFactory.php                     ⏳ To be created
│   │
│   └── 📁 seeders/
│       ├── DatabaseSeeder.php                   ⏳ To be created
│       ├── UserSeeder.php                       ⏳ To be created
│       ├── CategorySeeder.php                   ⏳ To be created
│       └── DesignSeeder.php                     ⏳ To be created
│
├── 📁 routes/
│   ├── api.php                                  ✅ API routes
│   ├── web.php                                  ⏳ Web routes (optional)
│   └── auth.php                                 ⏳ Auth routes (optional)
│
├── 📁 storage/
│   ├── 📁 uploads/
│   │   ├── designs/                             ⏳ Design images
│   │   ├── custom-referensi/                    ⏳ Custom order files
│   │   └── hasil/                               ⏳ Final results
│   │
│   └── 📁 logs/
│       └── laravel.log                          ⏳ Log file
│
├── 📁 config/
│   ├── app.php                                  ⏳ App config
│   ├── database.php                             ⏳ Database config
│   ├── jwt.php                                  ⏳ JWT config
│   ├── midtrans.php                             ⏳ Midtrans config
│   └── filesystems.php                          ⏳ File storage config
│
├── 📁 public/
│   ├── index.php                                ⏳ Entry point
│   └── .htaccess                                ⏳ Apache rules
│
└── 📁 tests/
    ├── Feature/
    │   ├── AuthTest.php                         ⏳ To be created
    │   ├── OrderTest.php                        ⏳ To be created
    │   └── PaymentTest.php                      ⏳ To be created
    │
    └── Unit/
        ├── Models/
        │   └── OrderTest.php                    ⏳ To be created
        │
        └── Services/
            └── OrderServiceTest.php             ⏳ To be created

User test:
Admin: admin@aqlamMural.com / password
Customer: pelanggan@example.com / password
