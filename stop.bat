@echo off
echo Stopping and removing PDA System containers...
docker-compose down

echo.
echo Containers stopped.
echo.
echo To also remove the database volume, run:
echo   docker-compose down -v
echo.
pause
