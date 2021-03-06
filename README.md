2FAuth-Steganography
================

This example demonstrates the two-factor authentication, where the second factor to authenticate is system    generated key. This key is embedded in a user image using the technique of steganography. 
To pass 2 factor authentication the user needs load an image with embedded key.

You can explore [live demo](http://semaster.name/2FAuthSteganography/) to check how it works.

Requirements
------------
* PHP 5.4+
* GDLib
* gettext support
* PHP PDO Extension
* Mysqli

Installation
------------
* Copy files from /web - to your web folder
* Setup DB 
* Write settings in /engine/config.php
* Make sure that the folder /uploads/2fa is writable
* If you're using Apache, make sure you activate the URL rewriting module, 
* For Nginx servers use proper location, for example
```
    location /2FAuthSteganography/ {
        root   /var/www/yousite;
        index  index.html index.php;
        try_files $uri /2FAuthSteganography/index.php?$request_uri;
    }
```

Least Significant Bit (LSB)
---------------------------
2FAuth-Steganography uses LSB method to modify least significant bit of image to embed auth-key. 
Detailed description with example can be found section ["Steganography in Depth" of this book](http://books.google.com.ua/books?id=qGcum1ZWkiYC&pg=PA37&source=gbs_toc_r&cad=3#v=onepage&q&f=false).

2FAuth-Steganography has implementation of LSB with such conditions:
* png, jpg or gif images as container,
* binary string as a auth-key.
 
