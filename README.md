# Ideas

Ideas is a Laravel application for capturing, organizing, and developing personal ideas. It includes CRUD management for ideas, actionable steps, links, images, Gemini-powered analysis for an individual idea, and a personal AI assistant that can work with the authenticated user's ideas.

## Features

- User registration, login, logout, and profile management
- Create, list, filter, view, update, and delete ideas
- Idea statuses: pending, in progress, and completed
- Actionable steps with completion toggles
- Optional links and featured images
- AI Analysis for one authorized idea
- Personal Idea Assistant for listing, summarizing, comparing, and prioritizing the authenticated user's ideas
- Authorization that prevents users from accessing another user's ideas

## Tech stack

- PHP 8.3+
- Laravel 13
- MySQL for local development (SQLite is used by the test suite)
- Blade
- Alpine.js
- Axios
- Tailwind CSS and Vite
- Gemini API
- Pest and Laravel browser tests

## Installation

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm install
npm run build
```

Configure database connection values in `.env` before running migrations.

## Environment configuration

The required AI settings are:

```dotenv
AI_PROVIDER=gemini
AI_BASE_URL=https://generativelanguage.googleapis.com/v1beta
AI_API_KEY=
AI_MODEL=gemini-flash-latest
AI_TIMEOUT=30
```

Put the Gemini API key only in the local or deployment environment `.env` file. Never commit it, place it in `.env.example`, render it in Blade, or send it to the browser.

## Database setup

The migrations create users, sessions, ideas, actionable steps, cache, and queue tables. Ideas belong to users and steps belong to ideas. Deleting a user cascades to their ideas, and deleting an idea cascades to its steps.

## Running the application

For development:

```bash
composer run dev
```

Or run the application and frontend separately:

```bash
php artisan serve
npm run dev
```

## Running tests

```bash
php artisan test
vendor/bin/pint --test
npm run build
```

The test suite uses Laravel HTTP fakes for Gemini requests; it does not depend on the real Gemini API.

## AI architecture

The browser never communicates directly with Gemini:

```text
Browser
  -> Laravel controller
  -> authentication, validation, and authorization
  -> database query scoped to the authenticated user
  -> limited AI context
  -> Gemini
  -> Laravel response
  -> Browser
```

For AI Analysis, Laravel authorizes the selected idea and sends only the fields required for that analysis. For the Personal Idea Assistant, Laravel queries only the authenticated user's ideas and sends only `id`, `title`, `description`, `status`, and `created_at`.

Gemini does not directly access the database, Eloquent models, arbitrary user records, or application credentials.

## Security model

- Authenticated routes use Laravel's `auth` middleware.
- Idea ownership is enforced through `IdeaPolicy::workWith`.
- Create and update requests validate user input before persistence.
- Mass assignment is limited by model fillable configuration and action-level field selection.
- Blade escapes displayed user and AI text; Alpine uses `x-text` for AI output.
- CSRF protection applies to browser forms and Axios requests.
- Gemini API keys remain server-side in environment configuration.
- Provider failures are logged with status metadata only, not keys, prompts, or user idea data.

## External AI privacy boundary

AI requests send selected user content to the configured Gemini provider. The personal
assistant sends each authenticated user's idea `id`, title, description, status, and
creation timestamp together with the current chat message. Idea analysis sends the
selected idea's title, description, and status. Images, links, passwords, and unrelated
users' ideas are not included.

Before deploying, disclose this data flow to users and review the Gemini account's data
retention and privacy settings. Do not enable AI features in an environment where this
transfer is not permitted for the stored idea data.

## Project structure

- `app/Http/Controllers` - HTTP endpoints and authorization boundaries
- `app/Http/Requests` - request validation
- `app/Actions` - idea persistence workflows
- `app/Models` - Eloquent models and relationships
- `app/Policies` - ownership authorization
- `app/Services/AiChatService.php` - Gemini request and response handling
- `resources/views` - Blade pages and Alpine UI
- `database/migrations` - database schema
- `tests/Feature` - HTTP and AI integration tests
- `tests/Browser` - browser interaction tests

## Future improvements

Potential future work includes richer structured assistant responses, background processing for long AI requests, rate limiting, and expanded authorization and API failure coverage. These are not required for the current application flow.

## Portfolio summary

This project can honestly be described as:

> A Laravel Ideas Management application enhanced with Gemini AI for individual Idea analysis and a personalized AI assistant.

It demonstrates server-mediated AI integration, ownership-scoped data access, validation, CRUD workflows, and automated tests without giving the AI provider direct database access.
