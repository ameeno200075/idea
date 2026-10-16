# Idea — AI-Powered Idea Management

A Laravel web application for managing ideas, tracking progress, and using Google's Gemini AI to analyze ideas and provide personalized assistance.

## Overview

Idea combines traditional idea management with generative AI capabilities. Users can organize their ideas, track their status, and receive AI-powered feedback to help them evaluate and develop their plans.

The application uses Laravel as the backend layer between users, application data, and the Gemini API.

## Features

### Idea Management

* Create, view, update, and delete ideas.
* Track progress using Pending, In Progress, and Completed statuses.
* Organize ideas with descriptions and steps.
* Upload images associated with ideas.

### AI Assistant

* Chat with an AI assistant about your ideas.
* Ask questions based on your own ideas.
* Get help summarizing ideas and identifying priorities.

### AI-Powered Idea Analysis

Analyze an idea and receive structured feedback:

* **Summary:** An overview of the idea.
* **Strengths:** Positive aspects and potential advantages.
* **Risks:** Potential challenges and weaknesses.
* **Next Steps:** Suggested actions to develop the idea.

## Technology Stack

| Technology        | Purpose                        |
| ----------------- | ------------------------------ |
| PHP 8.3+          | Backend programming language   |
| Laravel 13        | Web application framework      |
| MySQL             | Local development database     |
| Google Gemini API | AI assistant and idea analysis |
| Blade             | Server-rendered user interface |
| Alpine.js         | Interactive UI components      |
| Vite and npm      | Frontend asset tooling         |
| Pest              | Automated testing              |

## AI Integration Architecture

The application uses a backend-mediated architecture for AI features.

1. The user submits a message or requests an idea analysis.
2. Laravel validates the request and checks authorization.
3. The application selects the relevant idea information.
4. The AI service sends the approved context to the Gemini API.
5. The response is processed and returned to the user.

The AI provider does not receive direct database access. Laravel controls access to application data and determines which information is included in AI requests.

## Security Considerations

* AI features are protected by authentication.
* Authorization checks are applied before analyzing ideas.
* Incoming requests are validated.
* Only selected idea fields are sent as AI context.
* API credentials should be stored in environment variables and never committed to version control.

## Requirements

* PHP 8.3 or later
* Composer
* Node.js and npm
* A database supported by the application's Laravel configuration
* A Google Gemini API key for AI functionality

## Installation

Clone the repository and enter the project directory.

Install PHP dependencies:

```bash
composer install
```

Create your environment configuration:

```bash
cp .env.example .env
```

On Windows PowerShell, use:

```powershell
Copy-Item .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure your database connection and Gemini API credentials in `.env`, using the variable names defined by the project's configuration.

Run database migrations:

```bash
php artisan migrate
```

Install frontend dependencies and build assets:

```bash
npm install
npm run build
```

Start the Laravel development server:

```bash
php artisan serve
```

Open the local address displayed in your terminal.

**Note:** Database configuration and AI environment variable names must match the project's actual configuration files.

## Running Tests

Run the configured Laravel test suite:

```bash
php artisan test
```

The project includes tests for important application functionality, including AI-assisted interactions and idea analysis.

## Project Goals

This project demonstrates practical implementation of AI-powered application features using Laravel, including:

* Integration with an external generative AI API
* Backend service design
* Authentication and authorization
* Request validation
* Structured AI responses
* Automated testing
* Controlled sharing of application data with an AI provider

## License

Refer to the repository's license file for the applicable license terms.
