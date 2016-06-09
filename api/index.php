<?php 
  require_once('../vendor/autoload.php'); 
  use Slim\Slim;
  class_alias('RedBeanPHP\Facade', 'R');

  $vga = new Slim();
  $vga->config(['debug' => FALSE]);

  // Hooks
  $vga->hook('slim.before.dispatch', function () {

    $dbhost = 'localhost';
    $dbname = 'video_game_inventory';
    $dbuser = 'video_gamer';
    $dbpass = 'mikeiscool!';

    R::setup("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
  });
  
  $vga->hook('slim.after.dispatch', function () {
    R::close();
  });

  $vga->image_path = '../src/img/';

  // Giant Bomb API variables
  $vga->giantbomb = new stdClass;
  $vga->giantbomb->api_key = 'Your Giant Bomb API Key goes here.';
  $vga->giantbomb->format = 'json';
  $vga->giantbomb->search_endpoint = 'http://www.giantbomb.com/api/search/';
  $vga->giantbomb->game_endpoint = 'http://www.giantbomb.com/api/game/';
  $vga->giantbomb->image_endpoint = 'http://static.giantbomb.com';
  $vga->giantbomb->search_field_list = 'id,name,original_release_date,platforms';
  $vga->giantbomb->game_field_list = 'deck,image,name,original_release_date,platforms,developers,genres';

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
  $vga->get('/giantbomb/search/:query', function ($query) use ($vga) {

    $url = $vga->giantbomb->search_endpoint .
      '?api_key=' . $vga->giantbomb->api_key . 
      '&field_list=' . $vga->giantbomb->search_field_list . 
      '&format=' . $vga->giantbomb->format .
      '&query=' . urlencode('"' . $query . '"');

    echo getAPIResponse($url);
  });

  // GiantBomb API route: View game
  $vga->get('/giantbomb/get/:id', function ($id) use ($vga) {

    $url = $vga->giantbomb->game_endpoint . "$id/" .
      '?api_key=' . $vga->giantbomb->api_key . 
      '&field_list=' . $vga->giantbomb->game_field_list . 
      '&format=' . $vga->giantbomb->format;

    echo getAPIResponse($url);
  });

  // View all games
  $vga->get('/games', function () {
    echo json_encode(R::getAll('SELECT * FROM game'), JSON_NUMERIC_CHECK);
  });

  // Add game
  $vga->post('/games', function () use ($vga) {

    $game = R::dispense('game');
    $game->import(json_decode(file_get_contents('php://input'), TRUE));

    // Check for duplicate
    if (0 === R::count('game', 'title = ? AND system = ?', [$game->title, $game->system])) :

      $game->created_at = NULL;
      $game->updated_at = NULL;

      if (filter_var($game->image, FILTER_VALIDATE_URL)) :
        
        // Retain state of URL
        $filename = basename($game->image);
        $url = $game->image;
        
        // Update image
        $game->image = $filename;
        
        $id = (int) R::store($game);

        if (! downloadAndSaveFile($vga->image_path . $id . '/' . $filename, $url)) :
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
    $data = json_decode(file_get_contents('php://input'), TRUE);
    
    // @TODO These amount of variables I'm dealing with needs to change should I
    // decide to update more or all columns
    $game->import($data, 'notes,completed');
    
    unset($game->updated_at); // Allow MySQL to update the TIMESTAMP on this column

    R::store($game);
  });

  // Delete game
  $vga->delete('/games/:id', function ($id) use ($vga) {
    $game = R::load('game', $id);

    // Remove image
    $folder = $vga->image_path . $game->id;
    $image = $folder . '/' . $game->image;

    if (is_dir($folder) && is_file($image)) :
      unlink($image);
      rmdir($folder);
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
    if (NULL === $favorite_system_data) :
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
    if (NULL === $last_game_data) :
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
AND genre IS NOT NULL
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
    $genres_counted = array_slice($genres_counted, 0, 15, TRUE);
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

  // Wrappers for cURL requests
  function getAPIResponse ($url) {

    $ch = curl_init();

    curl_setopt_array($ch, [
      CURLOPT_SSL_VERIFYPEER => FALSE,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_TIMEOUT => 20,
      CURLOPT_URL => $url,
      CURLOPT_CONNECTTIMEOUT => 20,
      CURLOPT_USERAGENT => 'Video Game Inventory by mykisscool' // Otherwise GiantBomb will think you are a scraper
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
  }

  function downloadAndSaveFile ($path, $url) {

    if (! is_dir(dirname($path))) :
      mkdir(dirname($path), 0755, TRUE);
    endif;

    $fp = fopen($path, 'w+');
    $ch = curl_init();

    curl_setopt_array($ch, [
      CURLOPT_SSL_VERIFYPEER => FALSE,
      CURLOPT_RETURNTRANSFER => FALSE,
      CURLOPT_BINARYTRANSFER => TRUE,
      CURLOPT_TIMEOUT => 20,
      CURLOPT_CONNECTTIMEOUT => 20,
      CURLOPT_URL => $url,
      CURLOPT_FILE=> $fp
    ]);

    curl_exec($ch);

    $return = ((curl_errno($ch)) ? FALSE : TRUE);

    curl_close($ch);
    fclose($fp);

    return $return;
  }
