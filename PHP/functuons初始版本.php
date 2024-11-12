
<?php
/**
 * GeneratePress.
 *
 * Please do not make any edits to this file. All edits should be done in a child theme.
 *
 * @package GeneratePress
 */

// 更改 Yoast SEO 面包屑中指定页面及其子页面的 Home 链接
add_filter( 'wpseo_breadcrumb_links', 'custom_breadcrumb_for_cleaning_drone' );
function custom_breadcrumb_for_cleaning_drone( $links ) {
    // 获取当前页面对象
    global $post;
    
    // 检查当前页面是否是指定的页面或其子页面
    $parent_page_id = 31617;  // 将 123 替换为 "Cleaning Drone" 页面的 ID
    if ( is_page() && ( $post->ID == $parent_page_id || $post->post_parent == $parent_page_id ) ) {
        
        // 删除默认的第一个 Home 链接
        array_shift( $links );
        
        // 添加自定义的 Home 链接
        $new_home = array(
            'url' => 'https://www.eipeks.com/technologies/drone-development/',  // 自定义的 Home 链接
            'text' => 'Home',  // 面包屑显示的文字
        );
        
        array_unshift( $links, $new_home ); // 将自定义链接添加到面包屑第一个位置
    }
    
    return $links;
}
 
//超过2560px的图片不剪裁
add_filter( 'big_image_size_threshold', '__return_false' );

function add_custom_form_menu() {
    add_menu_page(
        'Custom Form Data', // 页面标题
        'Form Submissions', // 菜单标题
        'manage_options',   // 用户权限
        'custom-form-data', // 菜单别名
        'display_custom_form_data' // 回调函数
    );
}
add_action('admin_menu', 'add_custom_form_menu');

function display_custom_form_data() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_form_data';
    $results = $wpdb->get_results("SELECT * FROM $table_name");
    
    echo '<h1>表单提交数据</h1>';
    echo '<table>';
    echo '<tr><th>Name</th><th>Email</th><th>Date</th></tr>';
    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . esc_html($row->name) . '</td>';
        echo '<td>' . esc_html($row->email) . '</td>';
        echo '<td>' . esc_html($row->created_at) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}
function handle_custom_form_submission() {
    // 检查是否有POST请求
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 获取并处理表单数据
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);

        // 保存数据到自定义表
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_form_data';
        $wpdb->insert($table_name, array(
            'name' => $name,
            'email' => $email,
            'created_at' => current_time('mysql'),
        ));

        // 返回JSON响应
        wp_send_json_success('表单提交成功！');
    } else {
        wp_send_json_error('提交失败');
    }
}

// 注册AJAX处理函数
add_action('wp_ajax_handle_custom_form', 'handle_custom_form_submission');
add_action('wp_ajax_nopriv_handle_custom_form', 'handle_custom_form_submission');

// 在激活插件或主题时创建数据库表
function create_custom_form_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_form_data';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        email text NOT NULL,
        created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_setup_theme', 'create_custom_form_table');


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Set our theme version.
define( 'GENERATE_VERSION', '3.5.1' );

if ( ! function_exists( 'generate_setup' ) ) {
	add_action( 'after_setup_theme', 'generate_setup' );
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since 0.1
	 */
	function generate_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'generatepress' );

		// Add theme support for various features.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'status' ) );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );

		$color_palette = generate_get_editor_color_palette();

		if ( ! empty( $color_palette ) ) {
			add_theme_support( 'editor-color-palette', $color_palette );
		}

		add_theme_support(
			'custom-logo',
			array(
				'height' => 70,
				'width' => 350,
				'flex-height' => true,
				'flex-width' => true,
			)
		);

		// Register primary menu.
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'generatepress' ),
			)
		);

		/**
		 * Set the content width to something large
		 * We set a more accurate width in generate_smart_content_width()
		 */
		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 1200; /* pixels */
		}

		// Add editor styles to the block editor.
		add_theme_support( 'editor-styles' );

		$editor_styles = apply_filters(
			'generate_editor_styles',
			array(
				'assets/css/admin/block-editor.css',
			)
		);

		add_editor_style( $editor_styles );
	}
}

/**
 * Get all necessary theme files
 */
$theme_dir = get_template_directory();

require $theme_dir . '/inc/theme-functions.php';
require $theme_dir . '/inc/defaults.php';
require $theme_dir . '/inc/class-css.php';
require $theme_dir . '/inc/css-output.php';
require $theme_dir . '/inc/general.php';
require $theme_dir . '/inc/customizer.php';
require $theme_dir . '/inc/markup.php';
require $theme_dir . '/inc/typography.php';
require $theme_dir . '/inc/plugin-compat.php';
require $theme_dir . '/inc/block-editor.php';
require $theme_dir . '/inc/class-typography.php';
require $theme_dir . '/inc/class-typography-migration.php';
require $theme_dir . '/inc/class-html-attributes.php';
require $theme_dir . '/inc/class-theme-update.php';
require $theme_dir . '/inc/class-rest.php';
require $theme_dir . '/inc/deprecated.php';

if ( is_admin() ) {
	require $theme_dir . '/inc/meta-box.php';
	require $theme_dir . '/inc/class-dashboard.php';
}

/**
 * Load our theme structure
 */
require $theme_dir . '/inc/structure/archives.php';
require $theme_dir . '/inc/structure/comments.php';
require $theme_dir . '/inc/structure/featured-images.php';
require $theme_dir . '/inc/structure/footer.php';
require $theme_dir . '/inc/structure/header.php';
require $theme_dir . '/inc/structure/navigation.php';
require $theme_dir . '/inc/structure/post-meta.php';
require $theme_dir . '/inc/structure/sidebars.php';
require $theme_dir . '/inc/structure/search-modal.php';
