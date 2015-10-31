SfProject
==========

This "sfproject" is a simple demo application based on Symfony 2.3 framework.
This demo application just shows you how a Symfony Application looks like, and how it works. 
It will not tell how to develop a Symfony application from scratch, But you can follow the recommended [Best Practices](http://symfony.com/doc/current/best_practices/index.html) to make it.


Requirements
------------

  * PHP 5.4 or higher will be fine;
  * PDO-MYSQL PHP extension enabled;
  * and the [usual Symfony application requirements](http://symfony.com/doc/2.3/reference/requirements.html).

If unsure about meeting these requirements, download this demo application and browse the `http://localhost:8000/config.php` script to get more detailed information.


Installation
------------

1. Get Source Code:
    Clone or download this [sfproject](https://github.com/loiyshen/sfproject.git).

    ```bash
    $ git clone https://github.com/loiyshen/sfproject.git
    ```

2. Create Database:
    The SQL files you need are under the folder `/data/mysql-db/`.
    The default Account/Password is: `admin/123456`, then you can login to the back-office.

3. Apache Configuration:

You need to add 2 virtual hosts to your `httpd-vhosts.conf` file.

The content of configuration what you need in the `/data/apache-conf/httpd-vhosts.conf` file.

4. Add Local Hosts Records:

Copy the content of `/data/hosts/hosts.txt` file, and paste it at the end of your local hosts file.

The hosts file here `C:\Windows\System32\drivers\etc\hosts` in Win 7.

5. Run it:
Now, you visit the demo application: 

 - front-end:  `http://sf.dev.com` 
 - back-office: `http://sf-admin.dev.com`.

If you have PHP 5.4 or higher, there is no need to configure a virtual host in your web server to access the application. 

Just use the built-in web server:

```bash
$ cd sfproject/
$ php app/console server:run
```
This command will start a web server for the Symfony application. 

Now you can access the application in your browser at <http://localhost:8000>. 

You can stop the built-in web server by pressing `Ctrl + C` while you're in the terminal.

> **NOTE**
> For more details, see:
> http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html

Questions
------------

If you have any question about this demo application, please leave me a comment.

Welcome to point the bugs out, and I will try to fix it as soon as possible.


What is Symfony?
---------------------

> Symfony is a PHP 5.3 full-stack web framework.  It is written with
> speed and flexibility in mind.  It allows developers to build better
> and easy to maintain websites with PHP.
> 
> Symfony can be used to develop all kind of websites, from your
> personal blog to high traffic ones like Dailymotion or Yahoo! Answers.

You can get more information from <http://symfony.com> and <https://github.com/symfony/symfony>.


Contact
--------
Please mail me: `web#loiy.net`.
