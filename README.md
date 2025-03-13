# xSentimental

This project performs a search for tweets using the Twitter API, queues them in RabbitMQ, and then uses the ChatGPT API (gpt-3.5-turbo model) to conduct sentiment analysis. The architecture is organized following Domain-Driven Design (DDD) principles and decoupled integration with external services (Twitter and OpenAI).

---

## Architecture Overview

The project is structured into several layers:

1. **Domain**

   - Contains pure business logic and domain entities such as `Tweet`, `Sentiment`, `TweetId`, etc.
   - Defines repository interfaces for data persistence and retrieval.

2. **Application (Use Cases)**

   - Orchestrates business use cases, such as `SearchTweetsUseCase`, which:
     - Searches for tweets via the Twitter API.
     - Queues tweets for further processing.
   - Leverages repository interfaces and external service clients (e.g., TwitterApiClient, SentimentAnalysisClient) via dependency injection.

3. **Infrastructure**

   - Implements technical details (adapters) for integrating with external services:
     - `TwitterApiClient` to call the Twitter API.
     - `SentimentAnalysisClient` to call the ChatGPT API.
     - Concrete repository implementations (using Doctrine or other persistence layers).
     - RabbitMQ integration for queuing messages.

4. **UI (User Interface)**
   - Symfony Controllers exposing HTTP routes for interaction (e.g., a form to enter a hashtag and search tweets).
   - Console commands for batch operations or testing if needed.

---

## Main Flow

1. The **user** inputs a hashtag through the web form.
2. The **controller** invokes the **Use Case** (`SearchTweetsUseCase`), which:
   - Uses the **`TwitterApiClient`** to search for tweets on the Twitter API.
   - Enqueues the tweets into **RabbitMQ** for asynchronous processing.
3. A **worker** (consumer from RabbitMQ) picks up the tweets and invokes the **`SentimentAnalysisClient`** (which calls ChatGPT) to analyze the sentiment (positive, negative, or neutral).
4. The **result** (sentiment analysis) is then saved in the database (or cached) and can be returned to the UI via AJAX or rendered on a web page.

---

## Docker-Compose

The `docker-compose.yml` file includes several services to run the project:

- **php**: Contains PHP-FPM with the necessary extensions for Symfony.
- **nginx**: A web server exposing the application on port 80.
- **db**: A container running PostgreSQL (or another database, as configured).
- **rabbitmq3**: RabbitMQ for queuing messages (tweets for analysis).
- **worker**: A service that runs `php bin/console messenger:consume async` to process messages from RabbitMQ.
- **mailer** (optional): A container for capturing and viewing emails during development (e.g., MailCatcher).
- Other services (such as Xdebug configuration) may also be included.

**Usage**:

1. Adjust environment variables in `.env` or `.env.local` (e.g., `TWITTER_API_KEY`, `OPENAI_API_KEY`, etc.).
2. Run `docker-compose up -d` to start all containers.
3. The application is available at `http://localhost` (or as configured).
4. The worker runs in the background to process RabbitMQ messages.

---

## What the Project Does

- **Searches for tweets** based on a provided hashtag using the Twitter API.
- **Queues** the retrieved tweets in RabbitMQ.
- **Uses ChatGPT (gpt-3.5-turbo)** to analyze the sentiment of each tweet.
- **Displays** the results on a web interface or via AJAX updates.

**Note**: Due to recent changes in the Twitter API, the account used must have access to the `tweets/search/recent` endpoint. If using a free account or one with restrictions, you might encounter a 429 error (Too Many Requests).

---

## Configuration and Execution

1. **Clone the repository**:
   ```bash
   git clone https://your-repository-url/xSentimental.git
   cd xSentimental
   ```
2. **Install dependencies**:
   ```bash
   composer install
   ```
3. **Set environment variables** in `.env.local`:
   ```env
   TWITTER_API_KEY=your_twitter_api_key
   OPENAI_API_KEY=your_openai_api_key
   ```
   Also adjust database settings as needed.
4. **Start the containers**:
   ```bash
   docker-compose up -d
   ```
5. **Access the application** at `http://localhost` (or your configured port).
6. **Test the application** by entering a hashtag in the form. The application will:
   - Search for tweets.
   - Queue them.
   - Process them for sentiment analysis via ChatGPT.

---

## Tests

- The project includes both **unit tests** (for domain entities and use cases) and **integration tests** (for external services such as Twitter and ChatGPT).
- To run the tests:
  ```bash
  vendor/bin/phpunit
  ```
  or, if using Symfony Flex:
  ```bash
  php bin/phpunit
  ```

---

## Final Remarks

- If you encounter a **429 error** when searching for tweets, it indicates that Twitter's rate limit has been reached. This might require a paid plan or waiting for the rate limit reset.
- Ensure that the **OPENAI_API_KEY** variable is set correctly to use the ChatGPT (gpt-3.5-turbo) API.
- The architecture is designed to be easily extended, for example, to persist tweets and sentiments in a database and to display real-time updates on the UI.
