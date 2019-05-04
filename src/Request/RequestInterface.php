<?php


namespace DHL\Request;

/**
 * Interface RequestInterface
 *
 * @package DHL\Request
 */
Interface RequestInterface
{
    /**
     * Prepare array to send in post
     *
     * @return array
     */
    public function toArray();
}
