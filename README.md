# Dragon Knight
- See also: [Dragon Scourge](https://github.com/renderse7en/dragon-scourge)
- [Live Demo](http://dragon.se7enet.com/)

Many years ago, when I was young and dumb, I wrote a simple little game based on the game *Dragon Warrior* for the NES. It was fun, it helped me learn how to code, and a lot of people liked it.
I am now turning it over to the open source community. Fork it, do what you want, make it your own.
Couple things to keep in mind though:
- It's super old. It may not even work on modern versions of PHP. It may have security issues. I have no idea.
- I have moved on with my life, and am no longer changing or doing anything with this game.
- I am not providing help or support. You're on your own.
- I am not accepting pull requests. If you fork this, you are welcome to do whatever you want, but no changes will be merged back into this.
- Quite frankly, I don't really suggest that you use this as is. It's probably better as an inspiration for your own project. 
- Have fun with this. I gave it a lot of love a long time ago. I hope it inspires you to give something a lot of love as well.
- This Git repo represents the final released version, 1.1.11, originally released 3/26/2006.

# System Requirements
- PHP (4.1 and higher)
- MySQL
- zlib compression enabled on your server (optional)

# Installation Instructions
1. Clone this repo or download the zip.
2. Create a new database for Dragon Knight to use, if you don't already have one set up.
3. Edit `config.php` to include the correct values for your database setup.
4. Upload the contents of the Dragon Knight folder to your site.
5. In your browser, run `install.php` and follow the instructions.
6. After completing installation, delete `install.php` from your Dragon Knight directory for security.
7. Enjoy the game.

# License
MIT License

Copyright (c) 2017 renderse7en

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
