<h2 align="center">🚀 Starship Commander Test 🚀</h2>

## How to run

>Requirements: PHP >= 8.1 & Composer (install latest version of composer from [here](https://getcomposer.org/download/))

1. Running this project is very easy. This is a simple command-line application. Once you download the project in your local directory, please run below command from the root directory of the project.
```shell
php index.php starships:list <no_of_starships>
```
> `no_of_starships` is a required argument. This will tell the system that how many Starship data it has to fetch from the API. An example command can be `php index.php starships:list 20`

2. I also added a logging system which logs data sanitization records. Log file can be checked inside `logs/` folder after running above command at least once.

## Future improvements
1. Can move more code from the Command file to separate services.
2. Implement some unit tests to make sure that my code works all the time.
3. Could add more attributes/endpoints into the application.