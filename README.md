# Simple CRUD Like "X" Platform with Plain PHP MVC

## About
This is a simple and fun project to gain my exploration knowledge using native PHP code base, before jump into Laravel ecosystem.  
Please **do not use this on production** app, this project has some limitation and known issues.  
If you want to build scalable modern apps in PHP, you can use framework like Laravel, Codeigniter, Slim, Yii, Symfony, etc.
## How to run
Create or use the sample `microsite.sqlite` dataset and place to the root project directory structure, and then
You can use command line utilities from Taskfile.dev or you can see Taskfile.yml file and running it each step manually...

```sh
task compile-css
task run-dev
```

before commit you can run `task run lint`

PS: when you have changes on views, you should make sure tailwind compiler is running, otherwise the compiled class not injected to the html code.

## Known Limitations
During development, mostly i use command `php -S localhost:8000` so it runs on builtin webserver, and the native webserver is not suitable for high performance workloads.  
On every HTTP request dispatch, the `bramus/router` mounted and match the regex of the path URI, so its not singleton for the design pattern.  
Because on every request mount an router instance, it makes memory too high, but do not worry about it... PHP has a builtins Garbage Collector.
