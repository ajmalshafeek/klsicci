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

// ID of the custom channel to be deleted.
define('CUSTOM_CHANNEL_ID', 'INSERT_CUSTOM_CHANNEL_ID_HERE');

/**
 * This example deletes a custom channel on a host ad client.
 *
 * To get ad clients, see GetAllAdClientsForHost.php.
 * To get custom channels, see GetAllCustomChannelsForHost.php.
 * Tags: customchannels.delete
 *
 * @author Sérgio Gomes <sgomes@google.com>
 */
class DeleteCustomChannelOnHost extends BaseExample {
  public function render() {
    $adClientId = HOST_AD_CLIENT_ID;
    $customChannelId = CUSTOM_CHANNEL_ID;

    // Retrieve custom channels list, and display it.
    $result = $this->adSenseHostService->customchannels
        ->delete($adClientId, $customChannelId);
    $mainFormat = 'Custom channel with ID "%s" was deleted.';
    $content = sprintf($mainFormat, $result['id']);
    print $content;
  }
}

