<?php
/*
 * Copyright 2012 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// Require the base class.
require_once __DIR__ . "/../BaseExample.php";

/**
 * This example gets all URL channels in a host ad client.
 *
 * To get ad clients, see GetAllAdClientsForHost.php.
 * Tags: urlchannels.list
 *
 * @author Sérgio Gomes <sgomes@google.com>
 * @author Silvano Luciani <silvano.luciani@gmail.com>
 */
class GetAllUrlChannelsForHost extends BaseExample {
  public function render() {
    $adClientId = HOST_AD_CLIENT_ID;
    $optParams['maxResults'] = MAX_PAGE_SIZE;
    $listClass = 'list';
    printListHeader($listClass);
    $pageToken = null;
    do {
      $optParams['pageToken'] = $pageToken;
      // Retrieve URL channels list and display it.
      $result = $this->adSenseHostService->urlchannels
          ->listUrlchannels($adClientId, $optParams);
      $urlChannels = $result['items'];
      if (isset($urlChannels)) {
        foreach ($urlChannels as $urlChannel) {
          $format = 'URL channel with ID "%s" and URL pattern "%s" was found.';
          $content = sprintf($format, $urlChannel['id'],
              $urlChannel['urlPattern']);
          printListElement($content);
        }
        $pageToken = isset($result['nextPageToken']) ? $result['nextPageToken']
            : null;
      } else {
        printNoResultForList();
      }
    } while ($pageToken);
    printListFooter();
  }
}

