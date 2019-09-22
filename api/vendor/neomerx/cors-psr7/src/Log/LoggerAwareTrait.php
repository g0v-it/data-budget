<?php namespace Neomerx\Cors\Log;

/**
 * Copyright 2015 info@neomerx.com (www.neomerx.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use \Psr\Log\LoggerAwareTrait as PsrLoggerAwareTrait;

/**
 * @package Neomerx\Cors
 */
trait LoggerAwareTrait
{
    use PsrLoggerAwareTrait;

    /**
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    protected function logDebug($message, array $context = [])
    {
        $this->hasLogger() === true ?: $this->logger->debug($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    protected function logInfo($message, array $context = [])
    {
        $this->hasLogger() === true ?: $this->logger->info($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    protected function logWarning($message, array $context = [])
    {
        $this->hasLogger() === true ?: $this->logger->warning($message, $context);
    }

    /**
     * @return bool
     */
    protected function hasLogger()
    {
        return $this->logger === null;
    }
}
