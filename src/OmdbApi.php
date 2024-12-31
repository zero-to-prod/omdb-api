<?php

namespace Zerotoprod\OmdbApi;

use Zerotoprod\Omdb\Omdb;

/**
 * A wrapper for https://www.omdbapi.com/
 */
class OmdbApi implements OmdbApiInterface
{
    /**
     * @var string
     */
    public $apikey;
    /**
     * @var string
     */
    public $base_url;
    /**
     * @var string
     */
    public $img_url;

    /**
     * Instantiates a new instance
     *
     * @param  string  $apikey
     * @param  string  $base_url
     * @param  string  $img_url
     *
     * @link https://github.com/zero-to-prod/omdb-api
     */
    public function __construct(
        string $apikey,
        string $base_url = 'https://www.omdbapi.com/',
        string $img_url = 'https://img.omdbapi.com/'
    ) {
        $this->apikey = $apikey;
        $this->base_url = $base_url;
        $this->img_url = $img_url;
    }

    /**
     * Returns the Poster Image.
     *
     * @see  https://www.omdbapi.com/
     * @link https://github.com/zero-to-prod/omdb-api
     */
    public function poster(string $imdbID): string
    {
        return $this->img_url.'?'.http_build_query(['apikey' => $this->apikey, 'i' => $imdbID]);
    }

    /**
     * Retrieve detailed information about a specific movie, TV series, or episode by either its IMDb ID or title.
     *
     * @param  string|null  $title      *Optional. Production title to search for (e.g. 'Avatar'). *Either `$t` or `$i` must be specified.
     * @param  string|null  $imdbID     *Optional. A valid IMDb ID (e.g. 'tt1285016'). *Either `$t` or `$i` must be specified.
     * @param  string|null  $type       Optional. Type of result to return (movie, series, episode)
     * @param  int|null     $year       Optional. Year of release.
     * @param  bool         $full_plot  Optional. Return short or full plot.
     * @param  mixed|null   $callback   Optional. JSONP callback name.
     * @param  string|null  $version    Optional. API version (reserved for future use).
     * @param  array|null   $CURLOPT    cURL options.
     *
     * @return array{
     *     Title: string,
     *     Year: string,
     *     Rated: string,
     *     Released: string,
     *     Runtime: string,
     *     Genre: string,
     *     Director: string,
     *     Writer: string,
     *     Actors: string,
     *     Plot: string,
     *     Language: string,
     *     Country: string,
     *     Awards: string,
     *     Poster: string,
     *     Ratings?: array<array{ Source: string, Value: string}>,
     *     Metascore: int,
     *     imdbRating: float,
     *     imdbVotes: string,
     *     imdbID: string,
     *     Type: string,
     *     DVD: string,
     *     BoxOffice: string,
     *     Production: string,
     *     Website: string,
     *     Response: bool
     * }|array{
     *     ErrorType: string,
     *     message: string,
     *     extra?: mixed
     * }
     *
     *
     * @see  https://www.omdbapi.com/
     * @link https://github.com/zero-to-prod/omdb-api
     */
    public function byIdOrTitle(
        ?string $title = null,
        ?string $imdbID = null,
        ?string $type = null,
        ?int $year = null,
        ?bool $full_plot = false,
        mixed $callback = null,
        ?string $version = null,
        ?array $CURLOPT = [CURLOPT_TIMEOUT => 10]
    ): array {
        if (!$title && !$imdbID) {
            return [
                'ErrorType' => 'validation',
                'message' => 'Either $title or $imdbID must be provided.',
            ];
        }

        return $this->curl(
            $CURLOPT + [
                CURLOPT_URL => $this->base_url.'?'.http_build_query(
                        array_filter([
                            'i' => $imdbID,
                            't' => $title,
                            'type' => $type,
                            'y' => $year,
                            'plot' => $full_plot ? 'full' : null,
                            'r' => 'json',
                            'callback' => $callback,
                            'v' => $version,
                            'apikey' => $this->apikey,
                        ])
                    ),
                CURLOPT_RETURNTRANSFER => true,
            ]
        );
    }

    /**
     * Search for multiple titles using a keyword.
     *
     * @param  string       $title     Required. Production title to search for (e.g. 'Avatar').
     * @param  string|null  $type      Optional. Type of result to return (movie, series, episode)
     * @param  int|null     $year      Optional. Year of release.
     * @param  int|null     $page      Optional. Search page number.
     * @param  mixed|null   $callback  Optional. JSONP callback name.
     * @param  string|null  $version   Optional. API version (reserved for future use).
     * @param  array|null   $CURLOPT   cURL options.
     *
     * @return array{
     *     Search: array<array{
     *         Title: string,
     *         Year: string,
     *         imdbID: string,
     *         Type: string,
     *         Poster: string
     *     }>,
     *     totalResults: int,
     *     Response: bool
     * }|array{
     *     ErrorType: string,
     *     message: string,
     *     extra?: mixed
     * }
     *
     * @see  https://www.omdbapi.com/
     * @link https://github.com/zero-to-prod/omdb-api
     */
    public function search(
        string $title,
        ?string $type = null,
        ?int $year = null,
        ?int $page = 1,
        mixed $callback = null,
        ?string $version = null,
        ?array $CURLOPT = [CURLOPT_TIMEOUT => 10]
    ): array {
        return $this->curl(
            $CURLOPT + [
                CURLOPT_URL => $this->base_url.'?'.http_build_query(
                        array_filter([
                            's' => $title,
                            'type' => $type,
                            'y' => $year,
                            'r' => 'json',
                            'page' => $page,
                            'callback' => $callback,
                            'v' => $version,
                            'apikey' => $this->apikey,
                        ])
                    ),
                CURLOPT_RETURNTRANSFER => true
            ]
        );
    }

    private function curl(array $options = []): array
    {
        $ch = curl_init();
        if ($ch === false) {
            return [
                'ErrorType' => 'curl',
                'message' => 'Unable to initialize cURL.',
            ];
        }

        foreach ($options as $option => $value) {
            curl_setopt($ch, $option, $value);
        }
        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);

            return [
                'ErrorType' => 'curl',
                'message' => $error,
            ];
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 200) {
            return [
                'ErrorType' => 'curl',
                'message' => 'Unexpected HTTP code.',
                'extra' => $code,
            ];
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'ErrorType' => 'server',
                'message' => 'Invalid JSON returned from server.',
                'extra' => json_last_error_msg(),
            ];
        }

        if (!empty($data['Response']) && $data['Response'] === 'False') {
            return [
                'ErrorType' => 'server',
                'message' => $data['Error'] ?? 'Unknown server error.',
                'extra' => $data,
            ];
        }

        return $data;
    }
}