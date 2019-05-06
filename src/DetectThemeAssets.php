<?php

namespace WP2Static;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class DetectThemeAssets {

    public static function detect( $theme_type ) {
        $wp_site = new WPSite();

        $files = array();
        $template_path = '';
        $template_url = '';

        if ( $theme_type === 'parent' ) {
            $template_path = $wp_site->parent_theme_path;
            $template_url = get_template_directory_uri();
        } else {
            $template_path = $wp_site->child_theme_path;
            $template_url = get_stylesheet_directory_uri();
        }

        $directory = $template_path;

        if ( is_dir( $directory ) ) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $directory,
                    RecursiveDirectoryIterator::SKIP_DOTS
                )
            );

            foreach ( $iterator as $filename => $file_object ) {
                $path_crawlable =
                    FilesHelper::filePathLooksCrawlable( $filename );

                $detected_filename =
                    str_replace(
                        $template_path,
                        $template_url,
                        $filename
                    );

                $detected_filename =
                    str_replace(
                        get_home_url(),
                        '',
                        $detected_filename
                    );

                if ( $path_crawlable ) {
                    array_push(
                        $files,
                        $detected_filename
                    );
                }
            }
        }

        return $files;
    }
}
