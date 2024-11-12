<?php
/**
 * GeneratePress.
 *
 * Please do not make any edits to this file. All edits should be done in a child theme.
 *
 * @package GeneratePress
 */


// 创建PCB询盘表的菜单
function add_pcb_form_menu() {
    add_menu_page(
        'PCB Form Data', // 页面标题
        'PCB询盘表', // 菜单标题
        'manage_options',   // 用户权限
        'pcb-form-data', // 菜单别名
        'display_pcb_form_data' // 回调函数
    );
}
add_action('admin_menu', 'add_pcb_form_menu');

// pcb回调函数

function display_pcb_form_data() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pcb_form_data';
    // 查询时按 created_at 字段倒序排列
    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC" );
    
    // 添加 CSS 样式，使表格有边框像 Excel 表格
    echo '<style>
            table {
                border-collapse: collapse;
                width: 100%;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
          </style>';
  
    echo '<h1>PCB表单提交数据</h1>';
    echo '<table>';
    echo '<tr><th>序号</th><th>创建时间</th><th>基础数据</th><th>PCB名称</th><th>面板数量</th><th>交货期限</th><th>勾选数据</th><th>调度单位</th><th>面板参数</th><th>个人信息</th>';
    $index = 1; // 初始化序号
    foreach ($results as $row) {
        echo '<tr>';
        echo '<td>' . esc_html($index++) . '</td>'; // 显示序号并递增
        echo '<td>' . esc_html($row->created_at) . '</td>';
        // 基础数据
        echo '<td>';
        echo '国际武器贸易条例: ' . esc_html($row->ITAR) . '<br>';
        echo '首产品: ' . esc_html($row->first_article) . '<br>';
        echo '测试板: ' . esc_html($row->test_coupons) . '<br>';
        echo '</td>';
        echo '<td>' . esc_html($row->dispatch_unit) . '</td>'; //调度单位
        echo '</td>';
        echo '<td>' . esc_html($row->pcb_name) . '</td>'; //PCB名称
        echo '<td>' . esc_html($row->quantity) . '</td>'; //面板数量
        echo '<td>' . esc_html($row->delivery_term) . '</td>'; //交货期限
        // 三个勾选框
        echo '<td>';
        echo '国际武器贸易条例: ' . esc_html($row->ITAR) . '<br>';
        echo '首产品: ' . esc_html($row->first_article) . '<br>';
        echo '测试板: ' . esc_html($row->test_coupons) . '<br>';
        echo '</td>';
        echo '<td>' . esc_html($row->dispatch_unit) . '</td>'; //调度单位
        echo '</td>';
        // 自定义参数
        echo '<td>';
        echo '尺寸宽: ' . esc_html($row->dimension_width) . '<br>';
        echo '尺寸高: ' . esc_html($row->dimension_height) . '<br>';
        echo 'PCB/面板: ' . esc_html($row->pcbs_panel) . '<br>';
        echo '不同的设计/面板: ' . esc_html($row->different_designs_panel) . '<br>';
        echo '材料: ' . esc_html($row->material) . '<br>';
        echo '板层: ' . esc_html($row->board_layers) . '<br>';
        echo '板厚: ' . esc_html($row->board_thickness) . '<br>';
        echo '表面处理: ' . esc_html($row->surface_finish) . '<br>';
        echo '材料热导率: ' . esc_html($row->material_tg) . '<br>';
        echo '镀层铜厚度: ' . esc_html($row->finish_cu_thickness) . '<br>';
        echo '内层铜厚度: ' . esc_html($row->inner_cu_thickness) . '<br>';
        echo '硬金标签: ' . esc_html($row->hard_gold_tab) . '<br>';
        echo '金厚度: ' . esc_html($row->au_thickness) . '<br>';
        echo '阻焊层顶部: ' . esc_html($row->solder_mask_top) . '<br>';
        echo '图例顶部: ' . esc_html($row->legend_top) . '<br>';
        echo '阻焊层底部: ' . esc_html($row->solder_mask_bottom) . '<br>';
        echo '图例底部: ' . esc_html($row->legend_bottom) . '<br>';
        // 附件链接（如果有）
        if (!empty($row->gerber_file_url)) {
            $short_url = strlen($row->gerber_file_url) > 30 ? substr($row->gerber_file_url, 0, 30) . '...' : $row->gerber_file_url;
            echo '附件: <a href="' . esc_url($row->gerber_file_url) . '" target="_blank">' . esc_html($short_url) . '</a>';
        } else {
            echo '附件: 无附件';
        }
        echo '</td>';
        echo '<td>';
        echo '姓名: ' . esc_html($row->name) . '<br>';
        echo '邮箱: ' . esc_html($row->email) . '<br>';
        echo '手机号: ' . esc_html($row->phone) . '<br>';
        echo '公司: ' . esc_html($row->company) . '<br>';
        echo '应用领域: ' . esc_html($row->application) . '<br>';
        echo '留言: ' . esc_html($row->message) . '<br>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
  }

function handle_pcb_form_submission() {
    // 检查是否有POST请求
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 获取并处理表单数据
        $pcb_name = sanitize_text_field($_POST['pcb_name']);
        $quantity = sanitize_text_field($_POST['quantity']);
        $delivery_term = sanitize_text_field($_POST['delivery_term']);
        $ITAR = sanitize_text_field($_POST['ITAR']);
        $first_article = sanitize_text_field($_POST['first_article']);
        $test_coupons = sanitize_text_field($_POST['test_coupons']);
        $dispatch_unit = sanitize_text_field($_POST['dispatch_unit']);
        $dimension_width = sanitize_text_field($_POST['dimension_width']);
        $dimension_height = sanitize_text_field($_POST['dimension_height']);
        $pcbs_panel = sanitize_text_field($_POST['pcbs_panel']);
        $different_designs_panel = sanitize_text_field($_POST['different_designs_panel']);
        $material = sanitize_text_field($_POST['material']);
        $board_layers = sanitize_text_field($_POST['board_layers']);
        $board_thickness = sanitize_text_field($_POST['board_thickness']);
        $surface_finish = sanitize_text_field($_POST['surface_finish']);
        $material_tg = sanitize_text_field($_POST['material_tg']);
        $finish_cu_thickness = sanitize_text_field($_POST['finish_cu_thickness']);
        $inner_cu_thickness = sanitize_text_field($_POST['inner_cu_thickness']);
        $hard_gold_tab = sanitize_text_field($_POST['hard_gold_tab']);
        $au_thickness = sanitize_text_field($_POST['au_thickness']);
        $solder_mask_top = sanitize_text_field($_POST['solder_mask_top']);
        $legend_top = sanitize_text_field($_POST['legend_top']);
        $solder_mask_bottom = sanitize_text_field($_POST['solder_mask_bottom']);
        $legend_bottom = sanitize_text_field($_POST['legend_bottom']);
        // 处理文件上传
        if (isset($_FILES['gerber_file_url'])) {
            $uploaded_file = $_FILES['gerber_file_url'];
            $upload_overrides = array('test_form' => false);

            // 使用 wp_handle_upload 函数上传文件
            $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

            if ($movefile && !isset($movefile['error'])) {
                // 上传成功，获取文件URL
                $gerber_file_url = $movefile['url'];
            } else {
                // 上传失败，记录错误信息
                $gerber_file_url = '';
            }
        } else {
            $gerber_file_url = ''; // 如果没有上传文件，设置为空
        }
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_text_field($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $company = sanitize_text_field($_POST['company']);
        $application = sanitize_text_field($_POST['application']);
        $message = sanitize_text_field($_POST['message']);
        // 保存数据到自定义表
        global $wpdb;
        $table_name = $wpdb->prefix . 'pcb_form_data';
        $wpdb->insert($table_name, array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'application' => $application,
            'message' => $message,
            'pcb_name' => $pcb_name,
            'quantity' => $quantity,
            'delivery_term' => $delivery_term,
            'ITAR' => $ITAR,
            'first_article' => $first_article,
            'test_coupons' => $test_coupons,
            'dispatch_unit' => $dispatch_unit,
            'dimension_width' => $dimension_width,
            'dimension_height' => $dimension_height,
            'pcbs_panel' => $pcbs_panel,
            'different_designs_panel' => $different_designs_panel,
            'material' => $material,
            'board_layers' => $board_layers,
            'board_thickness' => $board_thickness,
            'surface_finish' => $surface_finish,
            'material_tg' => $material_tg,
            'finish_cu_thickness' => $finish_cu_thickness,
            'inner_cu_thickness' => $inner_cu_thickness,
            'hard_gold_tab' => $hard_gold_tab,
            'au_thickness' => $au_thickness,
            'solder_mask_top' => $solder_mask_top,
            'legend_top' => $legend_top,
            'solder_mask_bottom' => $solder_mask_bottom,
            'legend_bottom' => $legend_bottom,
            'gerber_file_url' => $gerber_file_url,
            'created_at' => current_time('mysql'),
        ));
  
        // 返回JSON响应
        wp_send_json_success('表单提交成功！');
    } else {
        wp_send_json_error('提交失败');
    }
  }
  
  // 注册AJAX处理函数
  add_action('wp_ajax_handle_pcb_form', 'handle_pcb_form_submission');
  add_action('wp_ajax_nopriv_handle_pcb_form', 'handle_pcb_form_submission');

// 在激活插件或主题时创建数据库表
function create_pcb_form_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pcb_form_data';
    $charset_collate = $wpdb->get_charset_collate();
  
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        email text DEFAULT '' NOT NULL,
        phone text DEFAULT '' NOT NULL,
        company text DEFAULT '' NOT NULL,
        application text DEFAULT '' NOT NULL,
        message text DEFAULT '' NOT NULL,
        pcb_name text DEFAULT '' NOT NULL,
        quantity text DEFAULT '' NOT NULL,
        delivery_term text DEFAULT '' NOT NULL,
        ITAR text DEFAULT '' NOT NULL,
        first_article text DEFAULT '' NOT NULL,
        test_coupons text DEFAULT '' NOT NULL,
        dispatch_unit text DEFAULT '' NOT NULL,
        dimension_width text DEFAULT '' NOT NULL,
        dimension_height text DEFAULT '' NOT NULL,
        pcbs_panel text DEFAULT '' NOT NULL,
        different_designs_panel text DEFAULT '' NOT NULL,
        material text DEFAULT '' NOT NULL,
        board_layers text DEFAULT '' NOT NULL,
        board_thickness text DEFAULT '' NOT NULL,
        surface_finish text DEFAULT '' NOT NULL,
        material_tg text DEFAULT '' NOT NULL,
        finish_cu_thickness text DEFAULT '' NOT NULL,
        inner_cu_thickness text DEFAULT '' NOT NULL,
        hard_gold_tab text DEFAULT '' NOT NULL,
        au_thickness text DEFAULT '' NOT NULL,
        solder_mask_top text DEFAULT '' NOT NULL,
        legend_top text DEFAULT '' NOT NULL,
        solder_mask_bottom text DEFAULT '' NOT NULL,
        legend_bottom text DEFAULT '' NOT NULL,
        gerber_file_url text DEFAULT '' NOT NULL,
        created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
  
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }
  add_action('after_setup_theme', 'create_pcb_form_table');  




// 创建多步骤询盘表的菜单
function add_custom_form_menu() {
    add_menu_page(
        'Custom Form Data', // 页面标题
        '多步骤需求调查表', // 菜单标题
        'manage_options',   // 用户权限
        'custom-form-data', // 菜单别名
        'display_custom_form_data' // 回调函数
    );
}
add_action('admin_menu', 'add_custom_form_menu');


// 回调函数
function display_custom_form_data() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'custom_form_data';
  // 查询时按 created_at 字段倒序排列
  $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC" );
  
  // 添加 CSS 样式，使表格有边框像 Excel 表格
  echo '<style>
          table {
              border-collapse: collapse;
              width: 100%;
          }
          table, th, td {
              border: 1px solid black;
          }
          th, td {
              padding: 8px;
              text-align: left;
          }
          th {
              background-color: #f2f2f2;
          }
        </style>';

  echo '<h1>分部署表单提交数据</h1>';
  echo '<table>';
  echo '<tr><th>序号</th><th>创建时间</th><th>基本信息</th><th>行业信息</th><th>公司信息</th><th>产品信息</th><th>项目阶段</th><th>具体任务</th><th>接受发展</th><th>具体材料</th><th>开发周期</th><th>市场调查</th><th>订购量</th></tr>';
  $index = 1; // 初始化序号
  foreach ($results as $row) {
      echo '<tr>';
      echo '<td>' . esc_html($index++) . '</td>'; // 显示序号并递增
      echo '<td>' . esc_html($row->created_at) . '</td>';
      echo '<td>';
      // 基本信息
      echo '姓名: ' . esc_html($row->name) . '<br>';
      echo '邮箱: ' . esc_html($row->email) . '<br>';
      echo '手机号: ' . esc_html($row->phone) . '<br>';
      // 行业
      echo '<td>';
      echo '选择: ' . esc_html($row->industry_select) . '<br>';
      echo '其他: ' . esc_html($row->industry_other) . '<br>';
      echo '<td>';
      // 公司信息
      echo '名称: ' . esc_html($row->company_name) . '<br>';
      echo '规模: ' . esc_html($row->company_employees_size) . '<br>';
      echo '网址: ' . esc_html($row->company_website) . '<br>';
      // 产品信息
      echo '<td>';
      echo '产品: ' . esc_html($row->describe_product) . '<br>';
      echo '链接: ' . esc_html($row->reference_products) . '<br>';
      // 附件链接（如果有）
      if (!empty($row->file_url)) {
          $short_url = strlen($row->file_url) > 30 ? substr($row->file_url, 0, 30) . '...' : $row->file_url;
          echo '附件: <a href="' . esc_url($row->file_url) . '" target="_blank">' . esc_html($short_url) . '</a>';
      } else {
          echo '附件: 无附件';
      }
      // 项目阶段
      echo '<td>';
      echo '阶段: ' . esc_html($row->project_stage) . '<br>';
      echo '其他: ' . esc_html($row->project_stage_other) . '<br>';
      echo '<td>';
      // 具体任务
      echo '名称: ' . esc_html($row->specific_task) . '<br>';
      echo '设计: ' . esc_html($row->specific_task_list) . '<br>'; 
      echo '<td>' . esc_html($row->accept_development) . '</td>';
      echo '<td>';
      // 制作材料
      echo '选择: ' . esc_html($row->specific_material) . '<br>';
      echo '其他: ' . esc_html($row->specific_material_ohter) . '<br>';
      echo '<td>';
      echo '时间: ' . esc_html($row->development_cycle_select) . '<br>';
      echo '需要软件开发吗: ' . esc_html($row->need_software_development) . '<br>';
      // 市场调查
      echo '<td>';
      echo '选择: ' . esc_html($row->market_research) . '<br>';
      echo '注明: ' . esc_html($row->market_research_price) . '<br>';
      echo '其他: ' . esc_html($row->market_research_other) . '<br>';
      echo '<td>';
      echo '数量: ' . esc_html($row->order_quantity) . '<br>';
      echo '金额: ' . esc_html($row->budget) . '</td>';
      echo '<td><a href="#" class="delete-record" data-id="' . esc_attr($row->id) . '">删除</a></td>';
      echo '</tr>';
  }
  echo '</table>';
}

// 删除分布式数据
function handle_delete_custom_form_data() {
    // 验证用户是否有权限删除数据
    if (!current_user_can('manage_options')) {
        wp_send_json_error('无权限删除记录');
    }

    // 检查是否接收到有效的 POST 请求
    if (isset($_POST['record_id'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_form_data';

        // 获取传递的记录 ID
        $record_id = intval($_POST['record_id']);

        // 从数据库中删除记录
        $deleted = $wpdb->delete($table_name, ['id' => $record_id]);

        // 检查删除是否成功
        if ($deleted) {
            wp_send_json_success('记录已成功删除');
        } else {
            wp_send_json_error('删除记录时出错');
        }
    } else {
        wp_send_json_error('无效的请求');
    }
}

// 注册AJAX处理函数
add_action('wp_ajax_delete_custom_form_data', 'handle_delete_custom_form_data');

function enqueue_custom_admin_scripts() {
    // 加载你的自定义 JavaScript 文件，不需要依赖 jQuery
    wp_enqueue_script('custom-admin-scripts', get_template_directory_uri() . '/js/custom-admin.js', array(), null, true);

    // 将 AJAX URL 传递到 JavaScript 文件中
    wp_localize_script('custom-admin-scripts', 'ajaxurl', admin_url('admin-ajax.php'));
}

add_action('admin_enqueue_scripts', 'enqueue_custom_admin_scripts');



function handle_custom_form_submission() {
  // 检查是否有POST请求
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // 获取并处理表单数据
      $name = sanitize_text_field($_POST['name']);
      $email = sanitize_email($_POST['email']);
      $phone = sanitize_text_field($_POST['phone']);
      $industry_select = sanitize_text_field($_POST['industrySelect']);
      $industry_other = sanitize_text_field($_POST['industryOther']);
      $company_name = sanitize_text_field($_POST['companyName']);
      $company_employees_size = sanitize_text_field($_POST['companyEmployeesSize']);
      $company_website = sanitize_text_field($_POST['companyWebsite']);
      $describe_product = sanitize_text_field($_POST['describeProduct']);
      $reference_products = sanitize_text_field($_POST['referenceProducts']);
    //   $file_url = sanitize_text_field($_POST['file_url']);
      $project_stage = sanitize_text_field($_POST['projectStage']);
      $project_stage_other = sanitize_text_field($_POST['projectStageOther']);
      $specific_task = sanitize_text_field($_POST['specific_task']);
      $specific_task_list = sanitize_text_field($_POST['specific_task_list']);
      $accept_development = sanitize_text_field($_POST['accept_development']);
      $specific_material = sanitize_text_field($_POST['specific_material']);
      $specific_material_ohter = sanitize_text_field($_POST['specific_material_ohter']);
      $development_cycle_select = sanitize_text_field($_POST['development_cycle_select']);
      $need_software_development = sanitize_text_field($_POST['need_software_development']);
      $market_research = sanitize_text_field($_POST['market_research']);
      $market_research_price = sanitize_text_field($_POST['market_research_price']);
      $market_research_other = sanitize_text_field($_POST['market_research_other']);
      $order_quantity = sanitize_text_field($_POST['order_quantity']);
      $budget = sanitize_text_field($_POST['budget']);
    // 处理文件上传
    if (isset($_FILES['file_url'])) {
        $uploaded_file = $_FILES['file_url'];
        $upload_overrides = array('test_form' => false);

        // 使用 wp_handle_upload 函数上传文件
        $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            // 上传成功，获取文件URL
            $file_url = $movefile['url'];
        } else {
            // 上传失败，记录错误信息
            $file_url = '';
        }
    } else {
        $file_url = ''; // 如果没有上传文件，设置为空
    }
      // 保存数据到自定义表
      global $wpdb;
      $table_name = $wpdb->prefix . 'custom_form_data';
      $wpdb->insert($table_name, array(
          'name' => $name,
          'email' => $email,
          'phone' => $phone,
          'industry_select' => $industry_select,
          'industry_other' => $industry_other,
          'company_name' => $company_name,
          'company_employees_size' => $company_employees_size,
          'company_website' => $company_website,
          'describe_product' => $describe_product,
          'reference_products' => $reference_products,
          'file_url' => $file_url,
          'project_stage' => $project_stage,
          'project_stage_other' => $project_stage_other,
          'specific_task' => $specific_task,
          'specific_task_list' => $specific_task_list,
          'accept_development' => $accept_development,
          'specific_material' => $specific_material,
          'specific_material_ohter' => $specific_material_ohter,
          'development_cycle_select' => $development_cycle_select,
          'need_software_development' => $need_software_development,
          'market_research' => $market_research,
          'market_research_price' => $market_research_price,
          'market_research_other' => $market_research_other,
          'order_quantity' => $order_quantity,
          'budget' => $budget,
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
      phone varchar(20) DEFAULT '' NOT NULL,
      industry_select varchar(50) DEFAULT '' NOT NULL,
      industry_other text DEFAULT '' NOT NULL,
      company_name text DEFAULT '' NOT NULL,
      company_employees_size text DEFAULT '' NOT NULL,
      company_website text DEFAULT '' NOT NULL,
      describe_product text DEFAULT '' NOT NULL,
      reference_products text DEFAULT '' NOT NULL,
      project_stage varchar(50) DEFAULT '' NOT NULL,
      project_stage_other text DEFAULT '' NOT NULL,
      specific_task text DEFAULT '' NOT NULL,
      specific_task_list text DEFAULT '' NOT NULL,
      accept_development varchar(50) DEFAULT '' NOT NULL,
      specific_material text DEFAULT '' NOT NULL,
      specific_material_ohter text DEFAULT '' NOT NULL,
      development_cycle_select varchar(50) DEFAULT '' NOT NULL,
      need_software_development text DEFAULT '' NOT NULL,
      market_research text DEFAULT '' NOT NULL,
      market_research_price text DEFAULT '' NOT NULL,
      market_research_other text DEFAULT '' NOT NULL,
      order_quantity varchar(50) DEFAULT '' NOT NULL,
      budget varchar(50) DEFAULT '' NOT NULL,
      created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      PRIMARY KEY  (id)
  ) $charset_collate;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}
add_action('after_setup_theme', 'create_custom_form_table');



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
