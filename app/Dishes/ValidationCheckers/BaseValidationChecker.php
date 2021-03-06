<?php

namespace App\Dishes\ValidationCheckers;

abstract class BaseValidationChecker
{
    protected $content;

    protected $lines;

    /**
     * BaseValidationChecker constructor.
     *
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content;

        $lines = preg_split('/\n|\r\n?/', $content);

        $this->lines = $this->sanitizeContent($lines);
    }

    /**
     * Perform the check.
     *
     * @return mixed
     */
    abstract public function check();

    /**
     * Sanitize the lines by removing empty lines.
     *
     * @param $lines
     *
     * @return mixed
     */
    private function sanitizeContent($lines)
    {
        return collect($lines)->reject(function ($line) {
            return ! $line;
        })
            ->values()
            ->toArray();
    }
}
