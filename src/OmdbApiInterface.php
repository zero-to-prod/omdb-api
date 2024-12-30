<?php

namespace Zerotoprod\OmdbApi;

/**
 * A wrapper for https://www.omdbapi.com/
 */
interface OmdbApiInterface
{
    /**
     * Returns the Poster Image.
     *
     * @see  https://www.omdbapi.com/
     * @link https://github.com/zero-to-prod/omdb-api
     */
    public function poster(string $imdbID): string;

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
    ): array;

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
    ): array;
}