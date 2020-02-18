<?php
/*
    WordPressSite

    WP2Static's representation of the WordPress site we are running in

*/

namespace WP2Static;

class WordPressSite {

    /**
     * Get URLs
     *
     * We don't store URLs within class, for performance
     *
     * @return string[] list of URLs in CrawlQueue
     */
    public static function getURLs() : array {
        $urls = CrawlQueue::getCrawlableURLs();

        return $urls;
    }

    /**
     * Clear detected URLs
     *
     * Reset the CrawlQueue
     *
     */
    public static function clearDetectedURLs() : bool {
        CrawlQueue::truncate();

        return true;
    }

    /**
     * Create  dir
     *
     * @param string $path static site directory
     * @throws WP2StaticException
     */
    private function create_directory( string $path ) : string {
        if ( is_dir( $path ) ) {
            return $path;
        }

        if ( ! mkdir( $path ) ) {
            $err = "Couldn't create archive directory:" . $path;
            WsLog::l( $err );
            throw new WP2StaticException( $err );
        }

        return $path;
    }
}

