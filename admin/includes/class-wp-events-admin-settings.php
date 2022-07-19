<?php

/**
 * The admin Settings of the plugin.
 *
 * @package    Wp_Events/admin
 * @subpackage Wp_Events/admin/includes
 * @author     WP Minds
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Admin Settings
 * this Class deals deals with admin settings
 */
class Wp_Events_Admin_Settings {
	/**
	 * Plugin Settings Tabs
	 *
	 * @var    array $wpe_settings_tabs Plugin Settings Tabs array
	 * @since  1.2.0
	 * @access protected
	 */
	protected $wpe_settings_tabs;

	/**
	 * form options
	 *
	 * @var    array $wpe_form_settings form options in options table
	 * @since  1.0.438
	 * @access private
	 */
	private $wpe_form_settings;

	/**
	 * Admin Settings Constructor
	 *
	 * @since 1.0.438
	 */
	function __construct() {
		$this->wpe_form_settings        = get_option( 'wpe_forms_settings' );
		$this->set_wpe_settings_tabs();
	}

	/**
	 * Adding Settings Tabs
	 *
	 * @since 1.2.0
	 */
	public function wpe_admin_settings_tab() {
		global $wpe_active_tab;

		foreach ( $this->wpe_settings_tabs as $tab ) {
			?>
            <a class="nav-tab <?php
			wpe_is_active_tab( $wpe_active_tab, $tab, TRUE ) ?>"
               href="<?php
			   echo admin_url( "edit.php?post_type=wp_events&page=wp_events_settings&tab=$tab" ); ?>"><?php
				echo __( ucfirst( $tab ), 'wp-events' ); ?> </a>
			<?php
		}
	}

	/**
	 * Adding Settings Content
     *
     * @since 1.2.0
	 */
	public function wpe_admin_settings_content() {
		global $wpe_active_tab;

		if ( ! in_array( $wpe_active_tab, $this->wpe_settings_tabs ) ) {
            /**
             * @todo redirect to main settings page if tab doesn't exist
            */
		}

		settings_fields( 'wpe_' . $wpe_active_tab . '_settings' );
		do_settings_sections( 'wp_events_settings&tab=' . $wpe_active_tab );

	}

	/**
	 * Register Admin Settings
	*/
	public function wpe_admin_register_settings() {

		/**
         * General Tab Section And Fields
		*/
        $this->wpe_settings_general_tab();

		/**
		 * Events Tab Section And Fields
		 */
		$this->wpe_settings_events_tab();

		/**
		 * Display Tab Section And Fields
		 */
		$this->wpe_settings_display_tab();

		/**
		 * Forms Tab Section And Fields
		 */
		$this->wpe_settings_forms_tab();

		/**
		 * Mail Tab Section And Fields
		 */
		$this->wpe_settings_mail_tab();

		/**
		 * firm Tab Section And Fields
		 */
		$this->wpe_settings_firm_info_tab();

		/**
		 * reCAPTCHA Tab Section And Fields
		 */
		$this->wpe_settings_reCAPTCHA_tab();

		/**
		 * export Tab Section And Fields
		 */
		$this->wpe_settings_export_tab();
	}

	/**
	 * General Tabs Settings Section And Field
	 *
	 * @access protected
	 * @since 1.2.0
	 */
	protected function wpe_settings_general_tab() {

		register_setting( 'wpe_general_settings', 'wpe_settings', [
			'sanitize_callback' => [ $this, 'sanitize_settings_fields' ],
		] );

		add_settings_section(
			'wpe_settings_section',
			'General',
			[ $this, 'wpe_settings_general_callback' ],
			'wp_events_settings&tab=general'
		);

		/**
		 * ===========================================
		 * All Fields Under General Tab are added below
		 * ===========================================
		 */
		add_settings_field(
			'wpe_settings_slug',
			'Event URL Slug',
			[ $this, 'wpe_settings_slug_field_callback' ],
			'wp_events_settings&tab=general',
			'wpe_settings_section'
		);

		add_settings_field(
			'wpe_settings_post_name',
			'Event Menu name',
			[ $this, 'wpe_settings_post_name_callback' ],
			'wp_events_settings&tab=general',
			'wpe_settings_section'
		);

		add_settings_field(
			'wpe_settings_archive_meta_description',
			'Archive Meta Description',
			[ $this, 'wpe_settings_archive_meta_description_callback' ],
			'wp_events_settings&tab=general',
			'wpe_settings_section'
		);

		add_settings_field(
			'wpe_settings_privacy_policy',
			'Privacy Policy',
			[ $this, 'wpe_settings_privacy_field_callback' ],
			'wp_events_settings&tab=general',
			'wpe_settings_section'
		);

		add_settings_field(
			'wpe_settings_remove_on_uninstall',
			'Remove data on Delete',
			[ $this, 'wpe_settings_remove_on_uninstall_callback' ],
			'wp_events_settings&tab=general',
			'wpe_settings_section'
		);
	}

	/**
	 * Events Tabs Settings Section and fields
	 *
	 * @access protected
	 * @since 1.2.0
	 */
	protected function wpe_settings_events_tab() {

		register_setting( 'wpe_events_settings', 'wpe_events_settings', [
			'sanitize_callback' => array( $this, 'sanitize_settings_fields' ),
		] );

		add_settings_section(
			'wpe_settings_events_section',
			'Events',
			[ $this, 'wpe_settings_events_callback' ],
			'wp_events_settings&tab=events'
		);

		/**
		 * ===========================================
		 * All Fields Under Events Tab are added below
		 * ===========================================
		 */

		add_settings_field(
			'wpe_settings_approve_registrations',
			'Enable/Disbale Approval',
			[ $this, 'wpe_settings_approve_registrations_callback' ],
			'wp_events_settings&tab=events',
			'wpe_settings_events_section'
		);

		add_settings_field(
			'wpe_settings_draft_past_events',
			'Move Past Events to Draft',
			[ $this, 'wpe_settings_draft_past_events_callback' ],
			'wp_events_settings&tab=events',
			'wpe_settings_events_section'
		);
	}

	/**
	 * Display Tabs Settings Section and fields
	 *
	 * @access protected
	 * @since 1.0.0
	 */
	protected function wpe_settings_display_tab() {

		register_setting( 'wpe_display_settings', 'wpe_display_settings', [
			'sanitize_callback' => array( $this, 'sanitize_settings_fields' ),
		] );

		add_settings_section(
			'wpe_settings_display_section',
			'Display',
			[ $this, 'wpe_settings_display_callback' ],
			'wp_events_settings&tab=display'
		);

		/**
		 * ===========================================
		 * All Fields Under Display Tab are added below
		 * ===========================================
		 */

		add_settings_field(
			'wpe_settings_enable_darkmode',
			'Enable Dark Mode',
			[ $this, 'wpe_settings_enable_darkmode_callback' ],
			'wp_events_settings&tab=display',
			'wpe_settings_display_section'
		);
		
		add_settings_field(
			'wpe_settings_disable_archive',
			'Disable Archive Page',
			[ $this, 'wpe_settings_disable_archive_callback' ],
			'wp_events_settings&tab=display',
			'wpe_settings_display_section'
		);

		add_settings_field(
			'wpe_settings_archive_title',
			'Archive Page Title',
			[ $this, 'wpe_settings_archive_title_callback' ],
			'wp_events_settings&tab=display',
			'wpe_settings_display_section'
		);

		add_settings_field(
			'wpe_settings_archive_posts',
			'No. of Events To Display per Page',
			[ $this, 'wpe_settings_archive_posts_callback' ],
			'wp_events_settings&tab=display',
			'wpe_settings_display_section'
		);

		add_settings_field(
			'wpe_settings_registration_button',
			'Enable/Disable Registration Button',
			[ $this, 'wpe_settings_registration_button_callback' ],
			'wp_events_settings&tab=display',
			'wpe_settings_display_section'
		);

		add_settings_field(
			'wpe_settings_reg_button_text',
			'Text for Registration Button',
			[ $this, 'wpe_settings_reg_button_text_callback' ],
			'wp_events_settings&tab=display',
			'wpe_settings_display_section'
		);

		add_settings_field(
			'wpe_settings_closed_registrations_text',
			'Text for Closed Registrations',
			[ $this, 'wpe_settings_closed_registrations_text_callback' ],
			'wp_events_settings&tab=display',
			'wpe_settings_display_section'
		);

		add_settings_field(
			'wpe_settings_max_seats',
			'Number of Seats per Registration',
			[ $this, 'wpe_settings_max_seats_callback' ],
			'wp_events_settings&tab=display',
			'wpe_settings_display_section'
		);
	}

	/**
	 * Forms Tabs Settings Section and fields
	 *
	 * @access protected
	 * @since 1.0.0
	 */
	protected function wpe_settings_forms_tab() {

		register_setting( 'wpe_forms_settings', 'wpe_forms_settings', [
			'sanitize_callback' => array( $this, 'sanitize_forms_settings' ),
		] );

		/**
		 * main forms tab section
		 */
		add_settings_section(
			'wpe_settings_forms_section',
			'',
			[ $this, 'wpe_settings_forms_callback' ],
			'wp_events_settings&tab=forms'
		);
		/**
		 *  Registration form section
		 */
		add_settings_section(
			'wpe_settings_registration_form_section',
			'',
			[ $this, 'wpe_settings_registration_form_section_callback' ],
			'wp_events_settings&tab=forms'
		);
		/**
		 * Subscriber form section
		 */
		add_settings_section(
			'wpe_settings_subscriber_form_section',
			'',
			[ $this, 'wpe_settings_subscriber_form_section_callback' ],
			'wp_events_settings&tab=forms'
		);

		/**
		 * ===========================================
		 * All Fields Under Form Tab are added below
		 * ===========================================
		 */
		add_settings_field(
			'wpe_settings_registration_form_labels',
			'Registration Form Field labels',
			[ $this, 'wpe_settings_registration_form_labels_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_registration_form_section'
		);

		add_settings_field(
			'wpe_settings_form_success',
			'Registration Form Redirect (Seminar)',
			[ $this, 'wpe_settings_form_success_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_registration_form_section'
		);

		add_settings_field(
			'wpe_settings_form_success_webinar',
			'Registration Form Redirect (Webinar)',
			[ $this, 'wpe_settings_form_success_webinar_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_registration_form_section'
		);

		add_settings_field(
			'wpe_settings_registration_enable_texting_permission',
			'Registration Form Texting Permission Field',
			[ $this, 'wpe_settings_reg_enable_texting_permission_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_registration_form_section'
		);
		
		add_settings_field(
			'wpe_settings_reg_form_texting_permission',
			'Texting Permission Label',
			[ $this, 'wpe_settings_reg_form_texting_permission_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_registration_form_section'
		);

		add_settings_field(
			'wpe_settings_registration_form_button',
			'Registration Form Button Text',
			[ $this, 'wpe_settings_registration_form_button_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_registration_form_section'
		);

		add_settings_field(
			'wpe_settings_before_registration',
			'Before Registration Form',
			[ $this, 'wpe_settings_before_registration_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_registration_form_section'
		);

		add_settings_field(
			'wpe_settings_after_registration',
			'After Registration Form',
			[ $this, 'wpe_settings_after_registration_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_registration_form_section'
		);

		add_settings_field(
			'wpe_settings_consent_checkbox',
			'Consent Checkbox',
			[ $this, 'wpe_settings_consent_checkbox_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_registration_form_section'
		);

		add_settings_field(
			'wpe_settings_disclaimer_checkbox',
			'Disclaimer Checkbox',
			[ $this, 'wpe_settings_disclaimer_checkbox_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_registration_form_section'
		);

		add_settings_field(
			'wpe_settings_hearaboutus_options',
			'How did you hear about us?',
			[ $this, 'wpe_settings_hearaboutus_options_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_registration_form_section'
		);

		/**
		 * Subscriber form settings fields
		 */
		add_settings_field(
			'wpe_settings_subscriber_form_labels',
			'Subscriber Form Field labels',
			[ $this, 'wpe_settings_subscriber_form_labels_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_subscriber_form_section'
		);	

		add_settings_field(
			'wpe_settings_subscriber_enable_phone_number',
			'Subscriber Form Phone Field',
			[ $this, 'wpe_settings_subscriber_enable_phone_number_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_subscriber_form_section'
		);

		add_settings_field(
			'wpe_settings_subscriber_enable_texting_permission',
			'Subscriber Form Texting Permission Field',
			[ $this, 'wpe_settings_subscriber_enable_texting_permission_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_subscriber_form_section'
		);
		
		add_settings_field(
			'wpe_settings_subscriber_form_texting_permission',
			'Texting Permission Label',
			[ $this, 'wpe_settings_subscriber_form_texting_permission_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_subscriber_form_section'
		);

		add_settings_field(
			'wpe_settings_subscriber_form_success',
			'Subscriber Form Redirect',
			[ $this, 'wpe_settings_subscriber_form_success_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_subscriber_form_section'
		);

		add_settings_field(
			'wpe_settings_subscriber_form_title',
			'Subscriber Form Title',
			[ $this, 'wpe_settings_subscriber_form_title_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_subscriber_form_section'
		);

		add_settings_field(
			'wpe_settings_subscriber_form_description',
			'Subscriber Form Description',
			[ $this, 'wpe_settings_subscriber_form_description_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_subscriber_form_section'
		);

		add_settings_field(
			'wpe_settings_subscriber_form_button',
			'Subscriber Form Button Text',
			[ $this, 'wpe_settings_subscriber_form_button_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_subscriber_form_section'
		);

		add_settings_field(
			'wpe_settings_before_subscriber',
			'Before Subscriber Form',
			[ $this, 'wpe_settings_before_subscriber_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_subscriber_form_section'
		);

		add_settings_field(
			'wpe_settings_after_subscriber',
			'After Subscriber Form',
			[ $this, 'wpe_settings_after_subscriber_callback' ],
			'wp_events_settings&tab=forms',
			'wpe_settings_subscriber_form_section'
		);
	}

    /**
     * Mail tab Section and Settings Fields
    */
    protected function wpe_settings_mail_tab(){

	    register_setting( 'wpe_mail_settings', 'wpe_mail_settings' );
	    add_settings_section(
		    'wpe_settings_mail_section',
		    'Mail',
		    [$this,'wpe_settings_mail_callback'],
		    'wp_events_settings&tab=mail'
	    );

	    /**
	     * ===========================================
	     * All Fields Under Mail Tab are added below
	     * ===========================================
	     */
	    add_settings_field(
		    'wpe_settings_mail_from',
		    'Send Mail From',
		    [$this,'wpe_settings_mail_from_callback'],
		    'wp_events_settings&tab=mail',
		    'wpe_settings_mail_section'
	    );

		add_settings_field(
		    'wpe_settings_enable_webinar_conformation',
		    'Enable Webinar Confirmation email',
		    [$this,'wpe_settings_enable_webinar_conformation_callback'],
		    'wp_events_settings&tab=mail',
		    'wpe_settings_mail_section'
	    );

	    add_settings_section(
		    'wpe_settings_mail_tab_section',
		    '',
		    [$this,'wpe_settings_mail_subtab_callback'],
		    'wp_events_settings&tab=mail'
	    );

    }

	/**
	 * firm Info Tabs Settings Section And Field
	 *
	 * @access protected
	 * @since 1.2.5
	 */
	protected function wpe_settings_firm_info_tab() {

		register_setting( 'wpe_firm_settings', 'wpe_firm_settings', [
			'sanitize_callback' => [ $this, 'sanitize_settings_fields' ],
		] );

		add_settings_section(
			'wpe_settings_firm_section',
			'Firm Information',
			[ $this, 'wpe_settings_firm_callback' ],
			'wp_events_settings&tab=firm'
		);

		/**
		 * =========================================================
		 * All Fields Under firm Tab are added below
		 * =========================================================
		 */
		add_settings_field(
			'wpe_settings_owner_name',
			'Firm\'s Owner Name',
			[ $this, 'wpe_settings_owner_name_callback' ],
			'wp_events_settings&tab=firm',
			'wpe_settings_firm_section'
		);

	    add_settings_field(
		    'wpe_settings_mail_from_name',
		    'Your Firm Name',
		    [$this,'wpe_settings_mail_from_name_callback'],
		    'wp_events_settings&tab=firm',
		    'wpe_settings_firm_section'
	    );

		add_settings_field(
		    'wpe_settings_admin_email',
		    'Contact Email Address',
		    [$this,'wpe_settings_admin_from_callback'],
		    'wp_events_settings&tab=firm',
		    'wpe_settings_firm_section'
	    );

		add_settings_field(
			'wpe_settings_phone',
			'Firm Phone No.',
			[ $this, 'wpe_settings_phone_field_callback' ],
			'wp_events_settings&tab=firm',
			'wpe_settings_firm_section'
		);

		add_settings_field(
			'wpe_settings_fax',
			'Firm Fax No.',
			[ $this, 'wpe_settings_fax_field_callback' ],
			'wp_events_settings&tab=firm',
			'wpe_settings_firm_section'
		);
	}

	/**
	 * reCAPTCHA Tabs Settings Section And Field
	 *
	 * @access protected
	 * @since 1.3.0
	 */
	protected function wpe_settings_reCAPTCHA_tab() {

		register_setting( 'wpe_reCAPTCHA_settings', 'wpe_reCAPTCHA_settings', [
			'sanitize_callback' => [ $this, 'sanitize_settings_fields' ],
		] );

		add_settings_section(
			'wpe_settings_reCAPTCHA_section',
			'reCAPTCHA Settings',
			[ $this, 'wpe_settings_reCAPTCHA_callback' ],
			'wp_events_settings&tab=reCAPTCHA'
		);

		/**
		 * =========================================================
		 * All Fields Under reCAPTCHA Tab are added below
		 * =========================================================
		 */
		add_settings_field(
			'wpe_settings_reCAPTCHA_type',
			'Type',
			[ $this, 'wpe_settings_reCAPTCHA_type_callback' ],
			'wp_events_settings&tab=reCAPTCHA',
			'wpe_settings_reCAPTCHA_section'
		);

		add_settings_field(
			'wpe_settings_reCAPTCHA_sitekey',
			'Site Key',
			[ $this, 'wpe_settings_reCAPTCHA_sitekey_callback' ],
			'wp_events_settings&tab=reCAPTCHA',
			'wpe_settings_reCAPTCHA_section'
		);

		add_settings_field(
			'wpe_settings_reCAPTCHA_secretkey',
			'Secret Key',
			[ $this, 'wpe_settings_reCAPTCHA_secretkey_callback' ],
			'wp_events_settings&tab=reCAPTCHA',
			'wpe_settings_reCAPTCHA_section'
		);
	}

	/**
	 * export Tabs Settings Section And Field
	 *
	 * @access protected
	 * @since 1.3.0
	 */
	protected function wpe_settings_export_tab() {

		register_setting( 'wpe_export_settings', 'wpe_export_settings', [
			'sanitize_callback' => [ $this, 'sanitize_settings_fields' ],
		] );

		add_settings_section(
			'wpe_settings_export_section',
			'Export Events',
			[ $this, 'wpe_settings_export_callback' ],
			'wp_events_settings&tab=export'
		);

		add_settings_section(
			'wpe_settings_export_reg_section',
			'Export Registrations',
			[ $this, 'wpe_settings_export_reg_callback' ],
			'wp_events_settings&tab=export'
		);

		/**
		 * =========================================================
		 * All Fields Under Export Tab are added below
		 * =========================================================
		 */
		add_settings_field(
			'wpe_settings_events_filters',
			'Set Filters to Export File',
			[$this,'wpe_settings_events_filters_callback'],
			'wp_events_settings&tab=export',
			'wpe_settings_export_section'
		);

		add_settings_field(
			'wpe_settings_export_events',
			'Export Events',
			[$this,'wpe_settings_export_events_callback'],
			'wp_events_settings&tab=export',
			'wpe_settings_export_section'
		);
	}

    /**
     * Specific emails tabs section
     *
     * accordion for registration and subscription email
    */
	public function wpe_settings_mail_subtab_callback() {
		$wpe_mail_settings = get_option('wpe_mail_settings');
		?>
        <ul class="mail-accordion">
            <li>
                <div class="other-hold"><?php esc_html_e( 'Registrant templates', 'wp-events' ); ?></div>
                <div class="other-post">
                    <table class="form-table wpe-settings-table" role="presentation">
                        <tbody>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'User Email Subject', 'wp-events' ); ?></th>
                            <td>
                                <input class="wpe-settings-field wpe-sub-tab-field" name="wpe_mail_settings[mail_success_subject]" id="wpe_mail_success_subject" type="text"
                                       value="<?php echo isset( $wpe_mail_settings['mail_success_subject'] ) ? $wpe_mail_settings['mail_success_subject'] : 'Thank you for registering!';?>">
                                <small class="wpe-fields-description"><?php esc_html_e( 'Enter user email subject (users will receive this subject in mail)', 'wp-events' ); ?></small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Seminar Email Message', 'wp-events' ); ?></th>
                            <td>
							<?php
								$mailsuccessMessage =  isset( $wpe_mail_settings['mail_success_message'] ) ? $wpe_mail_settings['mail_success_message'] : '';
								echo wpe_editor( $mailsuccessMessage, 'mail_success_message', 'wpe_mail_settings[mail_success_message]' );
							?>
                                <small class="wpe-fields-description">
									<?php esc_html_e( 'Enter user email message (users will receive this message on registering for seminars)', 'wp-events' ); ?>
                                    <?php echo $this->shortcode_helper_tooltip(); ?>
                                </small>
                            </td>
                        </tr>
						<tr>
                            <th scope="row"><?php esc_html_e( 'Webinar Email Message', 'wp-events' ); ?></th>
                            <td>
							<?php
								$webinar_success_Message =  isset( $wpe_mail_settings['webinar_success_messages'] ) ? $wpe_mail_settings['webinar_success_message'] : '';
								$disable_editor 		 = isset( $wpe_mail_settings['enable_webinar_conformation'] ) ? '' : 'disable-editor';
								echo wpe_editor( $webinar_success_Message, 'webinar_success_message-'. $disable_editor, 'wpe_mail_settings[webinar_success_message]' );
							?>
                                <small class="wpe-fields-description">
								<?php esc_html_e( 'Enter user email message (users will receive this message on registering for webinars)', 'wp-events' ); ?>
                                    <?php echo $this->shortcode_helper_tooltip(); ?>
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Admin Email Subject', 'wp-events' ); ?></th>
                            <td>
                                <input class="wpe-settings-field wpe-sub-tab-field" name="wpe_mail_settings[registrant_admin_subject]" id="wpe_registrant_admin_subject" type="text"
                                       value="<?php echo isset( $wpe_mail_settings['registrant_admin_subject'] ) ? $wpe_mail_settings['registrant_admin_subject'] : '';?>">
                                <small class="wpe-fields-description"><?php esc_html_e( 'Enter admin email subject (admin will receive this subject in mail)', 'wp-events' ); ?></small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Admin Email Message', 'wp-events' ); ?></th>
                            <td>
							<?php
								$registrant_admin_Message =  isset( $wpe_mail_settings['registrant_admin_message'] ) ? $wpe_mail_settings['registrant_admin_message'] : '';
								echo wpe_editor( $registrant_admin_Message, 'registrant_admin_message', 'wpe_mail_settings[registrant_admin_message]' );
							?>
                                <small class="wpe-fields-description">
									<?php esc_html_e( 'Enter admin email Message (admin will receive this message when a user submits a form)', 'wp-events' ); ?>
		                            <?php echo $this->shortcode_helper_tooltip(); ?>
                                </small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </li>
            <li>
                <div class="other-hold"><?php esc_html_e( 'Subscriber templates', 'wp-events' ); ?></div>
                <div class="other-post">
					<table class="form-table wpe-settings-table" role="presentation">
                        <tbody>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'User Email Subject', 'wp-events' ); ?></th>
                            <td>
                                <input class="wpe-settings-field wpe-sub-tab-field" name="wpe_mail_settings[subscriber_user_subject]" id="wpe_subscriber_user_subject" type="text"
                                       value="<?php echo isset( $wpe_mail_settings['subscriber_user_subject'] ) ? $wpe_mail_settings['subscriber_user_subject'] : '';?>">
                                <small class="wpe-fields-description"><?php esc_html_e( 'Enter admin email subject (admin will receive this subject in mail)', 'wp-events' ); ?></small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'User Email Message', 'wp-events' ); ?></th>
                            <td>
								<?php
									$subscriber_user_Message =  isset( $wpe_mail_settings['subscriber_user_message'] ) ? $wpe_mail_settings['subscriber_user_message'] : '';
									echo wpe_editor( $subscriber_user_Message, 'subscriber_user_message', 'wpe_mail_settings[subscriber_user_message]' );
								?>
                                <small class="wpe-fields-description">
									<?php esc_html_e( 'Enter user email message (users will receive this message on submitting form)', 'wp-events' ); ?>
					                <?php echo $this->shortcode_helper_tooltip(); ?>
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Admin Email Subject', 'wp-events' ); ?></th>
                            <td>
                                <input class="wpe-settings-field wpe-sub-tab-field" name="wpe_mail_settings[subscriber_admin_subject]" id="wpe_subscriber_admin_subject" type="text"
                                       value="<?php echo isset( $wpe_mail_settings['subscriber_admin_subject'] ) ? $wpe_mail_settings['subscriber_admin_subject'] : '';?>">
                                <small class="wpe-fields-description"><?php esc_html_e( 'Enter admin email subject (admin will receive this subject in mail)', 'wp-events' ); ?></small>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Admin Email Message', 'wp-events' ); ?></th>
                            <td>
								<?php
									$subscriber_admin_Message =  isset( $wpe_mail_settings['subscriber_admin_message'] ) ? $wpe_mail_settings['subscriber_admin_message'] : '';
									echo wpe_editor( $subscriber_admin_Message, 'subscriber_admin_message', 'wpe_mail_settings[subscriber_admin_message]' );
								?>
                                <small class="wpe-fields-description">
									<?php esc_html_e( 'Enter admin email Message (admin will receive this message when a user submits a form)', 'wp-events' ); ?>
					                <?php echo $this->shortcode_helper_tooltip(); ?>
                                </small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </li>
        </ul>
		<?php
	}

    public function wpe_settings_mail_tabs() {
    }

	/**
	 * callback for Setting Section ID=> wpe_settings_section
	*/
	public function wpe_settings_general_callback() {
	}

	/**
	 * callback for Setting Section ID=> wpe_settings_forms_section
	*/
	public function wpe_settings_forms_callback() {
	    ?>
        <ul class="subsubsub">
            <li>
                <a href="#registration-form-settings"
                   class="current"><?php esc_html_e( 'Registration Form', 'wp-events' ); ?></a> |
            </li>
            <li>
                <a href="#subscriber-form-settings"
                   class=""><?php esc_html_e( 'Subscriber Form', 'wp-events' ); ?></a>
            </li>
        </ul>
        <?php
	    echo '';
	}

    /**
	 * callback for Setting Section ID=> wpe_settings_maps_section
	*/
	public function wpe_settings_display_callback() {
		echo 'All the Display Settings are available under this tab';
	}

	/**
	 * callback for Setting Section ID=> wpe_settings_maps_section
	*/
	public function wpe_settings_events_callback() {
		echo 'All the Events Settings are available under this tab';
	}

	/**
	 * callback for Setting Section ID=> wpe_settings_mail_section
	*/
	public function wpe_settings_mail_callback() {
		esc_html_e( 'All the mail Settings are available under this tab', 'wp-events' ); ?>
		<h3><?php esc_html_e( 'General Settings', 'wp-events' ); ?></h3>
		<?php
	}
	
	/**
	 * callback for Setting Section ID=> wpe_settings_firm_section
	*/
	public function wpe_settings_firm_callback() {
		esc_html_e( 'You can enter information about your firm here.', 'wp-events' ) ;
	}

	/**
	 * callback for Setting Section ID=> wpe_settings_reCAPTCHA_section
	*/
	public function wpe_settings_reCAPTCHA_callback() {
		esc_html_e( 'All the reCAPTCHA Settings are available under this tab', 'wp-events' ) ;
	}

	/**
	 * callback for Setting Section ID=> wpe_settings_export_section
	*/
	public function wpe_settings_export_callback() {
		esc_html_e( 'All the Export Events Settings are available here', 'wp-events' ) ;
	}


	/**
	 * callback for Setting Section ID=> wpe_settings_export_section
	*/
	public function wpe_settings_export_reg_callback() {
	?>
	<p><?php esc_html_e( 'All the Export Registrations Settings are available here', 'wp-events' ); ?></p>

	<table class="form-table">
  		<tr>
   			<th><?php esc_html_e( 'Event Start Date', 'wp-events' ); ?></th>
    		<td>
				<input id="wpe-filter-start-date" autocomplete="off" class="wp-event-datepicker" type="text" name="wpe-filter-start-date" placeholder="Filter by start date" value=""/>
			</td>
  		</tr>
  		<tr>
    		<th><?php esc_html_e( 'Event End Date', 'wp-events' ); ?></th>
    		<td>
				<input id="wpe-filter-end-date" autocomplete="off" class="wp-event-datepicker" type="text" name="wpe-filter-end-date" placeholder="Filter by end date" value=""/>
			</td>
  		</tr>
		<tr>
   			<th><?php esc_html_e( 'Select Event', 'wp-events' ); ?></th>
    		<td>
				<?php echo wpe_event_title(); ?>
			</td>
  		</tr>
 		<tr>
			<th scope="row"><?php esc_html_e( 'Export Registrations', 'wp-events' ); ?></th>
  			<td>
				<input type="submit" id="export-event-entries" class="button button-primary" value="Export Entries">
        		<small class="wpe-fields-description"><?php esc_html_e( 'Export Registrations to CSV (Leave the filters empty if you want to export all entries.)', 'wp-events' ); ?></small>
			</td>
		</tr>
	</table>

	<h2> <?php esc_html_e( 'Export Subscriptions', 'wp-events' ); ?> </h2>
	<table class="form-table">
 		<tr>
			<th scope="row"><?php esc_html_e( 'Export Subscriptions', 'wp-events' ); ?></th>
  			<td>
				<input type="submit" id="export-subscription" class="button button-primary" value="Export Subscriptions">
        		<small class="wpe-fields-description"><?php esc_html_e( 'Export Subscriptions to CSV', 'wp-events' ); ?></small>
			</td>
		</tr>
	</table>

	<?php

	}

	/**
	 * ==============================================
     *      General Tab Fields Callback Functions
     * ==============================================
	 */

	/**
	 * Callback Setting Fields ID=>wpe_settings_slug
	*/
	public function wpe_settings_slug_field_callback() {
	    $option = get_option('wpe_settings');
	    ?>
	    <input pattern="^[A-Za-z-]+$" title="Only alphabets(a-z) are allowed" class="wpe-settings-field" name="wpe_settings[events_slug]" id="wpe_page_slug" type="text" value="<?php echo $option['events_slug'] ?: 'events';?>"/>
        <a class="view-page" title="View Page" target="_blank" href="<?php echo get_site_url().'/'.$option['events_slug']; ?>"><span class="dashicons dashicons-external"></span></a>
        <small class="wpe-fields-description"><?php esc_html_e( 'Event Page Slug', 'wp-events' ); ?></small>
		<?php
		delete_option('rewrite_rules');
	}

	/**
     * Privacy Policy Callback
	*/
    public function wpe_settings_privacy_field_callback() {
	    $option = get_option('wpe_settings');
	    ?>
        <textarea name="wpe_settings[privacy_policy]" id="wpe_privacy_policy" style="width:50%;height:100px;"><?php echo isset( $option['privacy_policy'] ) ? $option['privacy_policy'] : '' ;?></textarea>
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter Privacy Policy (Appears on Registration Form)', 'wp-events' ); ?></small>
	    <?php
    }

    /**
     * Remove Data on uninstall checkbox callback
    */
    public function wpe_settings_remove_on_uninstall_callback() {
	    $option = get_option('wpe_settings');
        ?>
        <label class="wpe-checkbox">
            <input name="wpe_settings[remove_on_uninstall]" id="wpe_remove_on_uninstall" value="l_true" type="checkbox" <?php echo isset( $option['remove_on_uninstall'] ) ? 'checked' : ''; ?> />
            <span class="slider round"></span>
    	</label>
        <small><?php esc_html_e( 'Check this box if you would like to completely remove all of its data when the plugin is deleted.', 'wp-events' ); ?></small>
        <?php
    }

    /**
     * Post Menu Name Callback
    */
    public function wpe_settings_post_name_callback() {
	    $option= get_option('wpe_settings');
	    if( $option['events_post_name'] === '' ) {
		    $option['events_post_name'] = 'Events';
        }
	    ?>
        <input class="wpe-settings-field" name="wpe_settings[events_post_name]" id="wpe_post_name" type="text" value="<?php echo isset( $option['events_post_name'] ) ? $option['events_post_name'] : 'Events';?>"/>
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter Post Menu Name (This name will be replace by events).', 'wp-events' ); ?></small>
	    <?php
    }

	/**
     * Archive Meta Description Callback
    */
    public function wpe_settings_archive_meta_description_callback() {
	    $option = get_option('wpe_settings');
	    ?>
		<textarea name="wpe_settings[meta_description]" id="wpe_meta_description" style="width:50%;height:100px;"><?php echo isset( $option['meta_description'] ) ? $option['meta_description'] : 'Join us for free seminars for the most up-to-date information on how you can protect your assets during your life and preserve them after your death.' ;?></textarea>
		<small class="wpe-fields-description"><?php esc_html_e( 'Enter Meta Description to be displayed for Archive Page.', 'wp-events' ); ?></small>
	    <?php
    }


	/**
	 * ==============================================
     *      Forms Tab Fields Callback Functions
     * ==============================================
	 */

    /**
     * Settings Field wpe_settings_form_success callback
     */
    public function wpe_settings_form_success_callback() {
	    ?>
        <input class="wpe-settings-field" name="wpe_forms_settings[form_success]" id="wpe_form_successs" type="url" value="<?php echo isset( $this->wpe_form_settings['form_success'] ) ? $this->wpe_form_settings['form_success'] : ''; ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'User will be redirected to entered URL on successful seminar registration.', 'wp-events' ); ?></small>
	    <?php
    }

	/**
     * Settings Field wpe_settings_form_success_webinar callback
     */
    public function wpe_settings_form_success_webinar_callback() {
	    ?>
        <input class="wpe-settings-field" name="wpe_forms_settings[form_success_webinar]" id="wpe_form_successs_webinar" type="url" value="<?php echo isset( $this->wpe_form_settings['form_success_webinar'] ) ? $this->wpe_form_settings['form_success_webinar'] : ''; ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'User will be redirected to entered URL on successful webinar registration.', 'wp-events' ); ?></small>
	    <?php
    }

	/**
	 * 
	 * Texting Permission field callback
	 * 
	 */
	public function wpe_settings_reg_form_texting_permission_callback() {
		?>
        <textarea name="wpe_forms_settings[reg_form_texting_permission]" id="wpe_reg_texting_permission" style="width:50%;height:100px;"><?php echo isset( $this->wpe_form_settings['reg_form_texting_permission'] ) ? 
		$this->wpe_form_settings['reg_form_texting_permission'] : 
			esc_html_e( 'I agree to receive texts at the number provided from [wpe_firm_name]. Frequency may vary and include information on appointments, events, and other marketing messages. Message/data rates may apply. To opt-out, text STOP at any time.', 'wp-events' ); ?></textarea>
        <small class="wpe-fields-texting-permission wpe-fields-description"><?php esc_html_e( 'This text will be displayed as the label for texting permission checkbox', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Disable Texting Permission field
	 */
	public function wpe_settings_reg_enable_texting_permission_callback() {
		?>
	    <label class="wpe-checkbox">
        <input name="wpe_forms_settings[reg_enable_texting_permission]" id="wpe_reg_enable_texting_permission" value="yes" type="checkbox" <?php echo isset($this->wpe_form_settings['reg_enable_texting_permission']) ? 'checked' : ''; ?> />
        <span class="slider round"></span>
    	</label>
        <small class=""><?php esc_html_e( 'Check To show the Texting permission field.', 'wp-events' ); ?></small>
	    <?php
	}

    /**
     * Settings Field wpe_settings_form_success callback
     */
    public function wpe_settings_subscriber_form_success_callback() {
	    ?>
        <input class="wpe-settings-field" name="wpe_forms_settings[subsc_form_success]" id="wpe_subsc_form_success" type="url" value="<?php echo isset( $this->wpe_form_settings['subsc_form_success'] ) ? $this->wpe_form_settings['subsc_form_success'] : ''; ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'User will be redirected to entered URL on successful subscription.', 'wp-events' ); ?></small>
	    <?php
    }

    /**
     * Registration Form Field Labels
     *
     * @since 1.0.438
    */
    public function wpe_settings_registration_form_labels_callback() {
	    ?>
	    <label class="wpe-checkbox">
        <input name="wpe_forms_settings[form_labels]" id="wpe_form_labels" value="l_true" type="checkbox" <?php echo isset($this->wpe_form_settings['form_labels']) ? 'checked' : ''; ?> />
        <span class="slider round"></span>
    	</label>
        <small><?php esc_html_e( 'Check To Display Field Labels (This will remove placeholders from fields).', 'wp-events' ); ?></small>
	    <?php
    }

    /**
     * Show Form Labels
    */
    public function wpe_settings_subscriber_form_labels_callback() {
	    ?>
	    <label class="wpe-checkbox">
        <input name="wpe_forms_settings[subscriber_form_labels]" id="wpe_subscriber_form_labels" value="l_true" type="checkbox" <?php echo isset($this->wpe_form_settings['subscriber_form_labels']) ? 'checked' : ''; ?> />
        <span class="slider round"></span>
    	</label>
        <small class=""><?php esc_html_e( 'Check To Display Field Labels (This will remove placeholders from fields).', 'wp-events' ); ?></small>
	    <?php
    }

	
	/**
	 * 
	 * Texting Permission field callback
	 * 
	 */
	public function wpe_settings_subscriber_form_texting_permission_callback() {
		?>
        <textarea name="wpe_forms_settings[subscriber_form_texting_permission]" id="wpe_sub_texting_permission" style="width:50%;height:100px;"><?php echo isset( $this->wpe_form_settings['subscriber_form_texting_permission'] ) ? 
		$this->wpe_form_settings['subscriber_form_texting_permission'] : 
		'I agree to receive texts at the number provided from [wpe_firm_name]. Frequency may vary and include information on appointments, events, and other marketing messages. Message/data rates may apply. To opt-out, text STOP at any time.'; ?></textarea>
        <small class="wpe-fields-texting-permission wpe-fields-description"><?php esc_html_e( 'This text will be displayed as the label for texting permission checkbox', 'wp-events' ); ?></small>
		<?php
	}
	

	/** 
	 * Disable phone number field
	 */
	public function wpe_settings_subscriber_enable_phone_number_callback() {
		?>
	    <label class="wpe-checkbox">
        <input name="wpe_forms_settings[subscriber_enable_phone_number]" id="wpe_subscriber_enable_phone_number" value="l_true" type="checkbox" <?php echo isset($this->wpe_form_settings['subscriber_enable_phone_number']) ? 'checked' : ''; ?> />
        <span class="slider round"></span>
    	</label>
        <small class=""><?php esc_html_e( 'Check To show the phone number field.', 'wp-events' ); ?></small>
	    <?php
	}

	/**
	 * Disable Texting Permission field
	 */
	public function wpe_settings_subscriber_enable_texting_permission_callback() {
		?>
	    <label class="wpe-checkbox">
        <input name="wpe_forms_settings[subscriber_enable_texting_permission]" id="wpe_subscriber_enable_texting_permission" value="l_true" type="checkbox" <?php echo isset($this->wpe_form_settings['subscriber_enable_texting_permission']) ? 'checked' : ''; 
		echo isset($this->wpe_form_settings['subscriber_enable_phone_number']) ? '' : 'disabled';?> />
        <span class="slider round"></span>
    	</label>
        <small class=""><?php esc_html_e( 'Check To show the Texting permission field.', 'wp-events' ); ?></small>
	    <?php
	}

    /**
     * Displays textarea on Settings Page
     *
     * displays saved data before registration form on Single page
    */
    public function wpe_settings_before_registration_callback() {

		$before_registration_content = isset( $this->wpe_form_settings['before_registration_form_message'] ) ? $this->wpe_form_settings['before_registration_form_message'] : '';
		echo wpe_editor( $before_registration_content, 'before_registration_form_message', 'wpe_forms_settings[before_registration_form_message]' );
	    ?>
        <small class="wpe-fields-description"><?php esc_html_e( 'Add HTML or text before registration form', 'wp-events' ); ?></small>
	    <?php
    }

	/**
	 * Displays textarea on Settings Page
	 *
	 * displays saved data after registration form on Single page
	 */
	public function wpe_settings_after_registration_callback() {
		$after_registration_content = isset( $this->wpe_form_settings['after_registration_form_message'] ) ? $this->wpe_form_settings['after_registration_form_message'] : '';
		echo wpe_editor( $after_registration_content, 'after_registration_form_message', 'wpe_forms_settings[after_registration_form_message]' );
		?>
        <small class="wpe-fields-description"><?php esc_html_e( 'Add HTML or text after registration form', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Displays textarea on Settings Page
	 *
	 * displays saved data before Subscriber form on Archive page
     * if no event exists
	 */
	public function wpe_settings_before_subscriber_callback() {
		$before_subscriber_content =  isset( $this->wpe_form_settings['before_subscriber_form_message'] ) ? $this->wpe_form_settings['before_subscriber_form_message'] : '';
		echo wpe_editor( $before_subscriber_content, 'before_subscriber_form_message', 'wpe_forms_settings[before_subscriber_form_message]' );
	    ?>
        <small class="wpe-fields-description"><?php esc_html_e( 'Add HTML or text before subscriber form', 'wp-events' ); ?></small>
	    <?php
    }

	/**
	 * Displays textarea on Settings Page
	 *
	 * displays saved data after Subscriber form on Archive page
	 * if no event exists
	 */
	public function wpe_settings_after_subscriber_callback() {
		$after_subscriber_content =  isset( $this->wpe_form_settings['after_subscriber_form_message'] ) ? $this->wpe_form_settings['after_subscriber_form_message'] : '';
		echo wpe_editor( $after_subscriber_content, 'after_subscriber_form_message', 'wpe_forms_settings[after_subscriber_form_message]' );
		?>
        <small class="wpe-fields-description"><?php esc_html_e( 'Add HTML or text after subscriber form', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Settings Field wpe_settings_form_title callback
     *
     * @since 1.0.438
	 */
	public function wpe_settings_subscriber_form_title_callback() {
		?>
        <input class="wpe-settings-field" name="wpe_forms_settings[subscriber_form_title]" id="wpe_sub_form_title" type="text" value="<?php echo isset( $this->wpe_form_settings['subscriber_form_title'] ) ? $this->wpe_form_settings['subscriber_form_title'] : ''; ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'This title will be displayed at the top of subscriber form', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Settings Field wpe_settings_form_title callback
	 *
	 * @since 1.0.443
	 */
	public function wpe_settings_subscriber_form_description_callback() {
		$subscriber_description_content =  isset( $this->wpe_form_settings['subscriber_form_description'] ) ? $this->wpe_form_settings['subscriber_form_description'] : '';
		echo wpe_editor( $subscriber_description_content, 'subscriber_form_description', 'wpe_forms_settings[subscriber_form_description]' );
		?>
        <small class="wpe-fields-description">vz<?php esc_html_e( 'Subscriber form description will be displayed after title', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Settings Field wpe_settings_form_title callback
     *
     * @since 1.0.438
	 */
	public function wpe_settings_subscriber_form_button_callback() {
		?>
        <input class="wpe-settings-field" name="wpe_forms_settings[subscriber_form_button]" id="wpe_sub_form_button" type="text" value="<?php echo isset( $this->wpe_form_settings['subscriber_form_button'] ) ? $this->wpe_form_settings['subscriber_form_button'] : ''; ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'This text will be displayed at Subscriber form button', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Settings Field wpe_settings_form_title callback
     *
     * @since 1.0.438
	 */
	public function wpe_settings_registration_form_button_callback() {
		?>
        <input class="wpe-settings-field" name="wpe_forms_settings[registration_form_button]" id="wpe_reg_form_button" type="text" value="<?php echo isset( $this->wpe_form_settings['registration_form_button'] ) ? $this->wpe_form_settings['registration_form_button'] : ''; ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'This text will be displayed at Registration form button', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Settings Field wpe_settings_consent_checkbox callback
     *
     * @since 1.2.0
	 */
	public function wpe_settings_consent_checkbox_callback() {
		?>
        <textarea name="wpe_forms_settings[consent_checkbox]" id="wpe_consent_checkbox" style="width:50%;height:100px;"><?php echo isset( $this->wpe_form_settings['consent_checkbox'] ) ? $this->wpe_form_settings['consent_checkbox'] : 'I have read & consent to the above.*'; ?>
		</textarea>
        <small class="wpe-fields-description"><?php esc_html_e( 'This text will be displayed as label for consent checkbox.', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Settings Field wpe_settings_disclaimer_checkbox callback
     *
     * @since 1.2.0
	 */
	public function wpe_settings_disclaimer_checkbox_callback() {
		?>
        <textarea name="wpe_forms_settings[disclaimer_checkbox]" id="wpe_disclaimer_checkbox" style="width:50%;height:100px;"><?php echo isset( $this->wpe_form_settings['disclaimer_checkbox'] ) ? $this->wpe_form_settings['disclaimer_checkbox'] : 'I have read & understand your website Disclaimer.*'; ?>
		</textarea>
        <small class="wpe-fields-description"><?php esc_html_e( 'This text will be displayed as label for disclaimer checkbox.', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Settings Field wpe_settings_hearaboutus_options callback
     *
     * @since 1.2.0
	 */
	public function wpe_settings_hearaboutus_options_callback() {
		?>
        <textarea name="wpe_forms_settings[hearaboutus_options]" id="wpe_hearaboutus_options" style="width:50%;height:100px;"><?php echo isset( $this->wpe_form_settings['hearaboutus_options'] ) ? $this->wpe_form_settings['hearaboutus_options'] : 'An Email I Received, Blog / Facebook, Internet / Search Engine, Landing Pages, Radio and TV, Link from another website, Mailing / Postcard, Newsletter, Newspaper, Other, Referral'; ?>
		</textarea>
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter comma(,) separated values for the dropdown. e.g. (Option 1, Option 2)', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Settings Field wpe_settings_form_title callback
     *
     * @since 1.0.438
	 */
	public function wpe_settings_subscriber_form_section_callback() {
	    ?>
        <div id="subscriber-form-settings">
            <h2><?php esc_html_e( 'Subscriber Form Settings', 'wp-events' ); ?></h2>
            <p><?php esc_html_e( 'All the Subscriber form settings are available here.', 'wp-events' ); ?></p>
        </div>
        <?php
	}

	/**
	 * Settings Field wpe_settings_form_title callback
     *
     * @since 1.0.438
	 */
	public function wpe_settings_registration_form_section_callback() {
		?>
        <div id="registration-form-settings">
			<h2><?php esc_html_e( 'Enable/Disable the Registration Form Fields', 'wp-events' ); ?></h2>
			<p><?php esc_html_e( 'Check To Hide the field from the form.', 'wp-events' ); ?></p>
			<div class="wpe-registration-form">
				<table class="wpe-settings-table">
					<tr>
						<td>
							<span class="label"><?php esc_html_e( 'Address 1', 'wp-events' ); ?></span>
						</td>
						<td>
							<label class="wpe-checkbox">
							<input name="wpe_forms_settings[form_address1]" id="wpe_form_address1" value="l_true" type="checkbox" <?php echo isset( $this->wpe_form_settings['form_address1'] ) ? 'checked' : ''; ?> />
							<span class="slider round"></span>
							</label>
						</td>
						<td>
							<span class="label"><?php esc_html_e( 'Address 2', 'wp-events' ); ?></span>
						</td>
						<td>
							<label class="wpe-checkbox">
							<input name="wpe_forms_settings[form_address2]" id="wpe_form_address2" value="l_true" type="checkbox" <?php echo isset( $this->wpe_form_settings['form_address2'] ) ? 'checked' : ''; ?> />
							<span class="slider round"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<span class="label"><?php esc_html_e( 'City', 'wp-events' ); ?></span>
						</td>
						<td>
							<label class="wpe-checkbox">
							<input name="wpe_forms_settings[form_city]" id="wpe_form_city" value="l_true" type="checkbox" <?php echo isset( $this->wpe_form_settings['form_city'] ) ? 'checked' : ''; ?> />
							<span class="slider round"></span>
							</label>
						</td>
						<td>
							<span class="label"><?php esc_html_e( 'State', 'wp-events' ); ?></span>
						</td>
						<td>
							<label class="wpe-checkbox">
							<input name="wpe_forms_settings[form_state]" id="wpe_form_state" value="l_true" type="checkbox" <?php echo isset( $this->wpe_form_settings['form_state'] ) ? 'checked' : ''; ?> />
							<span class="slider round"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<span class="label"><?php esc_html_e( 'Zip', 'wp-events' ); ?></span>
						</td>
						<td>
							<label class="wpe-checkbox">
							<input name="wpe_forms_settings[form_zip]" id="wpe_form_zip" value="l_true" type="checkbox" <?php echo isset( $this->wpe_form_settings['form_zip'] ) ? 'checked' : ''; ?> />
							<span class="slider round"></span>
							</label>
						</td>
						<td>
							<span class="label"><?php esc_html_e( 'Fax', 'wp-events' ); ?></span>
						</td>
						<td>
							<label class="wpe-checkbox">
							<input name="wpe_forms_settings[form_fax]" id="wpe_form_fax" value="l_true" type="checkbox" <?php echo isset( $this->wpe_form_settings['form_fax'] ) ? 'checked' : ''; ?> />
							<span class="slider round"></span>
							</label>
						</td>
					</tr>
					<tr>
						<td>
							<span class="label"><?php esc_html_e( 'Business Name', 'wp-events' ); ?></span>
						</td>
						<td>
							<label class="wpe-checkbox">
							<input name="wpe_forms_settings[form_businessName]" id="wpe_form_businessName" value="l_true" type="checkbox" <?php echo isset( $this->wpe_form_settings['form_businessName'] ) ? 'checked' : ''; ?> />
							<span class="slider round"></span>
							</label>
						</td>
						<td>
							<span class="label"><?php esc_html_e( 'Hear About Us', 'wp-events' ); ?></span>
						</td>
						<td>
							<label class="wpe-checkbox">
							<input name="wpe_forms_settings[form_hear_about]" id="wpe_form_hear_about" value="l_true" type="checkbox" <?php echo isset( $this->wpe_form_settings['form_hear_about'] ) ? 'checked' : ''; ?> />
							<span class="slider round"></span>
							</label>
						</td>
					</tr>
				</table>
			</div>
			<h2><?php esc_html_e( 'Registration Form Settings', 'wp-events' ); ?></h2>
        	<p><?php esc_html_e( 'All the Registration form settings are available here.', 'wp-events' ); ?></p>
        </div>
		<?php
	}

	/**
	 * ==============================================
     *  Events Tab Fields Callback Functions
     * ==============================================
	 */

    /**
	 * Display Enable/disbale registrations approval
	 *
	 * @access public
	 * @since 1.2.0
	 */
	public function wpe_settings_approve_registrations_callback() {
		$option = get_option( 'wpe_events_settings' );
		?>
        <label class="wpe-checkbox">
            <input name="wpe_events_settings[approve_registrations]" id="wpe_approve_registrations" value="l_true"
                   type="checkbox" <?php echo isset( $option['approve_registrations'] ) ? 'checked' : ''; ?> />
            <span class="slider round"></span>
        </label>
        <small><?php esc_html_e( 'Check to enable option for Registration Cancellation/Approval.', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Move past events to draft
	 *
	 * @access public
	 * @since 1.2.0
	 */
	public function wpe_settings_draft_past_events_callback() {
		$option = get_option( 'wpe_events_settings' );
		?>
        <label class="wpe-checkbox">
            <input name="wpe_events_settings[draft_past_events]" id="draft_past_events" value="l_true"
                   type="checkbox" <?php echo isset( $option['draft_past_events'] ) ? 'checked' : ''; ?> />
            <span class="slider round"></span>
        </label>
        <small><?php esc_html_e( 'Move past events to draft (to exclude from indexing).', 'wp-events' ); ?></small>
		<?php
	}


	/**
	 * ==============================================
     *  Display Tab Fields Callback Functions
     * ==============================================
	 */

	/**
	 * Enable Dark Mode
	 *
	 * @access public
	 * @since 1.4.3
	 */
	public function wpe_settings_enable_darkmode_callback() {
		$option = get_option( 'wpe_display_settings' );
		?>
        <label class="wpe-checkbox">
            <input name="wpe_display_settings[dark_mode]" id="wpe_dark_mode" value="l_true"
                   type="checkbox" <?php echo isset( $option['dark_mode'] ) ? 'checked' : ''; ?> />
            <span class="slider round"></span>
        </label>
        <small><?php esc_html_e( 'Check to enable dark mode.', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Disable Archive Page
	 *
	 * @access public
	 * @since 1.4.3
	 */
	public function wpe_settings_disable_archive_callback() {
		$option = get_option( 'wpe_display_settings' );
		?>
        <label class="wpe-checkbox">
            <input name="wpe_display_settings[disable_archive]" id="wpe_disable_archive" value="l_true"
                   type="checkbox" <?php echo isset( $option['disable_archive'] ) ? 'checked' : ''; ?> />
            <span class="slider round"></span>
        </label>
        <small><?php esc_html_e( 'Check to disable archive page. (Please refresh the permalinks after saving the changes).', 'wp-events' ); ?></small>
		<?php
	}



    /**
     * Settings Field wpe_settings_archive_posts callback
    */
    public function wpe_settings_archive_posts_callback() {
	    $option= get_option('wpe_display_settings');
	    ?>
        <input class="wpe-settings-field" name="wpe_display_settings[archive_posts]" id="wpe_archive_posts" type="number" value="<?php echo isset( $option['archive_posts'] ) ? $option['archive_posts'] : 12; ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'Number of Events To Display Per Page', 'wp-events' ); ?></small>
	    <?php
    }

    /**
     * Field to change archive page title
     *
     * @access public
     * @since 1.0.0
    */
	public function wpe_settings_archive_title_callback() {
		$option= get_option('wpe_display_settings');
		?>
        <input class="wpe-settings-field" name="wpe_display_settings[archive_title]" id="wpe_archive_title" type="text" value="<?php echo isset( $option['archive_title'] ) ? $option['archive_title'] : '' ; ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'Archive page Title', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Details and Registration Button
	 *
	 * Displays registration button on events archive
	 *
	 * @access public
	 * @since 1.1.1
	 */
	public function wpe_settings_registration_button_callback() {
		$option = get_option( 'wpe_display_settings' );
		?>
        <label class="wpe-checkbox">
            <input name="wpe_display_settings[reg_button]" id="wpe_reg_button" value="l_true"
                   type="checkbox" <?php echo isset( $option['reg_button'] ) ? 'checked' : ''; ?> />
            <span class="slider round"></span>
        </label>
        <small><?php esc_html_e( 'Enable To Display Registration Button on Events Archive Page.', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Views to show on archive page
	 *
	 *
	 * @access public
	 * @since 1.5.0
	 */
	public function wpe_settings_archive_views_callback() {
		$wpe_display_settings = get_option( 'wpe_display_settings' );
		?>
        <div class="wpe-archive-views">
			<table class="wpe-settings-table">
				<tr>
					<td>
						<span class="label"><?php esc_html_e( 'List', 'wp-events' ); ?></span>
					</td>
					<td>
						<label class="wpe-checkbox">
						<input name="wpe_display_settings[list_view]" id="wpe_list_view" value="yes" type="checkbox" <?php echo isset( $wpe_display_settings['list_view'] ) ? 'checked' : ''; ?> />
						<span class="slider round"></span>
						</label>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}


	/**
	 * Details and Registration Button Text
	 *
	 * Chooses text to display on registration button in events archive
	 *
	 * @access public
	 * @since 1.1.1
	 */
	public function wpe_settings_reg_button_text_callback() {
		$option = get_option( 'wpe_display_settings' );
		?>
        <input class="wpe-settings-field" name="wpe_display_settings[button_text]" id="wpe_button_text" type="text"
               value="<?php echo $option['button_text'] ?? 'Register'; ?>"/>
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter Text to display on Registration Button.', 'wp-events' ); ?></small>
		<?php
	}

	/**
     * Text for closed registrations message
     *
     * @access public
     * @since 1.1.1
	*/
	public function wpe_settings_closed_registrations_text_callback() {
		$option = get_option( 'wpe_display_settings' );
		?>
        <input class="wpe-settings-field" name="wpe_display_settings[closed_reg]" id="wpe_closed_reg" type="text"
               value="<?php echo isset( $option['closed_reg'] ) ? $option['closed_reg'] : 'Event Seats Quota is Full'; ?>"/>
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter Text to display when Registrations are closed.', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Maximum number of bookings allowed per registration
	 *
	 * @access public
	 * @since 1.1.1
	 */
	public function wpe_settings_max_seats_callback() {
		$option = get_option( 'wpe_display_settings' );
		?>
		<input class="wpe-settings-field" name="wpe_display_settings[max_seats]" id="wpe_max_seats" type="number"
		       min="1" max="10" value="<?php echo isset( $option['max_seats'] ) ? $option['max_seats'] : 10; ?>"/>
		<small class="wpe-fields-description"><?php esc_html_e( 'Enter Maximum Number of Seats Allowed in One Registration.', 'wp-events' ); ?></small>
		<?php
	}



	/**
     * ==============================================
     *  Mail Tab Fields Callback Functions
     * ==============================================
	*/

	/**
     * mail sender email Callback
	*/
	public function wpe_settings_mail_from_callback() {
		$option = get_option('wpe_mail_settings');
		?>
        <input class="wpe-settings-field" name="wpe_mail_settings[mail_from]" id="wpe_mail_from" type="text" value="<?php echo isset( $option['mail_from'] ) ? $option['mail_from'] : get_option('admin_email'); ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter email from users will receive email (It is recommended to use email like name@yourdomain.com)', 'wp-events' ); ?></small>
		<?php
    }
	/**
     * Disable Webinar confirmation
	*/
	public function wpe_settings_enable_webinar_conformation_callback() {
		$option = get_option('wpe_mail_settings');
		?>
           <label class="wpe-checkbox">
            <input name="wpe_mail_settings[enable_webinar_conformation]" id="wpe_enable_webinar_c" value="l_true"
                   type="checkbox" <?php echo isset( $option['enable_webinar_conformation'] ) ? 'checked' : ''; ?> />
            <span class="slider round"></span>
        </label>
        <small><?php esc_html_e( 'Check this box to enable the webinar confirmation email for registrants.', 'wp-events' ); ?></small>
		<?php
    }

	/**
     * ==================================================
     *  Firm Tab Fields Callback Functions
     * ==================================================
	*/

	/**
	 * firm Owner Callback
	 */
    public function wpe_settings_owner_name_callback() {
	    $option= get_option('wpe_firm_settings');
	    ?>
        <input class="wpe-settings-field" name="wpe_firm_settings[owner_name]" id="wpe_owner_name" type="text" value="<?php echo isset( $option['owner_name'] ) ? $option['owner_name'] : '' ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter Name of Owner for your firm', 'wp-events' ); ?></small>
	    <?php
    }

	/**
     * mail sender email Callback
	*/
	public function wpe_settings_admin_from_callback() {
		$option= get_option('wpe_firm_settings');
		?>
        <input class="wpe-settings-field" name="wpe_firm_settings[admin_mail]" id="wpe_admin_mail" type="text" value="<?php echo isset( $option['admin_mail'] ) ? $option['admin_mail'] : get_option('admin_email'); ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter contact email address of firm', 'wp-events' ); ?></small>
		<?php
    }

	/**
	 * mail sender name Callback
	 */
    public function wpe_settings_mail_from_name_callback() {
	    $option= get_option('wpe_firm_settings');
	    ?>
        <input class="wpe-settings-field" name="wpe_firm_settings[mail_from_name]" id="wpe_mail_from_name" type="text" value="<?php echo isset( $option['mail_from_name'] ) ? $option['mail_from_name'] : get_bloginfo( 'name' ); ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter Name of your firm', 'wp-events' ); ?></small>
	    <?php
    }

	/**
	 * firm phone Callback
	 */
    public function wpe_settings_phone_field_callback() {
	    $option= get_option('wpe_firm_settings');
	    ?>
        <input class="wpe-settings-field" name="wpe_firm_settings[firm_phone]" id="wpe_firm_phone" type="text" value="<?php echo isset( $option['firm_phone'] ) ? $option['firm_phone'] : '(XXX) XXX-XXXX' ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter Phone Number of your firm', 'wp-events' ); ?></small>
	    <?php
    }

	/**
	 * firm fax Callback
	 */
    public function wpe_settings_fax_field_callback() {
	    $option= get_option('wpe_firm_settings');
	    ?>
        <input class="wpe-settings-field" name="wpe_firm_settings[firm_fax]" id="wpe_firm_fax" type="text" value="<?php echo isset( $option['firm_fax'] ) ? $option['firm_fax'] : '' ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter Fax Number of your firm', 'wp-events' ); ?></small>
	    <?php
    }

	/**
     * ==================================================
     *  reCAPTCHA Tab Fields Callback Functions
     * ==================================================
	*/

	/**
	 * reCAPTCHA type Callback
	 */
    public function wpe_settings_reCAPTCHA_type_callback() {
	    $option = get_option('wpe_reCAPTCHA_settings');
	    ?>
		<div id="wpe-radio-div">
			<input type="radio" id="wpe_reCAPTCHA_invisible" name="wpe_reCAPTCHA_settings[reCAPTCHA_type]" value="invisible" <?php if( $option['reCAPTCHA_type'] == 'invisible') echo 'checked="checked"'; ?> >
			<label for="wpe_reCAPTCHA_invisible"><?php esc_html_e( 'Invisible', 'wp-events' ); ?></label><br>
			<input type="radio" id="wpe_reCAPTCHA_checkbox" name="wpe_reCAPTCHA_settings[reCAPTCHA_type]" value="checkbox" <?php if( $option['reCAPTCHA_type'] == 'checkbox') echo 'checked="checked"'; ?> >
			<label for="wpe_reCAPTCHA_checkbox"><?php esc_html_e( 'Checkbox', 'wp-events' ); ?></label>
			<small class="wpe-fields-description"><?php esc_html_e( 'Select type of reCAPTCHA for forms.', 'wp-events' ); ?></small>
		</div>
	    <?php
    }

	/**
	 * Settings Field wpe_settings_reCAPTCHA_sitekey callback
     *
     * @since 1.3.0
	 */
	public function wpe_settings_reCAPTCHA_sitekey_callback() {
		$option = get_option('wpe_reCAPTCHA_settings');
		?>
        <input name="wpe_reCAPTCHA_settings[reCAPTCHA_site_key]" id="wpe_reCAPTCHA_site_key" style="width:50%;padding:5px 10px;"
		type="text" value="<?php echo isset( $option['reCAPTCHA_site_key'] ) ? $option['reCAPTCHA_site_key'] : ''; ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter your reCAPTCHA Site Key, if you do not have a key you can register ', 'wp-events' ); ?>
		<a href="https://www.google.com/recaptcha/admin/create" target="_blank"><?php esc_html_e( 'here.', 'wp-events' ); ?></a> <?php esc_html_e( 'reCAPTCHA is a free service.', 'wp-events' ); ?></small>
		<?php
	}

	/**
	 * Settings Field wpe_settings_reCAPTCHA_secretkey callback
     *
     * @since 1.3.0
	 */
	public function wpe_settings_reCAPTCHA_secretkey_callback() {
		$option = get_option('wpe_reCAPTCHA_settings');
		?>
        <input name="wpe_reCAPTCHA_settings[reCAPTCHA_secret_key]" id="wpe_reCAPTCHA_secret_key" style="width:50%;padding:5px 10px;"
		type="text" value="<?php echo isset( $option['reCAPTCHA_secret_key'] ) ? $option['reCAPTCHA_secret_key'] : ''; ?>" />
        <small class="wpe-fields-description"><?php esc_html_e( 'Enter your reCAPTCHA Secret Key, if you do not have a key you can register ', 'wp-events' ); ?>
		<a href="https://www.google.com/recaptcha/admin/create" target="_blank"><?php esc_html_e( 'here.', 'wp-events' ); ?></a> <?php esc_html_e( 'reCAPTCHA is a free service.', 'wp-events' ); ?></small>
		<?php
	}

	/**
     * ==============================================
     *  Export Tab Fields Callback Functions
     * ==============================================
	*/

	/**
     * export events button Callback
	*/
	public function wpe_settings_export_events_callback() {
		$option= get_option('wpe_export_settings');
		?>
        <input class="wpe_export_events button button-primary" name="wpe_export_settings[export_button]" id="wpe_export_events" type="button" value="Export Events" />
        <small class="wpe-fields-description"><?php esc_html_e( 'Export Events to CSV', 'wp-events' ); ?></small>
		<?php
    }

	/**
     * Events Status Filter Callback
	*/
    public function wpe_settings_events_filters_callback() {

    	  // Set to 'All' as a default if the option does not exist
    	$options = get_option('wpe_export_settings', [ 'post_status' => 'All' ] );
    	$post_status = $options['post_status'];

    	// Define the select option values for post status
    	$items = array( 'All', 'Past', 'Future', 'On Going');

    	echo "<select id='post_status' name='wpe_export_settings[post_status]'>";

    	foreach( $items as $item ) {

    	  // Loop through the option values
    	  // If saved option matches the option value, select it
    	  echo "<option value='" . esc_attr( $item ) . "' ".selected( $post_status, $item, false ).">" . esc_html( $item ) . "</option>";
		}

		echo "</select>
		<small class='wpe-fields-description'>Select Status from Dropdown</small>";
	}

	/**
	 * prints shortcode's tooltip
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function shortcode_helper_tooltip() {
		return '<div class="shortcode-info dashicons dashicons-info-outline">
            <span class="shortcode-text">
                <div class="left-col">
                    <span>Event Name:</span>
                    <span>User First Name:</span>
                    <span>User Last Name:</span>
                    <span>User Email:</span>
                    <span>User Phone:</span>
                    <span>Event Details:</span>
                    <span>Registration Details:</span>
                    <span>Event Date and Time:</span>
                    <span>Link to Single Event Page:</span>
                    <span>Number of Registered Seats:</span>
                    <span>Site URL:</span>
					<span>Name of firm:</span>
					<span>Email for Contact:</span>
					<span>Phone No. of firm:</span>
					<span>Owner of firm:</span>
					</div>
                <div class="right-col">
                    <span>[wpe_event_name]</span>
                    <span>[wpe_user_first_name]</span>
                    <span>[wpe_user_last_name]</span>
                    <span>[wpe_user_email]</span>
					<span>[wpe_user_phone]</span>
                    <span>[wpe_event_details]</span>
                    <span>[wpe_registration_details]</span>
                    <span>[wpe_event_date_time]</span>
                    <span>[wpe_event_link]</span>
                    <span>[wpe_event_seats]</span>
                    <span>[wpe_site_url]</span>
					<span>[wpe_firm_name]</span>
					<span>[wpe_notification_email]</span>
					<span>[wpe_firm_phone]</span>
					<span>[wpe_owner_name]</span>
					</div>
            </span>
        </div>';
	}

	/**
	 * Sanitize Settings Fields Before Saving Data
	 *
	 * @param array $options
	 *
	 * @return array
	 * @since 1.1.1
	 */
	public function sanitize_settings_fields( $options ) {
		foreach ( $options as $key => $value ) {
			$options[ $key ] = sanitize_text_field( $value );
		}

		return $options;
	}

	/**
	 * Sanitize Forms Settings Fields Before Saving Data
	 *
	 * @param array $options
	 *
	 * @return array
	 * @since 1.3.0
	 */
	public function sanitize_forms_settings( $options ) {
		foreach ( $options as $key => $value ) {
			$options[ $key ] = strip_tags( $value, "<b><br><p><a><img><strong><h1><h2><h3><h4><h5><h6><ol><ul><li><span><em><blockquote><del><ins>" );
		}

		return $options;
	}

	/**
	 * @param  array  $wpe_settings_tabs
	 */
	public function set_wpe_settings_tabs( array $wpe_settings_tabs = [] ) : void {
		if ( $wpe_settings_tabs === [] ) {
			$this->wpe_settings_tabs = [
				'general',
				'events',
				'display',
				'forms',
				'mail',
				'firm',
				'reCAPTCHA',
				'export',
			];
		} else {
			$this->wpe_settings_tabs = $wpe_settings_tabs;
		}

		$this->wpe_settings_tabs = apply_filters( 'wpe_settings_tabs', $this->wpe_settings_tabs );
	}

}