
# Installation Guide

## Prerequisites
- **Git**: To clone the repository. [Download Git](https://git-scm.com/downloads).
  - Windows: Install Git for Windows.
  - macOS: Install via Homebrew (`brew install git`) or Xcode.
  - Linux: Install via package manager (e.g., `sudo apt-get install git` for Ubuntu).
- **PHP**: Version compatible with your Laravel project (typically 8.0+). [Install PHP](https://www.php.net/downloads.php).
  - Windows: Download from php.net or use WAMP/XAMPP.
  - macOS: Install via Homebrew (`brew install php@8.1`).
  - Linux: Install via package manager (e.g., `sudo apt-get install php8.1`).
- **Composer**: For PHP dependency management. [Install Composer](https://getcomposer.org/download/).
  - Follow platform-specific instructions on the Composer website.
- **Database**: A local database server (e.g., MySQL, PostgreSQL). [Install MySQL](https://dev.mysql.com/downloads/) or [PostgreSQL](https://www.postgresql.org/download/).
  - Windows: Use MySQL Installer or XAMPP.
  - macOS: Install via Homebrew (`brew install mysql` or `brew install postgresql`).
  - Linux: Install via package manager (e.g., `sudo apt-get install mysql-server`).
- **Node.js/NPM**: Optional, for frontend assets (e.g., Laravel Mix, Vite). [Install Node.js](https://nodejs.org/).
  - Windows: Download installer from nodejs.org.
  - macOS: Install via Homebrew (`brew install node`).
  - Linux: Install via package manager (e.g., `sudo apt-get install nodejs npm`).
- **Queue Driver**: This guide uses the `database` driver by default. For Redis, install Redis locally ([Install Redis](https://redis.io/docs/install/)).
- **Terminal/Shell**:
  - Windows: Use Command Prompt, PowerShell, or Git Bash.
  - macOS: Use Terminal or iTerm2.
  - Linux: Use Terminal.

## Steps

### 1. Clone the Laravel Project from GitHub
1. Open your terminal/shell:
   - Windows: Open Command Prompt, PowerShell, or Git Bash.
   - macOS/Linux: Open Terminal.
2. Navigate to the desired directory:
   ```bash
   cd /path/to/your/projects
   ```
   - Windows example: `cd C:\Users\YourName\Projects`.
   - macOS/Linux example: `cd ~/Projects`.
3. Clone the repository:
   ```bash
   git clone https://github.com/Shadman-Ahmed-Chowdhury/minimalist-finance
   ```
   Replace `username` and `repository-name` with the appropriate GitHub username and repository name.
4. Navigate into the project directory:
   ```bash
   cd minimalist-finance
   ```

### 2. Install Composer Dependencies
1. Verify Composer is installed:
   ```bash
   composer --version
   ```
   - If not installed, follow [Composer installation instructions](https://getcomposer.org/download/) for your OS.
2. Install PHP dependencies:
   ```bash
   composer install
   ```

### 3. Set Up Environment File
1. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```
   - Windows (Command Prompt): `copy .env.example .env`.
2. Open `.env` in a text editor (e.g., VS Code, Notepad, or nano) and configure database and queue settings:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password

   QUEUE_CONNECTION=database
   ```
   - Use `database` for the queue driver (simplest). For Redis, set `QUEUE_CONNECTION=redis` and configure Redis credentials (see Redis setup below).
3. Generate an application key:
   ```bash
   php artisan key:generate
   ```

### 4. Set Up the Database
1. Ensure your database server is running:
   - Windows: Start MySQL via XAMPP/WAMP or MySQL service.
   - macOS: Start MySQL (`brew services start mysql`) or PostgreSQL (`brew services start postgresql`).
   - Linux: Start MySQL (`sudo systemctl start mysql`) or PostgreSQL (`sudo systemctl start postgresql`).
2. Create a database:
   - Access the database CLI:
     ```bash
     mysql -u your_username -p
     ```
     - Windows: Use MySQL Command Line Client or phpMyAdmin (if using XAMPP).
     - macOS/Linux: Use the terminal command above.
   - Create the database:
     ```sql
     CREATE DATABASE your_database_name;
     EXIT;
     ```
     Replace `your_database_name` with the name in `.env`.
3. Run migrations to set up the database schema, including the `jobs` table for the database queue driver:
   ```bash
   php artisan migrate
   ```
4. (Optional) Seed the database if seeders are included:
   ```bash
   php artisan db:seed
   ```

### 5. Configure and Run the Queue
1. **Verify Queue Configuration**:
   - Check `config/queue.php` for available queue connections.
   - Ensure `.env` `QUEUE_CONNECTION` matches the project’s intended driver (e.g., `database` or `redis`).
2. **Set Up Queue Table** (for `database` driver):
   - If not created by migrations, generate the `jobs` table:
     ```bash
     php artisan queue:table
     php artisan migrate
     ```
3. **Set Up Redis** (if using `redis` driver):
   - Install Redis:
     - Windows: Download from [Redis Windows port](https://github.com/microsoftarchive/redis/releases) or use WSL.
     - macOS: `brew install redis`.
     - Linux: `sudo apt-get install redis-server`.
   - Start Redis:
     - Windows: Run `redis-server.exe` from the installation directory.
     - macOS: `brew services start redis`.
     - Linux: `sudo systemctl start redis`.
   - Install PHP Redis extension:
     - Windows: Add `php_redis.dll` to PHP extensions (consult XAMPP/WAMP docs).
     - macOS: `brew install php-redis` or `pecl install redis`.
     - Linux: `sudo apt-get install php-redis`.
   - Update `.env`:
     ```env
     QUEUE_CONNECTION=redis
     REDIS_HOST=127.0.0.1
     REDIS_PORT=6379
     REDIS_PASSWORD=null
     ```
4. **Start the Queue Worker**:
   - Run the queue worker:
     ```bash
     php artisan queue:work
     ```
     - This processes jobs in the default queue. Stop with `Ctrl+C`.
     - For a specific queue, use:
       ```bash
       php artisan queue:work --queue=queue_name
       ```
       Check project documentation for queue names.
5. **Run Queue in Background** (Optional):
   - Use a process manager or separate terminal.
   - **Supervisor** (macOS/Linux):
     - Install:
       - macOS: `brew install supervisor`.
       - Linux: `sudo apt-get install supervisor`.
     - Create a configuration file (e.g., `/etc/supervisor/conf.d/laravel-worker.conf`):
       ```ini
       [program:laravel-worker]
       process_name=%(program_name)s_%(process_num)02d
       command=php /path/to/your/projects/repository-name/artisan queue:work --sleep=3 --tries=3
       autostart=true
       autorestart=true
       user=your_username
       numprocs=1
       redirect_stderr=true
       stdout_logfile=/path/to/your/projects/repository-name/worker.log
       ```
     - Update Supervisor:
       ```bash
       sudo supervisorctl reread
       sudo supervisorctl update
       sudo supervisorctl start laravel-worker:*
       ```
     - Replace paths and `your_username` as needed.
   - **Windows**: Use Task Scheduler or run `php artisan queue:work` in a separate Command Prompt/PowerShell window.

### 6. Install Frontend Dependencies (Optional)
If the project uses frontend assets:
1. Verify Node.js and NPM:
   ```bash
   node --version
   npm --version
   ```
2. Install dependencies:
   ```bash
   npm install
   ```
3. Compile assets:
   ```bash
   npm run dev
   ```
   - Use `npm run build` for production.

### 7. Run the Application
1. Start the Laravel development server:
   ```bash
   php artisan serve
   ```
   - Runs on `http://localhost:8000`. Use `--port=8080` for a different port.
2. Open a browser and visit:
   ```
   http://localhost:8000
   ```
   - Check `.env` (`APP_URL`) for custom settings.
3. Test queue functionality by triggering a job (e.g., sending an email).

### 8. Useful Commands
- Run Artisan commands:
  ```bash
  php artisan <command>
  ```
- Clear cache:
  ```bash
  php artisan cache:clear
  php artisan config:clear
  ```
- Retry failed jobs:
  ```bash
  php artisan queue:retry all
  ```
- Clear failed jobs:
  ```bash
  php artisan queue:flush
  ```
- List failed jobs:
  ```bash
  php artisan queue:failed
  ```
- Refresh migrations:
  ```bash
  php artisan migrate:fresh
  ```

## Troubleshooting
- **PHP version mismatch**: Check `composer.json` for required PHP version.
- **Database errors**: Verify `.env` database settings.
- **Queue not processing**: Ensure `php artisan queue:work` is running and `QUEUE_CONNECTION` is correct.
- **Failed jobs**: Check `failed_jobs` table or logs:
  ```bash
  php artisan queue:failed
  ```
- **Missing extensions**: Install PHP extensions (e.g., `php-mysql`, `php-redis`).
  - Windows: Edit `php.ini` to enable extensions.
  - macOS/Linux: Use package manager or PECL.
- **Permissions**: Ensure `storage` and `bootstrap/cache` are writable:
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```
  - Windows: Adjust permissions via File Explorer or `icacls`.
- **Frontend errors**: Run `npm install` and check `README.md`.

## Notes
- Update `.env` if the project expects Sail’s Docker services.
- For production, use a web server (e.g., Apache, Nginx) and a robust queue setup.
- Redis requires additional setup but is faster for queues.

For more details, see [Laravel documentation](https://laravel.com/docs/installation) and [Laravel Queue documentation](https://laravel.com/docs/queues).
