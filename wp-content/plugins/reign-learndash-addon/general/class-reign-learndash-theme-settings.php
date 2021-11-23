<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


if (!class_exists('Reign_Learndash_Theme_Settings')) :

    /**
     * @class Reign_Learndash_Theme_Settings
     */
    class Reign_Learndash_Theme_Settings {

        /**
         * The single instance of the class.
         *
         * @var Reign_Learndash_Theme_Settings
         */
        protected static $_instance = null;
        protected static $_slug = 'learndash';

        /**
         * Main Reign_Learndash_Theme_Settings Instance.
         *
         * Ensures only one instance of Reign_Learndash_Theme_Settings is loaded or can be loaded.
         *
         * @return Reign_Learndash_Theme_Settings - Main instance.
         */
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Reign_Learndash_Theme_Settings Constructor.
         */
        public function __construct() {
            $this->init_hooks();
        }

        /**
         * Hook into actions and filters.
         */
        private function init_hooks() {
            add_filter('alter_reign_admin_tabs', array($this, 'add_tab'), 15, 1);

            add_action('render_theme_options_page_for_' . self::$_slug, array($this, 'render_theme_options'));

            add_action('render_theme_options_for_ld_general', array($this, 'render_theme_options_for_ld_general'));

            add_action('render_theme_options_for_ld_related_course', array($this, 'render_theme_options_for_ld_related_course'));

            add_action('render_theme_options_for_ld_buddypress', array($this, 'render_theme_options_for_ld_buddypress'));

            // add_action( 'render_theme_options_for_ld_bp_group_sync', array( $this, 'render_theme_options_for_ld_bp_group_sync' ) );

            add_action('wp_loaded', array($this, 'save_reign_theme_settings'));
        }

        public function render_theme_options_for_ld_bp_group_sync() {
            global $wbtm_reign_settings;
            global $bp;
            $saved_group_data = '';
            $per_page = 20;
            $group_args = array(
                'order' => 'DESC',
                'orderby' => 'date_created',
                'per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => '_reign_linked_course',
                        'compare' => 'EXISTS',
                    ),
                ),
            );
            $allgroups = groups_get_groups($group_args);
            echo '<table class="form-table reign-ld-linked-group-list"><tbody>';
            if ($allgroups['groups']) {
                $current_arr = array_slice($allgroups['groups'], 0, $per_page);
                foreach ($current_arr as $single_group) {
                    ?>
                    <tr>
                        <th>
                            <label><?php echo esc_html($single_group->name); ?></label>
                        </th>
                        <td>
                            <a class="button-primary reign-ld-group-sync" attr-id="<?php echo esc_attr($single_group->id); ?>">
                                <?php esc_html_e('Sync', 'reign-learndash-addon'); ?>
                            </a>
                            <i class="dashicons dashicons-update reign-ld-spinner"></i>
                            <span>
                                <?php esc_html_e('Completed', 'reign-learndash-addon'); ?>
                            </span>
                        </td>
                    </tr>
                    <?php
                }
            }
            echo '</tbody>';
            echo '</table>';
            echo '<div id="reign-ld-pagination-bar"></div>';
        }

        public function render_theme_options_for_ld_buddypress() {
            global $wbtm_reign_settings;
            $Reign_LearnDash_BuddyPress_Addon_Ref = Reign_LearnDash_BuddyPress_Addon::instance();
            $course_enroll_label = sprintf(esc_html_x('%s Enrollment Activity', 'Course Enrollment Activity Label', 'reign-learndash-addon'), LearnDash_Custom_Label::get_label('course'));
            $course_completion_label = sprintf(esc_html_x('%s Completion Activity', 'Course Completion Activity Label', 'reign-learndash-addon'), LearnDash_Custom_Label::get_label('course'));
            // $lesson_create_label                  = sprintf( esc_html_x( '%s Create Activity', 'Lesson Create Activity Label', 'reign-learndash-addon' ), LearnDash_Custom_Label::get_label( 'lesson' ) );
            $lesson_completion_label = sprintf(esc_html_x('%s Completion Activity', 'Lesson Completion Activity Label', 'reign-learndash-addon'), LearnDash_Custom_Label::get_label('lesson'));
            $topic_completion_label = sprintf(esc_html_x('%s Completion Activity', 'Topic Completion Activity Label', 'reign-learndash-addon'), LearnDash_Custom_Label::get_label('topic'));
            $quiz_passed_label = sprintf(esc_html_x('%s Passed Activity', 'Quiz Passed Activity Label', 'reign-learndash-addon'), LearnDash_Custom_Label::get_label('quiz'));
            $comment_course_label = sprintf(esc_html_x('Comment Single %s Activity', 'Comment Single Course Activity Label', 'reign-learndash-addon'), LearnDash_Custom_Label::get_label('course'));
            $comment_lesson_label = sprintf(esc_html_x('Comment Single %s Activity', 'Comment Single Lesson Activity Label', 'reign-learndash-addon'), LearnDash_Custom_Label::get_label('lesson'));

            $activity_array = array(
                'reign_learndash_course_enrollment_activity' => array(
                    'label' => $course_enroll_label,
                ),
                'reign_learndash_course_completion_activity' => array(
                    'label' => $course_completion_label,
                ),
                // 'reign_learndash_lesson_create_activity' => array(
                // 'label' => $lesson_create_label,
                // ),
                'reign_learndash_lesson_completion_activity' => array(
                    'label' => $lesson_completion_label,
                ),
                'reign_learndash_topic_completion_activity' => array(
                    'label' => $topic_completion_label,
                ),
                'reign_learndash_quiz_passed_activity' => array(
                    'label' => $quiz_passed_label,
                ),
                'reign_learndash_comment_single_course_activity' => array(
                    'label' => $comment_course_label,
                ),
                'reign_learndash_comment_single_lesson_activity' => array(
                    'label' => $comment_lesson_label,
                ),
            );
            ?>
            <table class="form-table">
                <?php
                if (class_exists('BuddyPress')) {
                    if (bp_is_active('groups')) {
                        ?>
                        <tr>
                            <td class="rtm-left-side">
                                <div class="rtm-tooltip-wrap">
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                                    <label class="rtm-tooltip-label">
                                        <?php esc_html_e('Enable BuddyPress Group Integration', 'reign-learndash-addon'); ?>
                                    </label>
                                </div>
                                <div class="rtm-tooltiptext">
                                    <?php esc_html_e('This will enable BuddyPress group integration with LearnDash.', 'reign-learndash-addon'); ?>
                                </div>
                            </td>
                            <td>
                                <input type="checkbox" id="enable_group_integration" name="learndash[enable_group_integration]" value="true" <?php isset($wbtm_reign_settings['learndash']['enable_group_integration']) ? checked($wbtm_reign_settings['learndash']['enable_group_integration'], 'true') : ''; ?>>
                            </td>
                        </tr>
                        <?php
                    }
                }

                foreach ($activity_array as $activity_key => $activity_info) {
                    ?>
                    <tr class="reign-learndash-group-activity">
                        <th>
                            <label>
                                <?php echo $activity_info['label']; ?>
                            </label>
                        </th>
                        <td>
                            <div class="rtm-td-div">
                                <div>
                                    <p>
                                        <label>
                                            <?php printf(__('Enable "%s" For Users', 'reign-learndash-addon'), $activity_info['label']); ?>
                                        </label>
                                    </p>
                                    <p>
                                        <?php
                                        $enable_related_course = isset($wbtm_reign_settings['learndash'][$activity_key]) ? $wbtm_reign_settings['learndash'][$activity_key] : 'enable';
                                        echo '<input type="radio" name="learndash[' . $activity_key . ']" class="reign_learndash_course_enrollment_activity" value="enable"  ' . checked($enable_related_course, 'enable', false) . ' />';
                                        esc_html_e('Enable', 'reign-learndash-addon');
                                        echo ' ';
                                        echo '<input type="radio" name="learndash[' . $activity_key . ']" class="reign_learndash_course_enrollment_activity" value="disable" ' . checked($enable_related_course, 'disable', false) . ' />';

                                        esc_html_e('Disable', 'reign-learndash-addon')
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <?php
        }

        public function render_theme_options_for_ld_related_course() {
            global $wbtm_reign_settings;
            ?>
            <table class="form-table">
                <tr>
                    <td class="rtm-left-side">
                        <div class="rtm-tooltip-wrap">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                            <label class="rtm-tooltip-label">
                                <?php _e('Enable Related Courses', 'reign-learndash-addon'); ?>
                            </label>
                        </div>
                        <div class="rtm-tooltiptext">
                            <?php _e('You can enable/disable related courses section using these settings.', 'reign-learndash-addon'); ?>
                        </div>
                    </td>
                    <td>
                        <?php
                        $enable_related_courses = array(
                            'enable' => __('Enable', 'reign-learndash-addon'),
                            'disable' => __('Disable', 'reign-learndash-addon'),
                        );
                        $enable_related_course = isset($wbtm_reign_settings['learndash']['enable_related_courses']) ? $wbtm_reign_settings['learndash']['enable_related_courses'] : 'enable';
                        echo '<select name="learndash[enable_related_courses]" class="enable_related_courses">';
                        foreach ($enable_related_courses as $key => $value) {
                            $_selected = ( $enable_related_course == $key ) ? 'selected="selected"' : '';
                            echo '<option value="' . $key . '" ' . $_selected . '>' . $value . '</option>';
                        }
                        echo '</select>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="rtm-left-side">
                        <div class="rtm-tooltip-wrap">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                            <label class="rtm-tooltip-label">
                                <?php _e('Heading For Related Courses', 'reign-learndash-addon'); ?>
                            </label>
                        </div>
                        <div class="rtm-tooltiptext">
                            <?php _e('This setting helps you manage the heading for related courses.', 'reign-learndash-addon'); ?>
                        </div>
                    </td>
                    <td>
                        <?php
                        $title_related_courses = isset($wbtm_reign_settings['learndash']['title_related_courses']) ? $wbtm_reign_settings['learndash']['title_related_courses'] : 'Related Courses';
                        echo '<input type="text" name="learndash[title_related_courses]" value="' . $title_related_courses . '" style="width:100%;" />';
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="rtm-left-side">
                        <div class="rtm-tooltip-wrap">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                            <label class="rtm-tooltip-label">
                                <?php _e('Number Of Related Courses Show', 'reign-learndash-addon'); ?>
                            </label>
                        </div>
                        <div class="rtm-tooltiptext">
                            <?php _e('This setting helps you manage number of related courses to show.', 'reign-learndash-addon'); ?>
                        </div>
                    </td>
                    <td>
                        <?php
                        $num_of_related_courses = isset($wbtm_reign_settings['learndash']['num_of_related_courses']) ? $wbtm_reign_settings['learndash']['num_of_related_courses'] : '3';
                        echo '<input type="number" min="1" name="learndash[num_of_related_courses]" value="' . $num_of_related_courses . '" />';
                        ?>
                    </td>
                </tr>
            </table>
            <?php
        }

        public function render_theme_options_for_ld_general() {
            global $wbtm_reign_settings;
            ?>
            <table class="form-table">
                <tr>
                    <td class="rtm-left-side">
                        <div class="rtm-tooltip-wrap">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                            <label class="rtm-tooltip-label">
                                <?php _e('Select Default Course Image', 'reign-learndash-addon'); ?>
                            </label>
                        </div>
                        <div class="rtm-tooltiptext">
                            <?php _e('Select Default Course Image', 'reign-learndash-addon'); ?>
                        </div>
                    </td>
                    <td>
                        <?php
                        $default_course_img_url = isset($wbtm_reign_settings['learndash']['default_course_img_url']) ? $wbtm_reign_settings['learndash']['default_course_img_url'] : get_reign_ld_default_course_img_url();
                        $image_inline_style = 'width:150px;height:100px;object-fit-cover;';
                        $remove_inline_style = '';
                        if (empty($default_course_img_url)) {
                            $image_inline_style .= 'display:none;';
                            $remove_inline_style .= 'display:none;';
                        }
                        ?>
                        <input class="reign_default_cover_image_url" type="hidden" name="learndash[default_course_img_url]" value="<?php echo $default_course_img_url; ?>" />
                        <img class="reign_default_cover_image" src="<?php echo $default_course_img_url; ?>" style="<?php echo $image_inline_style; ?>" />
                        <a href="#" class="reign-remove-file-button" rel="avatar_default_image" style="<?php echo $remove_inline_style; ?>" ><?php _e('Remove Image', 'reign-learndash-addon'); ?></a>
                        <input id="reign-upload-button" type="button" class="button reign-upload-button" value="<?php _e('Upload Image', 'reign-learndash-addon'); ?>" />
                    </td>
                </tr>                
                <?php
                $display_settings = true;
                if (defined('LEARNDASH_LEGACY_THEME') && class_exists('LearnDash_Theme_Register')) {
                    if (learndash_is_active_theme('ld30')) {
                        $display_settings = false;
                    }
                }
                if ($display_settings) {
                    ?>
                    <tr>
                        <td class="rtm-left-side">
                            <div class="rtm-tooltip-wrap">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                                <label class="rtm-tooltip-label">
                                    <?php _e('Course Curriculum Layout', 'reign-learndash-addon'); ?>
                                </label>
                            </div>
                            <div class="rtm-tooltiptext">
                                <?php _e('You can set the curriculum either expanded or collapsed.', 'reign-learndash-addon'); ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            $curriculum_layouts = array(
                                'collapsed' => __('Collapsed', 'reign-learndash-addon'),
                                'expanded' => __('Expanded', 'reign-learndash-addon'),
                            );
                            $curriculum_layout = isset($wbtm_reign_settings['learndash']['curriculum_layout']) ? $wbtm_reign_settings['learndash']['curriculum_layout'] : 'collapsed';
                            echo '<select name="learndash[curriculum_layout]">';
                            foreach ($curriculum_layouts as $key => $value) {
                                echo '<option value="' . $key . '" ' . selected($curriculum_layout, $key, false) . ' >' . $value . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="rtm-left-side">
                            <div class="rtm-tooltip-wrap">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                                <label class="rtm-tooltip-label">
                                    <?php _e('LD Profile Layout', 'reign-learndash-addon'); ?>
                                </label>
                            </div>
                            <div class="rtm-tooltiptext">
                                <?php _e('You can set the LD Profile either expanded or collapsed.', 'reign-learndash-addon'); ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            $ld_profile_layouts = array(
                                'collapsed' => __('Collapsed', 'reign-learndash-addon'),
                                'expanded' => __('Expanded', 'reign-learndash-addon'),
                            );
                            $ld_profile_layout = isset($wbtm_reign_settings['learndash']['ld_profile_layout']) ? $wbtm_reign_settings['learndash']['ld_profile_layout'] : 'collapsed';
                            echo '<select name="learndash[ld_profile_layout]">';
                            foreach ($ld_profile_layouts as $key => $value) {
                                echo '<option value="' . $key . '" ' . selected($ld_profile_layout, $key, false) . ' >' . $value . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="rtm-left-side">
                            <div class="rtm-tooltip-wrap">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                                <label class="rtm-tooltip-label">
                                    <?php _e('Distraction Free Layout', 'reign-learndash-addon'); ?>
                                </label>
                            </div>
                            <div class="rtm-tooltiptext">
                                <?php _e('Set "Distraction Free Layout" as default view for reading course content.', 'reign-learndash-addon'); ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            $ld_distraction_free_layouts = array(
                                'enable' => __('Enable', 'reign-learndash-addon'),
                                'disable' => __('Disable', 'reign-learndash-addon'),
                            );
                            $ld_distraction_free_layout = isset($wbtm_reign_settings['learndash']['ld_distraction_free_layout']) ? $wbtm_reign_settings['learndash']['ld_distraction_free_layout'] : 'enable';
                            echo '<select name="learndash[ld_distraction_free_layout]">';
                            foreach ($ld_distraction_free_layouts as $key => $value) {
                                echo '<option value="' . $key . '" ' . selected($ld_distraction_free_layout, $key, false) . ' >' . $value . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="rtm-left-side">
                            <div class="rtm-tooltip-wrap">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                                <label class="rtm-tooltip-label">
                                    <?php _e('Close Action - Distraction Free Layout', 'reign-learndash-addon'); ?>
                                </label>
                            </div>
                            <div class="rtm-tooltiptext">
                                <?php _e('Decide the action to perform when close button is clicked for "Distraction Free Layout".', 'reign-learndash-addon'); ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            $ld_dstrctn_close_actions = array(
                                'close' => __('Return To Default View', 'reign-learndash-addon'),
                                'redirect' => __('Return To Course Page', 'reign-learndash-addon'),
                            );
                            $ld_dstrctn_close_action = isset($wbtm_reign_settings['learndash']['ld_dstrctn_close_action']) ? $wbtm_reign_settings['learndash']['ld_dstrctn_close_action'] : 'close';
                            echo '<select name="learndash[ld_dstrctn_close_action]">';
                            foreach ($ld_dstrctn_close_actions as $key => $value) {
                                echo '<option value="' . $key . '" ' . selected($ld_dstrctn_close_action, $key, false) . ' >' . $value . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="rtm-left-side">
                            <div class="rtm-tooltip-wrap">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                                <label class="rtm-tooltip-label">
                                    <?php _e('Dashboard Title Text', 'reign-learndash-addon'); ?>
                                </label>
                            </div>
                            <div class="rtm-tooltiptext">
                                <?php _e('This will set the title text for linking course page at distraction free layout.', 'reign-learndash-addon'); ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            $dashboard_text = isset($wbtm_reign_settings['learndash']['dashboard_text']) ? $wbtm_reign_settings['learndash']['dashboard_text'] : 'Dashboard';
                            ?>
                            <input type="text" name="learndash[dashboard_text]" value="<?php echo $dashboard_text; ?>">
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="rtm-left-side">
                        <div class="rtm-tooltip-wrap">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                            <label class="rtm-tooltip-label">
                                <?php _e('Disable Course Reviews Display', 'reign-learndash-addon'); ?>
                            </label>
                        </div>
                        <div class="rtm-tooltiptext">
                            <?php _e('This will disable course reviews from listings and review tab at single course page.', 'reign-learndash-addon'); ?>
                        </div>
                    </td>
                    <td>
                        <input type="checkbox" name="learndash[hide_review_tab]" value="on" <?php isset($wbtm_reign_settings['learndash']['hide_review_tab']) ? checked($wbtm_reign_settings['learndash']['hide_review_tab'], 'on') : ''; ?>>
                    </td>
                </tr>
                <tr>
                    <td class="rtm-left-side">
                        <div class="rtm-tooltip-wrap">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                            <label class="rtm-tooltip-label">
                                <?php _e('Allow Guest Users Submit Course Reviews', 'reign-learndash-addon'); ?>
                            </label>
                        </div>
                        <div class="rtm-tooltiptext">
                            <?php _e('This will let non enrolled users to submit course reviews.', 'reign-learndash-addon'); ?>
                        </div>
                    </td>
                    <td>
                        <input type="checkbox" name="learndash[guest_reviews]" value="on" <?php isset($wbtm_reign_settings['learndash']['guest_reviews']) ? checked($wbtm_reign_settings['learndash']['guest_reviews'], 'on') : ''; ?>>
                    </td>
                </tr>
                <tr>
                    <td class="rtm-left-side">
                        <div class="rtm-tooltip-wrap">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                            <label class="rtm-tooltip-label">
                                <?php _e('Disable Course Instructor Tab', 'reign-learndash-addon'); ?>
                            </label>
                        </div>
                        <div class="rtm-tooltiptext">
                            <?php _e('Disable course instructor tab from learndash tabs section at single course page.', 'reign-learndash-addon'); ?>
                        </div>
                    </td>
                    <td>
                        <input type="checkbox" name="learndash[hide_instructor_tab]" value="on" <?php isset($wbtm_reign_settings['learndash']['hide_instructor_tab']) ? checked($wbtm_reign_settings['learndash']['hide_instructor_tab'], 'on') : ''; ?>>
                    </td>
                </tr>

                <tr>
                    <td class="rtm-left-side">
                        <div class="rtm-tooltip-wrap">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                            <label class="rtm-tooltip-label">
                                <?php _e('Single Course Layout', 'reign-learndash-addon'); ?>
                            </label>
                        </div>
                        <div class="rtm-tooltiptext">
                            <?php _e('Choose single course layout.', 'reign-learndash-addon'); ?>
                        </div>
                    </td>
                    <td>
                        <ul>
                            <li>
                                <label>
                                    <input type="radio" name="learndash[course_layout]" value="default" <?php checked($wbtm_reign_settings['learndash']['course_layout'], 'default', true); ?> />&nbsp;<?php _e('Default', 'reign-learndash-addon'); ?>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="learndash[course_layout]" value="udemy" <?php checked($wbtm_reign_settings['learndash']['course_layout'], 'udemy', true); ?>/>&nbsp;<?php _e('Udemy', 'reign-learndash-addon'); ?>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input type="radio" name="learndash[course_layout]" value="teachable"<?php checked($wbtm_reign_settings['learndash']['course_layout'], 'teachable', true); ?> />&nbsp;<?php _e('Teachable', 'reign-learndash-addon'); ?>
                                </label>
                            </li>
                        </ul>						
                    </td>
                </tr>
                <tr>
                    <td class="rtm-left-side">
                        <div class="rtm-tooltip-wrap">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                            <label class="rtm-tooltip-label">
                                <?php _e('Course Category Filter', 'reign-learndash-addon'); ?>
                            </label>
                        </div>
                        <div class="rtm-tooltiptext">
                            <?php _e('Enable course category filter on course archive page.', 'reign-learndash-addon'); ?>
                        </div>
                    </td>
                    <td>
                        <input type="checkbox" name="learndash[course_category_filter]" value="on" <?php isset($wbtm_reign_settings['learndash']['course_category_filter']) ? checked($wbtm_reign_settings['learndash']['course_category_filter'], 'on') : ''; ?>>
                    </td>
                </tr>
                <tr>
                    <td class="rtm-left-side">
                        <div class="rtm-tooltip-wrap">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                            <label class="rtm-tooltip-label">
                                <?php _e('Course Instructor Filter', 'reign-learndash-addon'); ?>
                            </label>
                        </div>
                        <div class="rtm-tooltiptext">
                            <?php _e('Enable course instructor filter on course archive page.', 'reign-learndash-addon'); ?>
                        </div>
                    </td>
                    <td>
                        <input type="checkbox" name="learndash[course_instructor_filter]" value="on" <?php isset($wbtm_reign_settings['learndash']['course_instructor_filter']) ? checked($wbtm_reign_settings['learndash']['course_instructor_filter'], 'on') : ''; ?>>
                    </td>
                </tr>
				<tr>
                    <td class="rtm-left-side">
                        <div class="rtm-tooltip-wrap">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/question.png'); ?>" class="rtm-tooltip-image" alt="<?php esc_attr_e('tooltip-image', 'reign'); ?>" />
                            <label class="rtm-tooltip-label">
                                <?php _e('Hide Course Content Tab', 'reign-learndash-addon'); ?>
                            </label>
                        </div>
                        <div class="rtm-tooltiptext">
                            <?php _e('Enable to hide course content tab on course detail page. And display course content below description inside course description tab.', 'reign-learndash-addon'); ?>
                        </div>
                    </td>
                    <td>
                        <input type="checkbox" name="learndash[hide_course_content_tab]" value="on" <?php isset($wbtm_reign_settings['learndash']['hide_course_content_tab']) ? checked($wbtm_reign_settings['learndash']['hide_course_content_tab'], 'on') : ''; ?>>
                    </td>
                </tr>
            </table>
            <?php
        }

        public function add_tab($tabs) {
            $tabs[self::$_slug] = __('LearnDash', 'reign-learndash-addon');
            return $tabs;
        }

        public function render_theme_options() {
            global $wbtm_reign_settings;
            $vertical_tabs = array(
                'ld_general' => __('General', 'reign-learndash-addon'),
                'ld_related_course' => __('Related Courses', 'reign-learndash-addon'),
            );

            if (class_exists('BuddyPress')) {
                if (bp_is_active('groups')) {
                    $vertical_tabs['ld_buddypress'] = __('LearnDash BuddyPress Integration', 'reign-learndash-addon');
                    if (isset($wbtm_reign_settings['learndash']['enable_group_integration'])) {
                        // $vertical_tabs['ld_bp_group_sync'] = __( 'BP Group Member Sync', 'reign-learndash-addon' );
                    }
                }
            }

            $vertical_tabs = apply_filters('wbtm_' . self::$_slug . '_vertical_tabs', $vertical_tabs);
            include 'vertical-tabs-skeleton.php';
        }

        public function save_reign_theme_settings() {
            if (isset($_POST['reign-settings-submit']) && $_POST['reign-settings-submit'] == 'Y') {
                check_admin_referer('reign-options');
                global $wbtm_reign_settings;
                if (isset($_POST['learndash'])) {
                    $wbtm_reign_settings['learndash'] = $_POST['learndash'];
                }
                update_option('reign_options', $wbtm_reign_settings);
                $wbtm_reign_settings = get_option('reign_options', array());
            }
        }

    }

    endif;

/**
 * Main instance of Reign_Learndash_Theme_Settings.
 *
 * @return Reign_Learndash_Theme_Settings
 */
Reign_Learndash_Theme_Settings::instance();
