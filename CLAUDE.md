# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application that scrapes and displays bus departure times from bustimes.org for a specific bus stop (0190NSC30636). The application uses Vite for asset compilation with Tailwind CSS for styling.

## Development Commands

### Setup
```bash
composer run setup
```
This command installs dependencies, sets up the environment file, generates app key, runs migrations, installs npm packages, and builds assets.

### Development Server
```bash
composer run dev
```
Starts multiple concurrent processes:
- Laravel development server (`php artisan serve`)
- Queue worker (`php artisan queue:listen --tries=1`)
- Log viewer (`php artisan pail --timeout=0`)
- Vite development server (`npm run dev`)

### Testing
```bash
composer run test
```
Clears config cache and runs PHPUnit tests.

### Asset Building
```bash
npm run build    # Production build
npm run dev      # Development build with watching
```

### Laravel Artisan Commands
```bash
php artisan serve           # Start development server
php artisan migrate         # Run database migrations
php artisan test           # Run tests directly
php artisan pail           # View logs in real-time
php artisan queue:listen   # Start queue worker
```

## Architecture

### Core Application Structure
- **Single Route Application**: Only one route (`/`) handled by `BusTimesController`
- **Web Scraping**: Uses Laravel HTTP client to fetch data from bustimes.org
- **DOM Parsing**: Extracts departure information using DOMDocument and XPath
- **Simple Blade View**: Displays scraped content with inline CSS styling

### Key Components
- `BusTimesController`: Handles the scraping logic and data extraction from `https://bustimes.org/stops/0190NSC30636`
- `bus-times.blade.php`: Main view template that renders scraped departure times
- Database: Uses SQLite for development (database.sqlite)

### Frontend Stack
- **Vite**: Build tool and development server
- **Tailwind CSS 4.0**: Utility-first CSS framework
- **Laravel Vite Plugin**: Integration between Laravel and Vite
- Asset sources: `resources/css/app.css` and `resources/js/app.js`

### Dependencies
- Laravel Framework 12.0
- PHP 8.2+
- Laravel Pint (code formatting)
- PHPUnit for testing
- Tailwind CSS 4.0 with Vite plugin

## Testing

The project uses PHPUnit with separate test suites:
- Unit tests: `tests/Unit/`
- Feature tests: `tests/Feature/`
- Test environment uses in-memory SQLite database