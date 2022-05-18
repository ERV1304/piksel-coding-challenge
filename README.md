# piksel-coding-challenge

<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#built-with">Built With</a></li>
      </ul>
    </li>
    <li>
      <ul>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

Implement a system to record and calculate royalty payments owed to Rights Owners based on viewing activity of customers.  

Royalties will be calculated at the Rights Owner level, so each episode belonging to a specific Rights Owner will be worth the same amount.  

The system must meet the provided REST API specification and accept/return JSON.

<p align="right">(<a href="#top">back to top</a>)</p>


### Built With

* [Composer](https://getcomposer.org/)
* [Docker](https://www.docker.com/)
* [MySQL](https://www.mysql.com/)
* [PHPUnit](https://phpunit.de/)
* [Restful Api](https://restfulapi.net/)
* [Symfony](https://symfony.com/)

<p align="right">(<a href="#top">back to top</a>)</p>


### Installation

1. Clone the repo using git clone command:
   ```
   git clone https://github.com/ERV1304/piksel-coding-challenge
   ```
2. Install dependencies packages via composer:
   ```
   composer install
   ```
   
3. Run docker compose to load container (use sudo if you need):
   ```
   docker-compose up -d
   ```
   
4. Find this line on '.env' file inside project:
 ```
DATABASE_URL="mysql://root:ERVmysql1304@mysql:3306/Coding_Challenge?serverVersion=5.7&charset=utf8mb4" 
 ```
 and replace for this other:
 
 ```
DATABASE_URL="mysql://root:ERVmysql1304@127.0.0.1:3306/Coding_Challenge?serverVersion=5.7&charset=utf8mb4" 
 ```

5. Execute migration file:
 ```
  php bin/console doctrine:migrations:execute --up "DoctrineMigrations\Version20220514090537" 
 ```

6. Load test data using fixture and json files:
 ```
 php bin/console doctrine:fixtures:load
 ```
 
 7. Write the following sentence on your browser:
  ```
  http://localhost:80
  ```
 
<p align="right">(<a href="#top">back to top</a>)</p>


<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE.txt` for more information.

<p align="right">(<a href="#top">back to top</a>)</p>


<!-- CONTACT -->
## Contact

Ernesto Rojas Vila - rojasvilaernesto@gmail.com

Project Link: https://github.com/ERV1304/piksel-coding-challenge

<p align="right">(<a href="#top">back to top</a>)</p>
