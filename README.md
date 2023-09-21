## SETUP PROJECT

This project involves using the https://swapi.dev api to search for characters, modifying a character to include a photo and the films associated with the character.
All this while completing the data with a symfony command

### 1. Clone Project
```
git clone git@github.com:Thomas905/starwarsinfotest.git
```

### 2. Install dependencies
```
compser install
```

### 3. Create database & Migration
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 4. Load fixtures
```
php bin/console starwars:import
```

### 5. Run server
```
symfony serve
```

### 6. URL
```
http://localhost:8000/peoples - List of characters
http://localhost:8000/people/{id} - Character for edit

http://localhost:8000/movies - List of movies
http://localhost:8000/movies/{id} - List of characters linked to the movie
```



