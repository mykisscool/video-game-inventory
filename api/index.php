<?php
  require_once('../vendor/autoload.php');
  class_alias('RedBeanPHP\Facade', 'R');

  $dotEnv = new Dotenv\Dotenv(dirname(__DIR__));
  $dotEnv->load();

  $vga = new Slim\Slim();
  $vga->config(['debug' => getenv('DEBUG')]);

  // Hooks
  $vga->hook('slim.before.dispatch', function () {
    R::setup('mysql:host=' . getenv('DBHOST_WEB') . ';dbname=' . getenv('DBNAME') .
      ';port=' . getenv('DBPORT'), getenv('DBUSER'), getenv('DBPASS'));
  });

  $vga->hook('slim.after.dispatch', function () {
    R::close();
  });

  // Override routes
  $vga->notFound(function () {
    echo json_encode([
      'error' => 'Page not found'
    ]);
  });

  $vga->error(function () {
    echo json_encode([
      'error' => 'An error has occurred'
    ]);
  });

  // GiantBomb API route: Search
  $vga->get('/giantbomb/search/:query', function ($query) {

    $params = [
      'api_key' => getenv('GIANTBOMB_API_KEY'),
      'field_list' => 'id,name,original_release_date,platforms',
      'format' => 'json',
      'query' => '"' . $query . '"'
    ];

    echo getAPIResponse('search', $params);
  });

  // GiantBomb API route: Game
  $vga->get('/giantbomb/get/:id', function ($id) {

    $params = [
      'api_key' => getenv('GIANTBOMB_API_KEY'),
      'field_list' => 'deck,image,name,original_release_date,platforms,developers,genres',
      'format' => 'json'
    ];

    echo getAPIResponse("game/$id", $params);
  });

  // View all games
  $vga->get('/games', function () {
    echo json_encode(R::getAll('SELECT * FROM game'), JSON_NUMERIC_CHECK);
  });

  // Add game
  $vga->post('/games', function () use ($vga) {

    $game = R::dispense('game');
    $game->import(json_decode(file_get_contents('php://input'), true));

    // Check for duplicate
    if (0 === R::count('game', 'title = ? AND system = ?', [$game->title, $game->system])) :

      $game->created_at = null;
      $game->updated_at = null;

      if (filter_var($game->image, FILTER_VALIDATE_URL)) :

        // Retain state of URL
        $filename = urldecode(basename($game->image));
        $url = urldecode($game->image);

        // Update image
        $game->image = $filename;

        $id = (int) R::store($game);

        if (! downloadAndSaveFile($id . '/' . $filename, $url)) :
          // @TODO If the image did not download- update the database and filesystem accordingly
        endif;

        // @TODO json_encode these responses
        echo $id;
      else :
        echo (int) R::store($game);
      endif;
    else :
      echo 'already exists in your inventory';
    endif;
  });

  // Edit game
  $vga->put('/games/:id', function ($id) {

    $game = R::load('game', $id);
    $data = json_decode(file_get_contents('php://input'), true);

    // @TODO These amount of variables I'm dealing with needs to change should I
    // decide to update more or all columns
    $game->import($data, 'notes,completed');

    unset($game->updated_at); // Allow MySQL to update the TIMESTAMP on this column

    R::store($game);
  });

  // Delete game
  $vga->delete('/games/:id', function ($id) {
    $game = R::load('game', $id);

    // Remove image
    $fullPath = dirname(realpath(dirname(__FILE__))) . getenv('APP_PATH') . 'src/img/' . $game->id;
    $image = $fullPath . '/' . $game->image;

    if (is_dir($fullPath) && is_file($image)) :
      unlink($image);
      rmdir($fullPath);
    endif;

    R::trash($game);
  });

  // Dashboard widgets
  $vga->get('/dashboard/widgets', function () {

    $data = new stdClass;
    $data->number_of_games = R::count('game');
    $data->number_of_games_beaten = R::count('game', 'completed = 1');

    // Set default state of application if no games are added yet
    if (0 === $data->number_of_games) :
      $data->percentage_games_beaten = 0;
    else :
      $data->percentage_games_beaten = round((($data->number_of_games_beaten / $data->number_of_games) * 100), 2);
    endif;

    $data->number_of_systems = (int) R::getCell('SELECT COUNT(DISTINCT system) FROM game');

    // Favorite system
    $favorite_system_sql = <<<EOD
SELECT COUNT(id) AS num_games, system
FROM game
GROUP BY system
ORDER BY num_games DESC
LIMIT 1
EOD;
    $favorite_system_data = R::getRow($favorite_system_sql);

    // Set default state of application if no games are added yet
    if (null === $favorite_system_data) :
      $data->favorite_system_games = 0;
      $data->favorite_system = '-';
    else :
      $data->favorite_system_games = $favorite_system_data['num_games'];
      $data->favorite_system = $favorite_system_data['system'];
    endif;

    // Last game added
    $last_game_added_sql = <<<EOD
SELECT title, DATE_FORMAT(created_at, '%b %D, %Y') AS created_at
FROM game
WHERE created_at = (SELECT MAX(created_at) FROM game)
LIMIT 1
EOD;
    $last_game_data = R::getRow($last_game_added_sql);

    // Set default state of application if no games are added yet
    if (null === $last_game_data) :
      $data->last_game_added = '-';
      $data->last_game_added_date = '';
    else :
      $data->last_game_added = $last_game_data['title'];
      $data->last_game_added_date = $last_game_data['created_at'];
    endif;

    // Favorite genre
    $genres = R::getCol('SELECT genre FROM game');
    $array_genres = [];

    foreach ($genres as $genre) :

      // Since we can have multiple genres for a game & they're stored in a comma-delimted list in the database,
      // we need to explode that list and use PHP to determine the most common genre
      $array_game_genres = explode(',', $genre);

      foreach ($array_game_genres as $game_genre) :
        $array_genres[] = $game_genre;
      endforeach;
    endforeach;

    $array_aggregate_genres = array_count_values($array_genres);

    // Set default state of application if no games are added yet
    if (empty($array_aggregate_genres)) :
      $data->favorite_genre_games = 0;
      $data->favorite_genre = '-';
    else :
      $data->favorite_genre_games = max($array_aggregate_genres);
      $data->favorite_genre = array_search($data->favorite_genre_games, $array_aggregate_genres);
    endif;

    echo json_encode($data);
  });

  // Dashboard: Genres chart data
  $vga->get('/dashboard/genres', function () {

    $sql = <<<EOD
SELECT genre
FROM game
WHERE genre != ''
AND genre IS NOT null
EOD;
    $genres = R::getCol($sql);

    $genres_pivoted = [];
    foreach ($genres as $genre) :
      $genre_array = explode(',', $genre);
      foreach ($genre_array as $genre) :
        $genres_pivoted[] = $genre;
      endforeach;
    endforeach;

    $genres_counted = [];
    foreach ($genres_pivoted as $genre) :
      $genres_counted[$genre] = count(array_keys($genres_pivoted, $genre));
    endforeach;

    // Return top 15 results
    $genres_counted = array_slice($genres_counted, 0, 15, true);
    arsort($genres_counted);

    $data = new stdClass;
    $data->labels = [];
    $data->series = [];

    foreach ($genres_counted as $genre => $count) :
      $data->series[] = (int) $count;
      $data->labels[] = $genre;
    endforeach;

    echo json_encode($data);
  });

  // Dashboard: Systems chart data
  $vga->get('/dashboard/systems', function () {

    $sql = <<<EOD
SELECT COUNT(id) AS num_games, system
FROM game
GROUP BY system
ORDER BY num_games DESC
LIMIT 15
EOD;
    $results = R::getAll($sql);
    $data = new stdClass;
    $data->labels = [];
    $data->series = [];

    foreach ($results as $result) :
      $data->series[] = (int) $result['num_games'];
      $data->labels[] = $result['system'];
    endforeach;

    echo json_encode($data);
  });

  // Dashboard: Timeline chart data
  $vga->get('/dashboard/timeline', function () {

    // Create a range of years your games were released
    $sql_years_range = <<<EOD
SELECT MIN(YEAR(released_on)) AS min_year_released, YEAR(CURDATE()) AS max_year_released
FROM game
EOD;

    $sql_games_count = <<<EOD
SELECT COUNT(id) AS num_games
FROM game
WHERE YEAR(released_on) = :year_released
EOD;
    $results = R::getRow($sql_years_range);

    // Loop over the years and get the number of games released that year
    $data = new stdClass;
    $data->labels = range($results['min_year_released'], $results['max_year_released']);
    $data->series = [];

    foreach ($data->labels as $year) :
      $results = R::getRow($sql_games_count, [':year_released' => $year]);
      $data->series[] = (int) $results['num_games'];
    endforeach;

    $data->labels = array_map('strval', $data->labels); // Convert labels to strings
    $json = json_encode($data);
    echo $json;
  });

  $vga->run();

  // Wrappers for API requests
  function getAPIResponse ($endpoint, $params) {

    $client = new GuzzleHttp\Client([
      'base_uri' => 'https://www.giantbomb.com/api/'
    ]);

    $response = $client->get($endpoint, [
      'timeout' => 20,
      'connect_timeout' => 20,
      'headers' => [
        'User-Agent' => 'Video Game Inventory by mykisscool'
      ],
      'query' => $params
    ]);

    return $response->getBody();
  }

  function downloadAndSaveFile ($localPath, $imageUrl) {

    $fullPath = dirname(realpath(dirname(__FILE__))) . getenv('APP_PATH') . 'src/img/' . $localPath;
    $client = new GuzzleHttp\Client();

    if (! is_dir(dirname($fullPath))) :
      mkdir(dirname($fullPath), 0755, true);
    endif;

    try {
      $response = $client->get(str_replace(' ', '%20', $imageUrl), [
        'timeout' => 20,
        'connect_timeout' => 20,
        'headers' => [
          'User-Agent' => 'Video Game Inventory by mykisscool'
        ],
        'save_to' => fopen($fullPath, 'w+')
      ]);

      return true;
    }
    catch (Exception $e) {
      return false;
    }
  }
