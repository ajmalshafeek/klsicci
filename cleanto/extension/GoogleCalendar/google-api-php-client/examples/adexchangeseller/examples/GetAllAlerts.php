<?php
/*
 * Copyright 2013 Google Inc.
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

// Require the base class
require_once __DIR__ . "/../BaseExample.php";

/**
 * Gets all alerts available for the logged in user's account.
 *
 * Tags: alerts.list
 *
 * @author Sérgio Gomes <sgomes@google.com>
 */
class GetAllAlerts extends BaseExample {
  public function render() {
    $listClass = 'list';
    printListHeader($listClass);
    // Retrieve alert list, and display it.
    $result = $this->adExchangeSellerService->alerts->listAlerts();
    if (isset($result['items'])) {
      $alerts = $result['items'];
      foreach ($alerts as $alert) {
        $format = 'Alert id "%s" with severity "%s" and type "%s" was found.';
        $content = sprintf(
            $format, $alert['id'], $alert['severity'], $alert['type']);
        printListElement($content);
      }
    } else {
      printNoResultForList();
    }
    printListFooter();
  }
}

