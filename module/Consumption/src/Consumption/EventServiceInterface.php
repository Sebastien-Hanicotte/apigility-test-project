<?php

namespace Consumption;

/**
 * interface EventServiceInterface
 */
interface EventServiceInterface
{
    /**
	 * consume $events starting from $cursor
	 *
	 * @param array  $event
	 * @param string $cursor
	 */
    public function consume(array $events, $cursor);
}
