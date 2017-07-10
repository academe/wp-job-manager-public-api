=== WP Job Manager - Public API ===
Contributors: judgej
Requires at least: 4.4 
Tested up to: 4.5.*
Stable tag: 1.1.0
License: GNU General Public License v3.0

Until the WP Job Manager fully supports the WP REST APi, this will expose titles and permalinks to the REST API.

= Documentation =

This plugin offers no configuration or administration pages. It just does what it does.

This plugin requires at least PHP 5.4

A single job detail can be found at the endpoint:

    https://example.com/wp-json/wpjm_public/v1/job/{job_id}

e.g. https://example.com/wp-json/wpjm_public/v1/job/2567

```
[
"2567": {

    "post_title": "Strategic Manager – Homelessness, Allocations and Voids Property Management",
    "post_name": "strategic-manager-homelessness-allocations-voids-property-management",
    "permalink": "https://example.com/job/strategic-manager-homelessness-allocations-voids-property-management/",
    "post_date_gmt": "2017-06-21 11:34:37",
    "post_status": "publish",
    "guid": "https://example.com/?post_type=job_listing&#038;p=41878"
}
]
```

Listings of jobs can be derived from the following endpoints:

*  https://example.com/wp-json/wpjm_public/v1/jobs
*  https://example.com/wp-json/wpjm_public/v1/jobs/{date-from}
*  https://example.com/wp-json/wpjm_public/v1/jobs/{date-from}/{date-to}

The dates can be a year, year and month, or a full date: 2017, 2017-05, 2017-06-30

= Support Policy =

Raise an issue on the github project, and we will take it from there.

== Installation ==

Nothing special, just the usual installation steps.

== Changelog ==

= 1.1.0: 2017-07-10 =

* Fetching single job no longer wrapped in array.

= 1.0.2: 2017-07-10 =

* Fetch job application and listing expiry dates.

= 1.0.1: 2017-07-04 =

* Include expired jobs in selection.

= 1.0.0: 2017-06-30 =

* Initial release.

