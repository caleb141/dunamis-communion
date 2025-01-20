@echo off
REM Script to create a PHP project structure without Composer

REM Create directories
mkdir public
mkdir src
mkdir config
mkdir api
mkdir db
mkdir tests
mkdir vendor

REM Create subdirectories inside src
mkdir src\Controllers
mkdir src\Auth
mkdir src\Models
mkdir src\Database
mkdir src\Middleware
mkdir src\Services
mkdir src\Helpers

REM Create placeholder files for each part of the project

REM public directory
type nul > public\index.php

REM src/Controllers directory
type nul > src\Controllers\MemberController.php
type nul > src\Controllers\TeamLeadController.php
type nul > src\Controllers\TeamController.php
type nul > src\Controllers\BaseController.php

REM src/Auth directory
type nul > src\Auth\AdminAuthController.php

REM src/Models directory
type nul > src\Models\Member.php
type nul > src\Models\TeamLead.php
type nul > src\Models\Team.php

REM src/Database directory
type nul > src\Database\Database.php

REM src/Middleware directory
type nul > src\Middleware\AuthMiddleware.php

REM src/Services directory
type nul > src\Services\AuthService.php

REM src/Helpers directory
type nul > src\Helpers\ResponseHelper.php

REM config directory
type nul > config\config.php

REM api directory (optional legacy API)
type nul > api\members.php
type nul > api\teamleads.php
type nul > api\teams.php

REM Create .env file for environment variables
echo DB_HOST=localhost > .env
echo DB_USER=root >> .env
echo DB_PASS=secret >> .env
echo DB_NAME=my_database >> .env

REM End of script
echo Project structure created successfully.
pause
