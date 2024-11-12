
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
