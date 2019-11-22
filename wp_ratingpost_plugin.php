<?php
/*
 * Plugin Name: Оценка статей
 * Description: Плагин для выставления и получения оценок статьи
 * Author:      SVteam
 * Version:     1.0
 */

require_once plugin_dir_path(__FILE__) . 'includes/models/Rating.php';

add_action('admin_menu', 'createLinkOnMainMenuRating');
register_activation_hook( __FILE__, 'createDatabasePostRating');

add_action('rest_api_init', function () {
  register_rest_route( 'rating', 'set',array(
                'methods'  => 'GET',
                'callback' => 'setRating'
      ));
});

add_action('rest_api_init', function () {
  register_rest_route( 'rating', 'get',array(
                'methods'  => 'GET',
                'callback' => 'getRating'
      ));
});

function createDatabasePostRating()
{
    global $table_prefix, $wpdb;
    $table_prefix = 'wp_vpSCDZ_';//Вот такой костыль, я хз как по-другому сделать

    $tblname = 'postrating';
    $wp_track_table = $table_prefix . "$tblname ";

    if($wpdb->get_var( "show tables like $wp_track_table" ) != $wp_track_table) 
    {
        $sql = "CREATE TABLE $wp_track_table (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `post_id` bigint(20) UNSIGNED NOT NULL,
            `positive` smallint(3) UNSIGNED NOT NULL DEFAULT '0',
            `negative` smallint(3) UNSIGNED NOT NULL DEFAULT '0',
            `comment` json DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `post_id` (`post_id`)
        );";

        $wpdb->get_results($sql);

        $set_link = "ALTER TABLE $wp_track_table ADD CONSTRAINT `post_ratings` FOREIGN KEY (`post_id`) REFERENCES `wp_posts` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE";

        $wpdb->get_results($set_link);
    }
}

function createLinkOnMainMenuRating()
{
    add_menu_page(
        'Рейтинги статей',
        'Рейтинги статей',
        'edit_others_posts',
        '/wp_ratingpost_plugin/includes/index.php',
        null,
        'dashicons-thumbs-up'
    );
}

function getRating($data) {
    $post_id = $data['post_id'];

    $rating = Rating::find($post_id);

    if(empty($rating)) {
        $rating = new Rating(['post_id' => $post_id]);
    }

    return $rating->getValues();
}

function setRating($data) {
    $post_id = $data['post_id'];
    $value = $data['value'];
    $comment = $data['comment'];

    $func = 'set' . ucfirst($value);

    $rating = Rating::find($post_id);

    if(!empty($rating)) {
        $rating->setComment($comment);
        $rating->$func();
        $rating->update();
    } else {
        $rating = new Rating(['post_id' => $post_id]);
        $rating->setComment($comment);
        $rating->$func();
        $rating->save();
    }

    return $rating;
}