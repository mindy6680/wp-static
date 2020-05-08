<?php

namespace WP2Static;

class Addons {
    public static function createTable() : void {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wp2static_addons';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            slug VARCHAR(255) NOT NULL,
            type VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            docs_url VARCHAR(2083) NOT NULL,
            description VARCHAR(255) NOT NULL,
            enabled TINYINT(1) UNSIGNED DEFAULT 0 NOT NULL,
            PRIMARY KEY  (slug)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    public static function registerAddon(
        string $slug,
        string $type,
        string $name,
        string $docs_url,
        string $description
    ) : void {
        // TODO: guard against unknown addon type

        global $wpdb;

        $table_name = $wpdb->prefix . 'wp2static_addons';

        $sql = "INSERT INTO {$table_name} (slug,type,name,docs_url,description)" .
            ' VALUES (%s,%s,%s,%s,%s)';

        $sql = $wpdb->prepare( $sql, $slug, $type, $name, $docs_url, $description );

        $wpdb->query( $sql );
    }

    /**
     * Get all Addons
     *
     * @return mixed[] array of Addon objects
     */
    public static function getAll() : array {
        global $wpdb;
        $addons = [];

        $table_name = $wpdb->prefix . 'wp2static_addons';

        $addons = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY type DESC" );

        return $addons;
    }

    /**
     *  Clear Addons via truncation
     */
    public static function truncate() : void {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wp2static_addons';

        $wpdb->query( "TRUNCATE TABLE $table_name" );

        self::l( 'Deleted all Addons' );
    }
}

