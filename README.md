# Minimalist Finance
Minimalist Finace is a financial management system that helps users track their income, expenses, and loans efficiently. It provides a dashboard overview of all accounts, allowing users to manage transactions, monitor loan repayments, and receive automated email reminders for due payments. The app will initially focus on core functionalities before integrating Redis for caching and RabbitMQ for background processing, optimizing performance for real-time financial tracking

## Implementation Steps:

### Step 1: Project Setup & Authentication
- Install Laravel & Set Up Database (MySQL/PostgreSQL)
- Install Laravel Breeze (For Authentication)
- Configure User Authentication
    - Login, Registration, Logout
    - Email Verification
    - Password Reset
- Implement Role-Based Middleware (Admin/User)
- Test Authentication Flow with Laravel Breeze UI

### Step 2: Accounts & Transactions
- Accounts Management
    - Create accounts table (id, name, type, balance, user_id, created_at, updated_at)
    - Implement CRUD operations for accounts
    - Set up API endpoints for managing accounts
    - Validate user permissions (Users can only manage their own accounts)

- Transactions Management
    - Create transactions table (id, account_id, type [income/expense], amount, category, description, date)
    - Implement CRUD operations for transactions
    - Validate transactions (e.g., Expense shouldn’t exceed account balance)
    - Implement category-based filtering
    - Implement date-range filtering

### Step 3: Loan Management
- Create loans table (id, user_id, amount, interest_rate, due_date, status)
- Implement CRUD operations for loan management
- Create loan_repayments table (id, loan_id, amount_paid, payment_date)
- Implement logic for calculating remaining loan balance
- Implement API to fetch due loans

### Step 4: Reports & Email Notifications
- Implement API to generate income vs expense reports
- Implement API to generate monthly financial summary
- Implement API for loan due reminders
- Implement Laravel Mail for sending loan due email reminders
- Implement Laravel Scheduler to send automated email reminders daily

### Step 5: Optimization with Redis & RabbitMQ (Bonus Point)
✅ Once the core functionalities work, integrate Redis and RabbitMQ for optimization:

#### Using Redis
- Cache Dashboard Data (Total balance, expenses, income)
- Cache Frequently Queried Data (Account balances, recent transactions)
- Use Redis for Rate Limiting on API requests

#### Using RabbitMQ
- Queue Loan Due Reminder Emails instead of sending them synchronously
- Queue Large Report Generation Tasks to avoid slowing down the application
- Queue Loan Interest Calculations to run in the background

