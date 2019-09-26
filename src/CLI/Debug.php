<?php

namespace VE\Electro\CLI;

use WP_CLI;

class Debug
{
    protected function main()
    {
    }

    /**
     * Runs anything in main() method
     *
     * ## EXAMPLES
     *
     * $ wp electro debug
     *
     */
    public function __invoke()
    {
        WP_CLI::line('');
        WP_CLI::line(
            WP_CLI::colorize( "%YDEBUG START%n " )
        );
        WP_CLI::line('');

        $this->main();

        WP_CLI::line('');
        WP_CLI::line(
            WP_CLI::colorize( "%CDEBUG END%n " )
        );
    }
}
