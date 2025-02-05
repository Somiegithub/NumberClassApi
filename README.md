# HNG-2025 Readme for Stage 1

## Setting Up PHP 8.0 with Apache on Ubuntu

### Prerequisites
Ensure you have a fresh Ubuntu installation with `sudo` privileges.

---

### Step 1: Update System Packages
Before installing new software, update your system packages:
```sh
sudo apt update && sudo apt upgrade -y
```

---

### Step 2: Install Necessary Packages
Install essential utilities like `unzip` and `p7zip`:
```sh
sudo apt-get install unzip -y
sudo apt-get install p7zip -y
```

---

### Step 3: Install Apache Web Server
Install the Apache web server:
```sh
sudo apt install apache2 -y
```
Ensure Apache is running:
```sh
sudo systemctl status apache2
```
If Apache is not running, start it:
```sh
sudo systemctl start apache2
```

---

### Step 4: Install PHP 8.0
First, add the `ondrej/php` repository, which provides multiple PHP versions:
```sh
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
```
Now, install PHP 8.0:
```sh
sudo apt install php8.0 -y
```
Verify the installation:
```sh
php -v
```

---

### Step 5: Install PHP Extensions
Install necessary PHP extensions required for the project:
```sh
sudo apt install php8.0-dom php8.0-gd php8.0-intl php8.0-mbstring php8.0-xml php8.0-xsl php8.0-zip -y
sudo apt-get install php8.0-curl php8.0-pdo-mysql php8.0-bcmath -y
```

---

### Step 6: Set Up API Directory
Navigate to the web root directory and create an `/api` directory:
```sh
cd /var/www/html/
sudo mkdir /api
cd /api
```
Create the `number_classify.php` file:
```sh
sudo nano number_classify.php
```

Add the following sample code to `classify-number.php`:
```php
paste the number_classify.php code inside
```
Save the file (`CTRL + X`, then `Y`, then `ENTER`).

Test the API by visiting:
```
http://34.228.212.209/api/number_classify.php?number=371
```

---

### Step 7: Configure Apache
Navigate to the Apache configuration directory:
```sh
cd /etc/apache2/sites-available/
```
Remove the default Apache configuration file:
```sh
sudo rm 000-default.conf
```
Create a new `000-default.conf` file:
```sh
sudo nano 000-default.conf
```

Add the following configuration:
```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html
    <Directory /var/www/html/>
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Save the file (`CTRL + X`, then `Y`, then `ENTER`).

Restart Apache to apply changes:
```sh
sudo systemctl restart apache2
```

---

## Understanding `number_classify.php`

This script processes a given number and classifies it based on specific properties such as being prime, perfect, Armstrong, even, or odd. Below is an explanation of its components:

1. **Headers for CORS and JSON Response:**
   ```php
   header("Access-Control-Allow-Origin: *");
   header("Content-Type: application/json");
   ```
   These lines ensure that the API can be accessed from any domain and that the response is in JSON format.

2. **Prime Number Check:**
   ```php
   function is_prime($n) {
       if ($n <= 1) return false;
       for ($i = 2; $i <= sqrt($n); $i++) 
           if ($n % $i == 0) return false;
       return true;
   }
   ```
   This function checks whether a number is prime by iterating up to its square root.

3. **Perfect Number Check:**
   ```php
   function is_perfect($n) {
       if ($n <= 1) return false;
       $sum = 1;
       for ($i = 2; $i <= sqrt($n); $i++) 
           if ($n % $i == 0) $sum += $i + ($n / $i);
       return $sum == $n;
   }
   ```
   This function determines if a number is perfect by summing its divisors.

4. **Armstrong Number Check:**
   ```php
   function is_armstrong($n) {
       $num = abs($n);
       $digits = str_split((string)$num);
       $power = count($digits);
       $sum = array_sum(array_map(fn($d) => pow($d, $power), $digits));
       return $sum == $num;
   }
   ```
   An Armstrong number is one where the sum of its digits, each raised to the power of the number of digits, equals the original number.

5. **Processing User Input:**
   ```php
   $input = $_GET['number'] ?? null;
   ```
   The script retrieves the number parameter from the URL.

6. **Validating Input:**
   ```php
   if (!is_numeric($input) || !ctype_digit((string)abs($input))) {
       http_response_code(400);
       echo json_encode(["number" => $input, "error" => true]);
       exit;
   }
   ```
   Ensures that the provided input is a valid number.

7. **Determining Properties:**
   ```php
   $properties = [];
   if (is_armstrong($number)) { ... }
   elseif (is_prime($abs_num)) { ... }
   elseif (is_perfect($abs_num)) { ... }
   $properties[] = ($abs_num % 2 == 0) ? "even" : "odd";
   ```
   Classifies the number based on its properties.

8. **Returning the Response:**
   ```php
   echo json_encode([...]);
   ```
   The results are returned as a structured JSON response.

---

### Conclusion
You have successfully set up an Apache server with PHP 8.0 and implemented a number classification API. Test the setup using the provided API endpoint.

For troubleshooting, check Apache logs:
```sh
sudo tail -f /var/log/apache2/error.log
```

When uploading, use:
```
http://34.228.212.209/api/number_classify.php?number=371# without parameter
```

Repository: [GitHub](https://github.com/Incrisz/HNG-2025)

