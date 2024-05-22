# Visit Tracker Library

Visit Tracker is a PHP library for tracking visits on a website and providing an interface to visualize this data.

**To start using the library, follow these steps:**

## Prerequisites

Before installing and using this package, make sure your environment meets the following prerequisites:

- **PHP**: This package requires PHP version 8.0 or higher. You can download and install PHP from the [official PHP website](https://www.php.net/downloads).
- **MySQL**: Ensure that you have MySQL installed and configured on your system. You can download MySQL from the [official MySQL website](https://www.mysql.com/downloads/).

## Installation

1. Install the library:

    1. Manually/Locally: 
   
        Add the below to your composer.json
        
        ```json
        "require": {
            ...
            "efernandess/visittracker": "dev-main"
        },
        "repositories": [
            ...
            {
                "type": "github",
                "url": "https://github.com/efernandess/visittracker.git"
            }
        ],
        ```
        Then run:       

       ```bash
       composer install
       ```
       or
       ```bash
       composer update
       ```

    3. Via [packagist.org](https://packagist.org) (if exists):
        ```bash
        composer require efernandess/visittracker
        ```

## Configuration

2. The `tracked_visits` table is used to store information about visits recorded on the website. Below is the structure of the table:

```sql
CREATE TABLE tracked_visits (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255) NOT NULL,
    visited_page VARCHAR(255) NOT NULL,
    referrer VARCHAR(255) DEFAULT NULL,
    visit_datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    country VARCHAR(100) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    device VARCHAR(50) DEFAULT NULL,
    screen_resolution VARCHAR(20) DEFAULT NULL,
    browser VARCHAR(50) DEFAULT NULL,
    browser_version VARCHAR(255) DEFAULT NULL,
    operating_system VARCHAR(50) DEFAULT NULL
);
```
Field Descriptions
- `ip_address`: IP address of the visitor.
- `user_agent`: User agent of the visitor.
- `visited_page`: Page visited by the user.
- `referrer`: Referring page, if any.
- `visit_datetime`: Date and time of the visit.
- `country`: Country of the visitor.
- `city`: City of the visitor.
- `device`: Device used by the visitor.
- `screen_resolution`: Screen resolution of the device.
- `browser`: Browser used by the visitor.
- `browser_version`: Version of the browser.
- `operating_system`: Operating system of the visitor's device.

3. Create a .env configuration file at the root of your project and configure the necessary environment variables:

```dotenv
TRACKER_ALLOWED_ORIGIN=http://127.0.0.1:8000
DB_HOST=
DB_USERNAME=
DB_PASSWORD=
DB_DATABASE=
DB_PORT=
```

4. **[IMPORTANT]** CSRF configuration (if necessary): **You must add the route `/visit-tracking` to the exceptions**.

5. Copy the file `/vendor/visit-tracker/public/tracker.js` and place it in a _public folder_ that you can include in your html. Include the JavaScript script at the end of the body tag of your HTML:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Title</title>
</head>
<body>
    <!-- Your HTML content here -->

    <script src="your public path/tracker.js"></script>
</body>
</html>
```

5. To register a visit, you need to create a `POST` route in your application and call the `postRegisterVisit` method of the library. Here's an example using the Slim Framework:

```php
$app->post('/visit-tracking', function () {
    return \EdsonFernandes\VisitTracker\Tracker::postRegisterVisit();
});
```
Make sure that the route name is `/visit-tracking`.

6. To retrieve visit data, create a `GET`  route in your application and call the `getRetrieveData` method of the library. Here's an example using the Slim Framework:

```php
$app->get('/visit-tracking', function () {
    return \EdsonFernandes\VisitTracker\Tracker::getRetrieveData();
});
```
Make sure that the route name is `/visit-tracking`.

## Contribution

Contributions are welcome! Feel free to open an issue or submit a pull request.
