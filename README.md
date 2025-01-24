# PHP X Microsite

## About
This is a simple and fun project to gain my exploration knowledge using native PHP code base, before jump into Laravel ecosystem.  
Please **do not use this on production** app, this project has some limitation and known issues.  
If you want to build scalable modern apps in PHP, you can use framework like Laravel, Codeigniter, Slim, Yii, Symfony, etc.
## Architecture

## Known Limitations
During development, mostly i use command `php -S localhost:8000` so it runs on builtin webserver, and the native webserver is not suitable for high performance workloads.  
On every HTTP request dispatch, the `bramus/router` mounted and match the regex of the path URI, so its not singleton for the design pattern.  
Because on every request mount an router instance, it makes memory too high, but do not worry about it... PHP has a builtins Garbage Collector.
