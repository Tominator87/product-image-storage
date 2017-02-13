<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Queue;

interface QueueClientConnectionInterface
{

    /**
     * @return bool
     */
    public function connect();

    /**
     * @return bool
     */
    public function disconnect();

    /**
     * @return bool
     */
    public function isConnected();
}
