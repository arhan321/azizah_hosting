# Aqlam Mural API Documentation

**Base URL:** `http://127.0.0.1:8000/api`

---

## Authentication Routes

### Register
- **Method:** `POST`
- **Path:** `/auth/register`
- **Auth:** None
- **Body:**
  ```json
  {
    "name": "",
    "email": "",
    "password": "",
    "password_confirmation": ""
  }
  ```

### Login
- **Method:** `POST`
- **Path:** `/auth/login`
- **Auth:** None
- **Body:**
  ```json
  {
    "email": "",
    "password": ""
  }
  ```

### Get Current User
- **Method:** `GET`
- **Path:** `/auth/me`
- **Auth:** Bearer Token (Required)

### Logout
- **Method:** `POST`
- **Path:** `/auth/logout`
- **Auth:** Bearer Token (Required)

### Refresh Token
- **Method:** `POST`
- **Path:** `/auth/refresh`
- **Auth:** Bearer Token (Required)

---

## Catalog Routes (Public)

### Get Categories
- **Method:** `GET`
- **Path:** `/catalog/categories`
- **Auth:** None

### Get All Designs
- **Method:** `GET`
- **Path:** `/catalog/designs`
- **Auth:** None
- **Query Params:**
  - `category_id` (optional)
  - `page` (default: 1)
  - `per_page` (default: 15)

### Get Design Detail
- **Method:** `GET`
- **Path:** `/catalog/designs/{id}`
- **Auth:** None

### Get Top Designs
- **Method:** `GET`
- **Path:** `/catalog/top-designs`
- **Auth:** None

---

## Order Routes (Protected)

### Create Catalog Order
- **Method:** `POST`
- **Path:** `/orders`
- **Auth:** Bearer Token (Required)
- **Body:**
  ```json
  {
    "design_id": 1,
    "payment_method": "midtrans"
  }
  ```

### List User Orders
- **Method:** `GET`
- **Path:** `/orders`
- **Auth:** Bearer Token (Required)
- **Query Params:**
  - `page` (default: 1)
  - `per_page` (default: 10)

### Get Order Stats
- **Method:** `GET`
- **Path:** `/orders/stats/summary`
- **Auth:** Bearer Token (Required)

### Get Order Detail
- **Method:** `GET`
- **Path:** `/orders/{id}`
- **Auth:** Bearer Token (Required)

### Get Amount Due
- **Method:** `GET`
- **Path:** `/orders/{id}/amount-due`
- **Auth:** Bearer Token (Required)

### Cancel Order
- **Method:** `DELETE`
- **Path:** `/orders/{id}`
- **Auth:** Bearer Token (Required)

---

## Custom Order Routes (Protected)

### Submit Custom Order
- **Method:** `POST`
- **Path:** `/custom-orders`
- **Auth:** Bearer Token (Required)
- **Body:**
  ```json
  {
    "name": "Custom Design Request",
    "description": "Detailed description of the design",
    "dimensions": "A4",
    "color_preference": "Full Color",
    "deadline": "2026-05-01",
    "brief": "Design brief details"
  }
  ```

### List User Custom Orders
- **Method:** `GET`
- **Path:** `/custom-orders`
- **Auth:** Bearer Token (Required)
- **Query Params:**
  - `page` (default: 1)

### Get Custom Order Detail
- **Method:** `GET`
- **Path:** `/custom-orders/{id}`
- **Auth:** Bearer Token (Required)

### Check Custom Order Status
- **Method:** `GET`
- **Path:** `/custom-orders/{id}/status`
- **Auth:** Bearer Token (Required)

### Get Custom Order Quote
- **Method:** `GET`
- **Path:** `/custom-orders/{id}/quote`
- **Auth:** Bearer Token (Required)

### Upload Custom Order Files
- **Method:** `POST`
- **Path:** `/custom-orders/{id}/upload-files`
- **Auth:** Bearer Token (Required)
- **Body:** Form-data with `files` (multipart file upload)

---

## Admin Routes (Protected - Admin Only)

### Get Dashboard Stats
- **Method:** `GET`
- **Path:** `/admin/dashboard`
- **Auth:** Bearer Token (Admin Required)

### List All Orders
- **Method:** `GET`
- **Path:** `/admin/orders`
- **Auth:** Bearer Token (Admin Required)
- **Query Params:**
  - `page` (default: 1)
  - `status` (optional)

### Get Order Detail
- **Method:** `GET`
- **Path:** `/admin/orders/{id}`
- **Auth:** Bearer Token (Admin Required)

### Update Order Status
- **Method:** `PUT`
- **Path:** `/admin/orders/{id}/status`
- **Auth:** Bearer Token (Admin Required)
- **Body:**
  ```json
  {
    "status": "dikerjakan"
  }
  ```
- **Status Options:** `pending`, `approved`, `dikerjakan`, `selesai`

### Upload Order Result
- **Method:** `POST`
- **Path:** `/admin/orders/{id}/upload-result`
- **Auth:** Bearer Token (Admin Required)
- **Body:** Form-data with `file` (single file upload)

### List All Custom Orders
- **Method:** `GET`
- **Path:** `/admin/custom-orders`
- **Auth:** Bearer Token (Admin Required)

### Get Pending Custom Orders
- **Method:** `GET`
- **Path:** `/admin/custom-orders/pending`
- **Auth:** Bearer Token (Admin Required)

### Approve Custom Order
- **Method:** `PUT`
- **Path:** `/admin/custom-orders/{id}/approve`
- **Auth:** Bearer Token (Admin Required)
- **Body:**
  ```json
  {
    "quote_price": 500000,
    "notes": "Approved quote"
  }
  ```

### Reject Custom Order
- **Method:** `PUT`
- **Path:** `/admin/custom-orders/{id}/reject`
- **Auth:** Bearer Token (Admin Required)
- **Body:**
  ```json
  {
    "reason": "Rejection reason"
  }
  ```

---

## Webhook Routes

### Midtrans Callback
- **Method:** `POST`
- **Path:** `/webhooks/midtrans`
- **Auth:** None
- **Body:**
  ```json
  {
    "transaction_time": "2026-04-06 12:00:00",
    "transaction_status": "settlement",
    "order_id": "",
    "payment_type": "credit_card"
  }
  ```

---

## Summary

| Category | Count | Auth |
|----------|-------|------|
| Authentication | 5 | Bearer Token |
| Catalog (Public) | 4 | None |
| Orders | 6 | Bearer Token |
| Custom Orders | 6 | Bearer Token |
| Admin | 10 | Bearer Token + Admin |
| Webhooks | 1 | None |
| **Total** | **32** | - |

