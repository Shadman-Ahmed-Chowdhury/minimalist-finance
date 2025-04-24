
# Installation Guide

## Prerequisites
- **Git**: To clone the repository. [Download Git](https://git-scm.com/downloads).
  - Windows: Install Git for Windows.
  - macOS: Install via Homebrew (`brew install git`) or Xcode.
  - Linux: Install via package manager (e.g., `sudo apt-get install git` for Ubuntu).
- **Docker**: For running Sail. [Install Docker Desktop](https://www.docker.com/products/docker-desktop).
  - Windows: Enable WSL 2 for best performance.
  - macOS: Ensure Docker Desktop is running.
  - Linux: Install Docker and Docker Compose (e.g., `sudo apt-get install docker.io docker-compose`).
- **Composer**: For PHP dependency management. [Install Composer](https://getcomposer.org/download/).
  - Windows: Download the installer.
  - macOS: Install via Homebrew (`brew install composer`).
  - Linux: Follow Composer’s Linux instructions.
- **PHP**: Required for Composer (version 8.0+).
  - Windows: Install via XAMPP or php.net.
  - macOS: Install via Homebrew (`brew install php@8.1`).
  - Linux: Install via package manager (e.g., `sudo apt-get install php8.1`).
- **Node.js/NPM**: Optional, for frontend assets (e.g., Laravel Mix, Vite). [Install Node.js](https://nodejs.org/).
  - Windows: Download installer.
  - macOS: `brew install node`.
  - Linux: `sudo apt-get install nodejs npm`.
- **Terminal/Shell**:
  - Windows: Use Command Prompt, PowerShell, or Git Bash.
  - macOS: Use Terminal or iTerm2.
  - Linux: Use Terminal.

## Steps

### 1. Clone the Laravel Project from GitHub
1. Open your terminal/shell:
   - Windows: Command Prompt, PowerShell, or Git Bash.
   - macOS/Linux: Terminal.
2. Navigate to the desired directory:
   ```bash
   cd /path/to/your/projects
   ```
   - Windows: `cd C:\Users\YourName\Projects`.
   - macOS/Linux: `cd ~/Projects`.
3. Clone the repository:
   ```bash
   git clone https://github.com/username/repository-name.git
   ```
   Replace `username` and `repository-name` with the GitHub username and repository name.
4. Navigate into the project directory:
   ```bash
   cd repository-name
   ```

### 2. Install Composer Dependencies
1. Verify Composer:
   ```bash
   composer --version
   ```
   - If not installed, follow [Composer instructions](https://getcomposer.org/download/) for your OS.
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
2. Open `.env` in a text editor (e.g., VS Code, Notepad, nano) and verify settings. Sail’s defaults usually work, but confirm queue settings:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=sail
   DB_PASSWORD=password

   QUEUE_CONNECTION=database
   ```
   - `QUEUE_CONNECTION=database` is simplest. For Redis, set `QUEUE_CONNECTION=redis` and ensure the Redis service is in `docker-compose.yml`.
3. Generate an application key:
   ```bash
   php artisan key:generate
   ```

### 4. Start Laravel Sail
1. Ensure Docker Desktop is running:
   - Windows/macOS: Launch Docker Desktop.
   - Linux: Verify Docker service (`sudo systemctl start docker`).
2. Start Sail:
   ```bash
   ./vendor/bin/sail up
   ```
   - Use `./vendor/bin/sail up -d` for background mode.
   - This starts containers (e.g., PHP, MySQL, Redis) defined in `docker-compose.yml`.
3. Check container status:
   ```bash
   docker ps
   ```
4. If using Redis for queues, verify `docker-compose.yml` includes a Redis service:
   ```yaml
   redis:
     image: 'redis:alpine'
     ports:
       - '${FORWARD_REDIS_PORT:-6379}:6379'
   ```
   Update `.env` for Redis:
   ```env
   QUEUE_CONNECTION=redis
   REDIS_HOST=redis
   REDIS_PORT=6379
   REDIS_PASSWORD=null
   ```

### 5. Set Up the Database
1. Run migrations to set up the database schema, including the `jobs` table for the database queue driver:
   ```bash
   ./vendor/bin/sail artisan migrate
   ```
2. (Optional) Seed the database:
   ```bash
   ./vendor/bin/sail artisan db:seed
   ```
3. For `database` queue driver, ensure the `jobs` table exists. If not, create it:
   ```bash
   ./vendor/bin/sail artisan queue:table
   ./vendor/bin/sail artisan migrate
   ```

### 6. Configure and Run the Queue
1. **Verify Queue Configuration**:
   - Check `config/queue.php` for queue connections.
   - Ensure `.env` `QUEUE_CONNECTION` matches the project’s driver (`database` or `redis`).
2. **Start the Queue Worker**:
   - Run the queue worker:
     ```bash
     ./vendor/bin/sail artisan queue:work
     ```
     - Processes jobs in the default queue. Stop with `Ctrl+C`.
     - For a specific queue:
       ```bash
       ./vendor/bin/sail artisan queue:work --queue=queue_name
       ```
       Check project documentation for queue names.
3. **Run Queue in Background** (Optional):
   - Use a separate terminal or process manager.
   - **Supervisor** (macOS/Linux):
     - Install:
       - macOS: `brew install supervisor`.
       - Linux: `sudo apt-get install supervisor`.
     - Create a configuration file (e.g., `/etc/supervisor/conf.d/laravel-worker.conf`):
       ```ini
       [program:laravel-worker]
       process_name=%(program_name)s_%(process_num)02d
       command=/path/to/your/projects/repository-name/vendor/bin/sail artisan queue:work --sleep=3 --tries=3
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
     - Replace paths and `your_username`.
   - **Windows**: Run `./vendor/bin/sail artisan queue:work` in a separate Command Prompt/PowerShell or use Task Scheduler.
   - **Alternative**: Run the worker in a Sail container:
     ```bash
     ./vendor/bin/sail shell
     php artisan queue:work
     ```

### 7. Install Frontend Dependencies (Optional)
If the project uses frontend assets:
1. Install dependencies:
   ```bash
   ./vendor/bin/sail npm install
   ```
2. Compile assets:
   ```bash
   ./vendor/bin/sail npm run dev
   ```
   - Use `npm run build` for production.

### 8. Access the Application
1. Open a browser and visit:
   ```
   http://localhost
   ```
   - Check `.env` (`APP_URL`) or `docker-compose.yml` for custom ports (e.g., `8000:80`).
2. Test queue functionality by triggering a job (e.g., sending an email).

### 9. Useful Sail Commands
- Stop Sail:
  ```bash
  ./vendor/bin/sail down
  ```
- Run Artisan commands:
  ```bash
  ./vendor/bin/sail artisan <command>
  ```
- Access Sail shell:
  ```bash
  ./vendor/bin/sail shell
  ```
- Run Composer/NPM:
  ```bash
  ./vendor/bin/sail composer <command>
  ./vendor/bin/sail npm <command>
  ```
- Retry failed jobs:
  ```bash
  ./vendor/bin/sail artisan queue:retry all
  ```
- Clear failed jobs:
  ```bash
  ./vendor/bin/sail artisan queue:flush
  ```
- List failed jobs:
  ```bash
  ./vendor/bin/sail artisan queue:failed
  ```

## Troubleshooting
- **Docker not running**: Start Docker Desktop (Windows/macOS) or Docker service (Linux).
- **Port conflicts**: Edit `docker-compose.yml` to change ports (e.g., `8080:80`) and restart Sail.
- **Permission issues** (Linux): Add user to Docker group:
  ```bash
  sudo usermod -aG docker $USER
  ```
- **Queue not processing**: Ensure `queue:work` is running and `QUEUE_CONNECTION` is correct.
- **Failed jobs**: Check `failed_jobs` table or logs:
  ```bash
  ./vendor/bin/sail artisan queue:failed
  ```
- **Missing dependencies**: Run `sail composer install` or `sail npm install`.
- **Database errors**: Verify `.env` and `docker-compose.yml` settings.

## Notes
- Since Sail is included, `docker-compose.yml` exists in the project root.
- Check `README.md` for specific instructions or queue drivers.
- For production, use a dedicated server setup instead of Sail.
- Redis is faster for queues; confirm it’s in `docker-compose.yml` if used.

For more details, see [Laravel Sail documentation](https://laravel.com/docs/sail) and [Laravel Queue documentation](https://laravel.com/docs/queues).
