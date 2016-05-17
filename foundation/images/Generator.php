<?php

namespace livetyping\hermitage\foundation\images;

use Carbon\Carbon;
use livetyping\hermitage\foundation\contracts\images\Generator as GeneratorContract;
use frostealth\Helpers\ShortId;

/**
 * Class Generator
 *
 * @package livetyping\hermitage\foundation\images
 */
final class Generator implements GeneratorContract
{
    /**
     * @return string
     */
    public function name(): string
    {
        return substr(uniqid(), -4);
    }

    /**
     * @return string
     */
    public function dirname(): string
    {
        $date = Carbon::now();
        $quarter = $date->copy()->firstOfQuarter()->format('Ym');
        $secondOfHour = $date->copy()->minute(0)->second(0)->diffInSeconds($date);

        $parts = [];
        $parts[] = ShortId::encode($quarter);
        $parts[] = $date->day;
        $parts[] = ShortId::encode($secondOfHour);
        $parts[] = uniqid();

        return implode('/', $parts);
    }

    /**
     * @return string
     */
    public function path(): string
    {
        $path = $this->dirname() . '/' . $this->name();
        
        return trim($path, '/');
    }
}
