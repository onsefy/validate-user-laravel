# OnSefy Laravel SDK – Validate Users & Detect Fraud

[![Latest Version on Packagist](https://img.shields.io/packagist/v/onsefy/validate-user-laravel.svg?style=flat-square)](https://packagist.org/packages/onsefy/validate-user-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/onsefy/validate-user-laravel.svg?style=flat-square)](https://packagist.org/packages/onsefy/validate-user-laravel)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

> Official Laravel SDK for [OnSefy](https://onsefy.com) – Validate new users, detect fraud, and score risk automatically to stop fake signups.

## 🚀 Features
- AI Based Quick Intelligent Assessment in microseconds
- Easy integration with Laravel apps
- Validate users with email, phone, IP, and user-agent
- Get detailed fraud risk scoring and patterns
- Detect suspicious users
- Free and paid plan support

---

### 📦 Installation

Install the package via Composer:

```bash

composer require onsefy/validate-user-laravel

```

Laravel 11+ will auto-discover the service provider and facade. If not, add manually to config/app.php:

```php
'providers' => [
// ...
OnSefy\Laravel\OnSefyProvider::class,
],

'aliases' => [
// ...
'OnSefy' => OnSefy\Laravel\Facades\OnSefy::class,
],
```

### ⚙️ Configuration
Publish the config file:
```bash
php artisan vendor:publish --tag=onsefy-config
```
This will create a config file at config/onsefy.
Add your credentials to .env:
```dotenv
ONSEFY_PLAN_TYPE=free
ONSEFY_API_KEY=your-api-key-here
ONSEFY_SERVICE_ID=your-service-id-here
```
### 🧠 Usage Example

use OnSefy;
```php
$response = OnSefy::validateUser([
'email' => 'xiyelv1@decep.com',
'phone' => '+13434128780',
'ip' => '103.209.253.36',
'name' => 'John Doed',
'user_agent' => 'mozilla/5.0 (macintosh; intel mac os x 10.15; rv:136.0) gecko/20100101 firefox/136.0',
]);

if ($response['status']) {
// Take action based on risk score or level
$risk = $response['summary']['risk_score'];
$risk_level = $response['summary']['risk_level'];

}
```
### ✅ Recommended Validation Flow

To effectively prevent fake signups and reduce fraud risk, follow this validation strategy:

1. **Pre-validate user data**  
   Call `OnSefy::validateUser($userData)` **before** creating or storing a new user record.

2. **Evaluate the response**  
   Inspect key indicators from the response:
    - `risk_score` — numerical fraud risk (0 to 10)
    - `verify_level` — 0,1,2  level classification (e.g.,"0 Legit","1 Suspicious", "2 Fraud" )
    - `risk_patterns` — matched signals or warnings (e.g., "x pattern", "y pattern")

3. **Take action based on risk score**
    - **Strict filtering**: Block or flag users with `risk_score > 1`
    - **Loose filtering**: Allow up to `risk_score <= 2`, review or throttle above that

This ensures your platform stays protected while balancing user experience.



### 🛡️ Risk Score Reference

| Risk Level  | Label      | Action Suggestion   |
|-------------| ---------- |---------------------|
| 0 – 1.00    | Low        | Proceed             |
| 1.01 – 2.50 | Suspicious | Review or challenge |
| 2.51        | Fraud      | Block and Reject    |

---


#### 📝 License

The MIT License (MIT). See [LICENSE](LICENSE) for details.

---

#### 💬 Support

* [Docs](https://docs.onsefy.com)
* [Website](https://onsefy.com)
* Submit Ticket at [HelpDesk](https://onsefy.zohodesk.in/portal/en/newticket)

---

#### 👥 Author

**OnSefy**
Stop fake signups. Detect fraud before it costs you.
[https://onsefy.com](https://onsefy.com)

---
