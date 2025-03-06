<?php

namespace Drupal\anytown;

/**
 * Interface for the Forecast Client service.
 */
interface ForecastClientInterface {

  /**
   * Fetches weather forecast data from a given URL.
   *
   * @param string $url
   *   The API endpoint URL.
   *
   * @return array|null
   *   The weather data as an associative array or NULL if fetching fails.
   */
  public function getForecastData(string $url): ?array;

}
