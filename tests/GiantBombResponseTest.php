<?php

class GiantBombApiResponseTest extends PHPUnit\Framework\TestCase
{
  private $client;

  public function setUp()
  {
    if (file_exists(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env')) {
      $dotEnv = new Dotenv\Dotenv(dirname(__DIR__));
      $dotEnv->load();
    }

    $this->client = new GuzzleHttp\Client([
      'base_uri' => 'https://www.giantbomb.com/api/',
      [
          'curl' => [
              CURLOPT_SSL_VERIFYPEER => false
          ]
      ]
    ]);
  }

  public function testSearchGame()
  {
    $response = $this->client->get('search', [
      'timeout' => 20,
      'connect_timeout' => 20,
      'headers' => [
        'User-Agent' => 'Video Game Inventory by mykisscool'
      ],
      'query' => [
        'api_key' => "test",
        'format' => 'json',
        'fields' => 'id,name,original_release_date,platforms',
        'query' => 'Mega Man 4'
      ]
    ]);

    $data = json_decode($response->getBody(), true);
    $row = $data['results'][0]; // Grab first result

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertArrayHasKey('id', $row);
    $this->assertArrayHasKey('name', $row);
    $this->assertArrayHasKey('original_release_date', $row);
    $this->assertArrayHasKey('platforms', $row);

    return $row['id'];
  }

  /**
   * @depends testSearchGame
   */
  public function testGetGameDetails($id)
  {
    $response = $this->client->get("game/$id", [
      'timeout' => 20,
      'connect_timeout' => 20,
      'headers' => [
        'User-Agent' => 'Video Game Inventory by mykisscool'
      ],
      'query' => [
        'api_key' => getenv('GIANTBOMB_API_KEY'),
        'format' => 'json',
        'fields' => 'deck,image,name,original_release_date,platforms,developers,genres',
      ]
    ]);

    $data = json_decode($response->getBody(), true)['results'];

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertArrayHasKey('deck', $data);
    $this->assertArrayHasKey('image', $data);
    $this->assertArrayHasKey('name', $data);
    $this->assertArrayHasKey('original_release_date', $data);
    $this->assertArrayHasKey('platforms', $data);
    $this->assertArrayHasKey('developers', $data);
    $this->assertArrayHasKey('genres', $data);
  }
}
