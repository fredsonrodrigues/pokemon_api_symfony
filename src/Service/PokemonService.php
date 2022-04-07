<?
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokemonService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    private function verifySuccess($statusCode)
    {
        switch ($statusCode) {
            case 404:
                return [
                    'success' => false,
                    'error' => 'not Found',
                    'statusCode' => $statusCode
                ];
            case 500:
                return [
                    'success' => false,
                    'error' => 'server Error',
                    'statusCode' => $statusCode
                ];
            case 429:
                return [
                    'success' => false,
                    'error' => 'too many requests to Pokemon API',
                    'statusCode' => $statusCode
                ];
            case 200:
                return [
                    'success' => true,
                    'error' => 'not Found',
                    'statusCode' => $statusCode
                ];
            default:
                return [
                    'success' => false,
                    'error' => 'unknown event',
                    'statusCode' => $statusCode
                ];
        }
    }

    private function orderAbilities($abilities)
    {
        usort($abilities, function($a, $b) {return strcmp($a['ability']['name'], $b['ability']['name']);});
        return $abilities;
    }

    public function getPokemonInfo(string $name): array
    {
        $response = $this->client->request(
            'GET',
            'https://pokeapi.co/api/v2/pokemon/'.$name
        );

        $statusCode = $response->getStatusCode();
        $status = $this->verifySuccess($statusCode);
        if (!$status['success']) {
            return [$status, $status['statusCode']];
        }

        $pokemon_info = $response->toArray();
        $pokemon_info['success'] = true;
        $pokemon_info['abilities'] = $this->orderAbilities($pokemon_info['abilities']);

        return [$pokemon_info, $status['statusCode']];
    }
}