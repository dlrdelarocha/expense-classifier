
# Expense Management Project

This project aims to provide a comprehensive solution for managing expenses. It consists of two main components:


The backend API is developed using Laravel framework, PostgreSQL as the database management system and Redis for caching and queue process. Docker is utilized for containerization, ensuring seamless deployment and scalability. 

The API offers various endpoints to handle expense-related operations, providing  functionality for managing expenses.

The frontend is built with Vue3 framework, along with Vuex for state management and Tailwind CSS for styling, offering seamless integration with the backend API. 



# Installation

To get started with the Expense Management Project, follow these steps:

1.- Clone the repository:
```bash
    git clone https://github.com/your/expense-classifier.git
```

2.- Navigate to the project directory:
```bash
   cd expense-classifier
```

3.- Set up environment variables:
- Create a copy of the .env.example file and rename it to .env.

4.- Install dependencies:
```bash
   npm install
```

5.- Start Docker:
   - Make sure you have Docker and Docker Compose installed on your system.
   - Start Docker before proceeding to the next steps.

6.- Start the Docker containers:
```bash
   docker-compose build
```
```bash
   docker-compose up
```

7.- Install composer dependencies:
```bash
   docker-compose exec app composer install
```

8.- Run migrations and seed data:
```bash
   docker-compose exec app php artisan migrate --seed
```
9.- Compile frontend assets (outside the container):
```bash
   npm run dev
```

## API Reference, available endpoints

### URL Base   
```bash
   http://localhost:8081/api
```

### Endpoint Headers. (Mandatory)    

| Headers | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `Accept`  | `string` | application/json           |

### The following headers are mandatory for all endpoints requiring authentication.    

| Headers | Type     | Description                  |
| :-------- | :------- | :------------------------- |
| `Bearer`   | `string` | **Required**. Your API key |
| `Accept`  | `string` | application/json           |

## Authentication

#### Register

```http
POST /api/register
```

| Body Parameter          | Type     | Required                  |
| :---------------------- | :------- | :--------------------------- |
| `email`                 | `string` | **True**|
| `name`                  | `string` | **True**|
| `password`              | `string` | **True**|
| `password_confirmation` | `string` | **True**|


#### Login

```http
POST /api/login
```

| Parameter   | Type     | Required                |
| :---------- | :------- | :------------------------- |
| `email`     | `string` | **True**.|
| `password`  | `string` | **True**.|

#### Logout

```http
POST /api/logout
```

## Categories

#### Create category

```http
POST /api/categories
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name`      | `string` | **Required** |

#### Get categories

```http
GET /api/categories
```

#### Update category

```http
PUT /api/categories/{categoryId}
```

| Parameter | Type       | Required                 |
| :-------- | :------- | :------------------------- |
| `categoryId`| `string` | **True** |
| `name`      | `string` | **True** |


#### Destroy category

```http
DELETE /api/categories/{categoryId}
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `categoryId`| `string` | **Required**             |


## Expenses


#### Create expense

```http
POST /api/expenses/
```

| Parameter   | Type     | Description                   |
| :---------- | :------- | :---------------------------- |           
| `name`        | `string` | **True**|
| `category_id` | `string` | **True**|
| `spent_at`    | `string` | **True**|
| `amount`      | `string` | **True**|

#### List expenses

```http
GET /api/expenses
```

#### Update expense

```http
PUT /api/expenses/{expenseId}
```

| Parameter   | Type     | Required                   |
| :---------- | :------- | :---------------------------- |           
| `expenseId`   | `string` | false |
| `name`        | `string` | false|
| `category_id` | `string` | false|
| `spent_at`    | `string` | false|
| `amount`      | `string` | false|

#### Destroy expense

```http
DELETE /api/expenses/{expenseId}
```

| Parameter   | Type     | Required                   |
| :---------- | :------- | :---------------------------- |
| `expenseId` | `string` | **true**                      |



#### Import expenses

```http
POST /api/expenses/upload
```
Headers 

| Headers   | Type     | Description                   |
| :---------- | :------- | :---------------------------- |
| `Content-Type` | `string` |  `multipart/form-data`     |

 Params 

| Parameter   | Type     | Required                   |
| :---------- | :------- | :---------------------------- |
| `file` | `xlsx,csv (binary)`   |   **true**     |

#### Testing
For testing the API endpoints, you can use the Postman collection available in the project's public folder. 
[Download Postman Collection](https://github.com/dlrdelarocha/expense-classifier/blob/main/public/shared/Expenses.postman_collection.json)
