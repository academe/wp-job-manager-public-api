<?php
/**
 * Plugin Name: WP Job Manager - Public API
 * Plugin URI: http://www.academe.co.uk/
 * Description: WP Plugin to expose non-sensitive WP Job Manager job details through the WP REST API.
 * Version: 1.0.0
 * Author: Academe Computing
 * Author URI: http://www.academe.co.uk/
 * Text Domain: wp-job-manager-public-api
 * Domain Path: /languages
 * License: GPLv2 or later
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * API Access to job titles and some other limited data.
 */
add_action('rest_api_init', function () {
    $api_route = 'wpjm_public/v1';

    $post_status = 'publish';
    $post_type = 'job_listing';

    register_rest_route($api_route, '/job/(?P<id>\d+)',[
        'methods' => 'GET',
        'callback' => function(WP_REST_Request $request) use ($post_status, $post_type) {
            // The ID of the job we want.
            $post_id = $request->get_param('id');

            // Get the job.
            $post = get_post($post_id);

            // Return the post details.
            if ($post->post_type == $post_type && $post->post_status == $post_status) {
                return [
                    $post->ID => [
                        'post_title' => $post->post_title,
                        'post_name' => $post->post_name,
                        'permalink' => get_permalink($post),
                        'post_date_gmt' => $post->post_date_gmt,
                        'post_status' => $post->post_status,
                        'guid' => $post->guid,
                    ]
                ];
            }
            // No post or the wrong type of post.
            return new WP_Error('invalid_job', 'Invalid job', ['status' => 404]);
        },
        'args' => [
            'id',
        ],
    ]);

    /**
     * The callback function, used over several similar routes.
     */
    $callback = function(WP_REST_Request $request) use ($post_status, $post_type) {
        // Just an assumption this will be okay for now.
        $posts_per_page = 1000;

        // Dates are inclusive and can use words, but replace spaces with underscores,
        // for example "this_month" (thought they don't seem to work).
        $after = str_replace('_', ' ', $request->get_param('date_from', ''));
        $before = str_replace('_', ' ', $request->get_param('date_to', ''));

        $args = [
            'post_status' => $post_status,
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
        ];

        if ($before || $after) {
            // Make sure the dates are inclusive, so /2017/2017 will give all
            // jobs for 2017, which is more intuitive.
            $args['date_query'] = ['inclusive' => true];

            if ($before) {
                $args['date_query']['before'] = $before;
            }

            if ($after) {
                $args['date_query']['after'] = $after;
            }
        }

        $posts = get_posts($args);

        $data = [];

        foreach($posts as $post) {
            $data[$post->ID] = [
                'post_title' => $post->post_title,
                'post_name' => $post->post_name,
                'permalink' => get_permalink($post),
                'post_date_gmt' => $post->post_date_gmt,
                'post_status' => $post->post_status,
                'guid' => $post->guid,
            ];
        }

        return $data;
    };

    /**
     * No from or to dates. Defaults to "latest".
     */
    register_rest_route($api_route, '/jobs',[
        'methods' => 'GET',
        'callback' => $callback,
    ]);

    /**
     * From date only.
     */
    register_rest_route($api_route, '/jobs/(?P<date_from>[-+_a-zA-Z0-9]+)',[
        'methods' => 'GET',
        'callback' => $callback,
        'args' => ['date_from'],
    ]);

    /**
     * From and oto dates (date range).
     */
    register_rest_route($api_route, '/jobs/(?P<date_from>[-+_a-zA-Z0-9]+)/(?P<date_to>[-a-zA-Z0-9]+)',[
        'methods' => 'GET',
        'callback' => $callback,
        'args' => ['date_from', 'date_to'],
    ]);
});
