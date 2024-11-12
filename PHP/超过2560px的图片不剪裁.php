<?php
// 禁止 WordPress 对超过2560px的图片进行裁剪
// 添加代码到functions.php文件
add_filter( 'big_image_size_threshold', '__return_false' );
