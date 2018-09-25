<?php
/**
 * FileCopier
 *
 * @package WP2Static
 */
class FileCopier {

    /**
     * TODO: if this fails to locate the local file for the remote,
     * TODO: ... it should fall back to regular crawl processing method
     * TODO: ... (where response status will also be checked in case of 404)
     */

    /**
     * Constructor
     *
     * @param string $url          URL
     * @param string $wp_site_url  Site URL
     * @param string $wp_site_path Site path
     */
    public function __construct( $url, $wp_site_url, $wp_site_path ) {
        $this->url = $url;
        $this->wp_site_url = $wp_site_url;
        $this->wp_site_path = $wp_site_path;
    }


    /**
     * Get local file for URL
     *
     * @return string
     */
    public function getLocalFileForURL() {
        /**
         * Take the public URL and return the location on the filesystem
         *
         * Ex: http://domain.com/wp-content/somefile.jpg
         *
         * replace the WP site url with the WP site path
         *
         * Ex: replace http://domain.com/ with /var/www/domain.com/html/
         *
         * resulting in
         *
         * Ex: /var/www/domain.com/html/wp-content/somefile.jpg
         */
        return( str_replace(
            $this->wp_site_url,
            $this->wp_site_path,
            $this->url
        ) );
    }


    /**
     * Copy file
     *
     * @param string $archiveDir Archive directory
     * @return mixed
     */
    public function copyFile( $archiveDir ) {
        $urlInfo = parse_url( $this->url );
        $pathInfo = array();

        $local_file = $this->getLocalFileForURL();

        /*
          $urlInfo['path'] will look like:

            (file with extension)

            [scheme] => http
            [host] => 172.18.0.3
            [path] => /wp-content/themes/twentyseventeen/assets/css/ie8.css

        */

        // TODO: here we can allow certain external host files to be crawled
        if ( ! isset( $urlInfo['path'] ) ) {
            return false;
        }

        $pathInfo = pathinfo( $urlInfo['path'] );

        // set fileDir to the directory name else empty
        $fileDir = $archiveDir . (
            isset( $pathInfo['dirname'] )
                ? $pathInfo['dirname']
                : ''
        );

        if ( ! file_exists( $fileDir ) ) {
            wp_mkdir_p( $fileDir );
        }

        $fileExtension = $pathInfo['extension'];

        $fileName = $fileDir . '/' . $pathInfo['filename'] . '.' .
            $fileExtension;

        copy( $local_file, $fileName );
    }
}
