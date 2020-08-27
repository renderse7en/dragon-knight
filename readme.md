# Dragon Knight
- [Official Dev Site](https://dragonknight.dev)
- [Live Demo](https://demo.dragonknight.dev)
- [Old repo](https://github.com/renderse7en/dragon-knight)
- See also: [Dragon Scourge](https://github.com/renderse7en/dragon-scourge)

Many years ago, @renderse7en made a cool little text-based RPG in PHP4. It was a fun side project, helped him learn to code, and a lot of people like it.
Then, everything changed when he abandoned the source code to the wastes of the internet in 2007.

Fast forward 3 years and @splashsky finds that abandoned, decrepit code. He learns how to program in PHP from it. Another 10 years into the future, and
now he's reviving that project so that other rookies can learn from the same fun project he did!

- Found a bug? Have a suggestion, or a question? Open an Issue!
- Fixed a bug? Implemented a cool idea? Open a Pull Request!

# Server Requirements
- PHP 7.0+
- A MySQL server; we recommend [MariaDB](https://mariadb.org)
- A web server; we use [Nginx](https://www.nginx.com/)

You can host the game on your local computer if you'd like, but for maximum fun we recommend hosting your game on the web. Our official demo version
runs on a [$5 DigitalOcean server](https://www.digitalocean.com/products/droplets/), and deployment is handled through 
[Laravel Forge](https://forge.laravel.com/) (but that isn't necessary).

# Installation Instructions
1. Clone this repo or download the zip.
2. Create a new database for Dragon Knight to use, if you don't already have one set up.
3. Edit `config.php` to include the correct values for your database setup.
4. Upload the contents of the Dragon Knight folder to your site.
5. In your browser, run `install.php` and follow the instructions.
6. After completing installation, delete `install.php` from your Dragon Knight directory for security.
7. Enjoy the game.

# License
This project uses the MIT license, meaning you can do anything you'd like with it! See the
`license` file for the full license.

However, Surf also reserves copyright on all contributions we have made or will make. We still
allow free use of our contributions.