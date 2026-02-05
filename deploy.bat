@echo off
echo ================================
echo  PDA System - Docker Deployment
echo ================================
echo.

REM Check if Docker is running
docker info > nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Docker is not running. Please start Docker Desktop first.
    pause
    exit /b 1
)

REM Copy environment file if not exists
if not exist .env (
    echo [INFO] Copying .env.docker to .env...
    copy .env.docker .env
)

REM Generate APP_KEY if empty
findstr /C:"APP_KEY=" .env | findstr /C:"APP_KEY=base64" > nul 2>&1
if %errorlevel% neq 0 (
    echo [INFO] Generating APP_KEY...
    docker run --rm -v "%cd%":/app -w /app composer:latest php artisan key:generate --force
)

echo.
echo [1/4] Building Docker images...
docker-compose build --no-cache

echo.
echo [2/4] Starting containers...
docker-compose up -d

echo.
echo [3/4] Waiting for MySQL to be ready...
timeout /t 15 /nobreak > nul

echo.
echo [4/4] Running migrations...
docker-compose exec -T app php artisan migrate --force

echo.
echo ================================
echo  Deployment Complete!
echo ================================
echo.
echo Application URL: http://localhost:8090
echo.
echo Useful commands:
echo   docker-compose logs -f      View logs
echo   docker-compose down         Stop containers
echo   docker-compose ps           Check status
echo.
pause
