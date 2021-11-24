=== LearnDash LMS ===
Author: LearnDash
Author URI: https://learndash.com 
Plugin URI: https://learndash.com
Slug: learndash-core
Tags: learndash
Requires at least: 5.5
Tested up to: 5.8.2
Requires PHP: 7.0
Stable tag: 3.6.0
Last Update: 2021-11-17

LearnDash LMS Plugin - Turn your WordPress site into a learning management system.

== Description ==

Turn your WordPress site into a learning management system.

Easily create & sell courses, deliver quizzes, award certificates, manage users, download reports, and so much more! By using LearnDash you have access to the latest e-learning industry trends for creating robust learning experiences.

See the [Features](https://www.learndash.com/wordpress-course-plugin-features/) page for more information.

== Installation ==

If the auto-update is not working, you always have the option to update manually. Please note, a full backup of your site is always recommended prior to updating. 

1. Deactivate and delete your current version of LearnDash LMS.
2. Download the latest version of LearnDash from our [support site](https://support.learndash.com/articles/my-downloads/).
3. Upload the zipped file via PLUGINS > ADD NEW, or to wp-content/plugins.
4. Activate the LearnDash LMS plugin via the PLUGINS menu.

== Changelog ==

= 3.6.0 =

* Added trial period and billing period options to subscriptions
* Added registration page and logic
* Added per course redirects post purchase for buy now and recurring access type courses
* Added advanced tab that new houses data upgrades, custom labels and the REST API
* Added payments tab that houses PayPal and Stripe payment gateways 
* Added customizable post purchase and registration emails
* Added from email settings to change sender name and address rather than using the default of WordPress
* Fixed REST API using 12 hours instead of 24 hours in some places
* Fixed quiz filter not showing on some associated quizzes
* Fixed leaderboard showing results as 0

= 3.5.1.3 =

* Fixed issue with section headings being removed after the previous update resulting in display issues

= 3.5.1.2 =

* Fixed conflict with ACF Pro in quizzes
* Fixed free choice questions with an apostrophe being marked as incorrect
* Fixed user not being able to progress to the next step if total quiz points are 0
* Fixed saving or adding quote marks in section headings resulting in removal of all headings from that course
* Fixed data upgrades getting stuck in specific use cases
* Fixed topic being marked as complete even when the associated quiz is failed
* Fixed minor logic issues with quiz saving and resume 

= 3.5.1.1 =

* Added LD version and unique key to quiz attemp user meta data
* Fixed matrix sorting validation not working since version 3.5.1
* Fixed fill in the blank type questions only awarding 1 point even when the points to be awarded is set to a higher value
* Fixed logged out users cannot access a quiz in a course that is set to the open access mode
* Fixed mark complete button not showing on sample lessons for logged-in users 
* Fixed being unable to remove the first section heading 
* Fixed not being able to progress through a course when the last question in a quiz is an essay
* Fixed an error with "browser cookie answer protection" setting

= 3.5.1 =

* Added new quiz saving and resume functionality
* Added new endpoint to REST API V2 for quiz form entries
* Added fill in the blank question type individual answer points
* Added filter to not force lower case compare on quiz answer
* Updated increase rows for free question answer type
* Updated quiz questions settings fields in REST API V2
* Updated binary selector post_status display logic
* Updated validated that the user inputted recurring billing cycle value is correct
* Fixed if free quiz answer is 0 the answer is not being stored correctly in the statistics table
* Fixed focus mode arrows getting reversed on tablets
* Fixed leaderboard on front-end not displaying maximum points
* Fixed quiz builder sometimes loading with an unexpected error in the console
* Fixed lessons ordering changes after saving
* Fixed individual point values being incorrect in the REST API
* Fixed custom quiz time format options not saving
* Fixed calendar icons not displaying the date on hover in focus mode
* Fixed incorrect variable name in the REST API V2
* Fixed some quiz settings not displaying in the WordPress admin when importing quizzes
* Fixed PHP deprecated notice $block_editor_context
* Fixed issue where update notice trigger triggers on the wrong plugin
* Fixed quiz custom field dates not translated in the certificate builder
* Fixed page scrolling to top on quiz start
* Fixed support tab showing upgrade notice 
* Fixed Assessment questions not grading correctly 
* Fixed warnings/notices 
* Fixed umlauts not working in section headings 
* Fixed quiz score delete 
* Fixed error when accessing a deleted step of a course 
* Fixed quiz Assessment question radio input can select multiple

= 3.4.2.1 =

* Fixed ld_course_list not linking to courses when groups aren't set to public 

= 3.4.2 = 

* Added display alert when group CPT is not public
* Added [user_groups] shortcode section to shortcode inserter
* Added compatibility for WordPress 5.8
* Fixed PHP warning illegal string "offset" warning 
* Fixed private lessons not displaying in the lesson selector
* Fixed quiz statistics not loading when user search for a course a LD30 profile
* Fixed focus mode sub-steps list being collapsed by default on active step
* Fixed PHP notice in CSV reports
* Fixed question display setting not displaying questions in pagination format when selected

= 3.4.1.1 = 

* Fixed learndash_process_mark_complete() function

= 3.4.0.8 = 

* Fixed quizzes not showing in the course builder
* Fixed Fixed activity table records not getting updated when manually marking content as completed

= 3.4.0.7 =

* Fixed recurring payment duration resetting to 1 day
* Fixed quizzes not showing on course builder 
* Fixed sprint() too few arguments warnings 
* Fixed warning illegal offset percentage 

= 3.4.0.6 = 

* Fixed Course Grid pagination resetting after going to the next page on a filtered grid 
* Fixed issue with course progression where some users were unable to progress to the next step when taking a quiz multiple times 
* Fixed learndash_get_global_quiz_list() causing infinite loop in Legacy Mode templates 

= 3.4.0.5 = 

* Fixed lesson progress bar showing in focus mode 
* Fixed issue where course grid was still showing in progress even when it should show completed
* Fixed lesson/topic content not showing if the course builder is disabled 
* Fixed Error Call to undefined function learndash_get_custom_lower_label()

= 3.4.0.4 = 

* Updated use php timestamp for quiz completion times rather than a JS based value
* Fixed modifying course progress from the backend user profile in the wp-admin resets all incomplete courses
* Fixed sprintf warning too few arguments

= 3.4.0.3 = 

* Added show progress bar in lesson overview page
* Added support for duplicate post plugins
* Updated import block dependencies instead of using the wp global
* Updated compatibility for Spotlightr API v2 videos for video player
* Fixed warning messages under overview section (class-simplepie.php)
* Fixed [ld_course_list] using legacy templates causes infinite looping 
* Fixed group access mode reverting to previous saved value when updating 
* Fixed PHP notice on transaction listing 
* Fixed expired course alert not showing 

= 3.4.0.2 =

* Added associated lesson/topic/quiz course selectors
* Fixed span html tag never closes
* Fixed lesson list not visible to those without a user role 
* Fixed users list inaccessible if the admin user also has the group leader role 
* Fixed group leader can't edit assignments 

= 3.4.0.1 = 

* Fixed problem where content was not showing on the front-end
* Fixed the label missing from the drip lesson 

= 3.4.0 = 

* Added return to current lesson button
* Added filter to check for the duration of uploaded videos in assignments
* Added REST support on the video_resume and video_focus_pause settings fields for Lesson and Topics
* Added disable auto complete/input on form fields in quizzes
* Added progress_status to REST API V2
* Added Gutenberg block for [quizinfo] shortcode (only for the new certificate builder post type)
* Updated performance improvement throughout the plugin
* Updated REST API V2 to show success/failure messages when deleting a user from a course
* Updated improved quizzes list display loading time (part of performance improvements)
* Fixed showing incorrect DB version in support information overview
* Fixed delete LearnDash MU plugin from the mu-plugins folder automatically when LearnDash is deleted
* Fixed group leader can edit any assignment 
* Fixed public step in private course viewable 
* Fixed in Progress parameter showing complete courses as well for non-admin user
* Fixed group Leader cannot create/modify LD content with Gutenberg with manage courses/groups option
* Fixed quiz only lessons not displaying steps in Focus Mode sidebar
* Fixed "All Certificates" label not translatable
* Fixed accessing free course via the REST API
* Fixed essay type upload questions: File format not recognized if uppercase extension is used
* Fixed register_rest_field() does not work with v2
* Fixed incorrect breadcrumb order when course/step title using RTL language
* Fixed removing user through REST API V2 not working
* Fixed PHP warnings and notices

= 3.3.0.3 = 

* Fixed fatal error on WordPress 5.5 based LearnDash websites

= 3.3.0.2 =

* Added filter "learndash_use_wp_safe_redirect" to be used if a user is being redirected to the wp-admin when marking a lesson or course as complete
* Added warning when using an older PHP version
* Updated ReactDND library
* Fixed download quiz certificate link not showing when a quiz has an essay type question with "Not graded" status
* Fixed styling issue with Elementor and the [ld_profile] shortcode 
* Fixed subsequent pages do not respect the filter condition, for example when filtering completed courses only
* Fixed styling issue on the quiz question overview page where the second row isn't correctly aligned to the left
* Fixed warning when foreach() isn't run over an array
* Fixed unexpected token < in JSON when Elementor and LearnDash are activated
* Fixed essay bulk approval not working 
* Fixed essay/open type questions showing a default white space
* Fixed course grid block not saving the column numbers
* Fixed two buttons showing (publish and update) when reviewing a submitted essay in Gutenberg/the block editor 
* Fixed not being able to approve submitted essays 
* Fixed Video Pause on Window Unfocused causing YT videos to autoplay
* Fixed PHP notices
* Fixed matrix sorting view questions results not displaying matching answers correctly

= 3.3.0.1 = 

* Fixed E_PARSE error causing Error message: syntax error, unexpected "?"" In REST API v2 for PHP versions below 7.3 
* Fixed an issue where when loading the REST API endpoints metabox_init_filter caused problems with third-party plugins and groups
* Fixed users can�t upload assignments
* Fixed custom course label not applying to LD user status widget
* Fixed unsupported operand types
* Fixed deprecation warnings when using PHP 8

= 3.3.0 =

* Added LearnDash REST API v2 (beta)
* Added course export performance improvements
* Added quiz messages for the question answered state
* Updated quiz retry logic 
* Updated packages used in the builder
* Updated coding standards 
* Fixed quiz answer spacing 
* Fixed translations not updating 
* Fixed header distorted in focus mode
* Fixed marking a course complete via the profile page in the wp-admin
* Fixed course list in ld_profile shortcode not showing 
* Fixed missing latest quiz statistic 
* Fixed mark complete button cut off in focus mode
* Fixed lesson title not changing when using the_title filter
* Fixed group leaders not being able to filter assingments/essays 
* Fixed LearnDash blocks throwing a warning
* Fixed RTL breadcrumbs being the wrong direction
* Fixed PayPal recurring payments not enrolling users

View our full changelog [here](https://www.learndash.com/changelog/).

== Upgrade Notice ==

= 3.1.3 = 
Important security update: please update immediately.

== FAQ ==

= Do I need to update? =

It is always recommended to update. However given the nature of WordPress and the option to have many other plugins installed, custom code, etc. it is possible that a conflict would arise. This is why we always recommend testing the update on a development environment first. 

= Why am I getting an error notice when trying to update? =

If you are getting an error while trying to update your version of LearnDash LMS, verify that your license is still valid. 

Both your license key and email address should be entered via LEARNDASH LMS > SETTINGS > LMS LICENSE. You should then see a "Your license is valid" message appear. 

If not, you can find your correct information via our [Support Site](https://support.learndash.com/articles/my-downloads/).

If your license has expired, you can purchase a new one [here](https://www.learndash.com/pricing-and-purchase/).

= What will happen to my customizations when updating? =

As long as the customizations were not done directly in the core LearnDash plugin files, there should be no problem. We provide many template files and hooks for this purpose. 

