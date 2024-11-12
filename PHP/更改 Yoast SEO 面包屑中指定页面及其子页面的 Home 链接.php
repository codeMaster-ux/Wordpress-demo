<?php
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
