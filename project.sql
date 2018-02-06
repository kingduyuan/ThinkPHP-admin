SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- 管理员表
-- ----------------------------
DROP TABLE IF EXISTS `my_admin`;
CREATE TABLE `my_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL COMMENT '账号',
  `password` char(40) NOT NULL COMMENT '密码(使用sha1()加密)',
  `email` varchar(50) DEFAULT NULL COMMENT '管理员邮箱',
  `face` varchar(100) DEFAULT '' COMMENT '用户头像',
  `roles` varchar(255) DEFAULT NULL COMMENT '权限',
  `auto_key` char(20) DEFAULT NULL COMMENT '自动登录的KEY',
  `access_token` char(40) DEFAULT NULL COMMENT '自动登录TOKEN',
  `status` tinyint(2) NOT NULL COMMENT '管理员状态',
  `create_id` int(11) NOT NULL COMMENT '创建管理员',
  `create_time` int(11) DEFAULT NULL COMMENT '注册时间',
  `last_time` int(11) DEFAULT NULL COMMENT '最后登录的时间',
  `last_ip` varchar(20) DEFAULT NULL COMMENT '最后登录IP',
  `update_time` int(11) NOT NULL DEFAULT '1' COMMENT '修改时间',
  `update_id` int(11) NOT NULL DEFAULT '1' COMMENT '修改用户',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='后台管理员信息表';

-- ----------------------------
-- 管理员信息
-- ----------------------------
INSERT INTO `my_admin` VALUES ('1', 'admin', 'f865b53623b121fd34ee5426c792e5c33af8c227', 'admin@qq.com', '/Public/Uploads/20160704/Admin577a423364339.jpg', '', '', '', '1', '0', '1457604078', '1467715355', '127.0.0.1', '1', '1');
INSERT INTO `my_admin` VALUES ('2', 'liujinxing', 'e74057e4af210894e68ae86918e051929bb6d85f', '821901008@qq.com', '/Public/Uploads/20160705/Admin577b8efe9d4ab.jpg', 'user', '', '', '1', '0', '1457606311', '1467368469', '127.0.0.1', '1', '1');

-- ----------------------------
-- 角色和权限表
-- ----------------------------
DROP TABLE IF EXISTS `my_auth_item`;
CREATE TABLE `my_auth_item` (
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '权限或者角色名',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '类型 1 角色 2 权限',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '说明信息',
  `data` varchar(255) DEFAULT NULL COMMENT '其他配置信息',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限和角色信息表';

-- ----------------------------
-- 角色和权限信息数据
-- ----------------------------
INSERT INTO `my_auth_item` VALUES ('/admin/admin/index', '2', '管理员信息显示', null, '1467103547', '1467103547');
INSERT INTO `my_auth_item` VALUES ('/admin/admin/login', '2', '管理员欢迎页面显示', null, '1467191163', '1467191163');
INSERT INTO `my_auth_item` VALUES ('/admin/admin/search', '2', '管理员信息搜索', null, '1467103592', '1467103592');
INSERT INTO `my_auth_item` VALUES ('/admin/admin/update', '2', '管理员信息编辑', null, '1467103571', '1467103571');
INSERT INTO `my_auth_item` VALUES ('/admin/admin/upload', '2', '管理员头像上传', null, '1467103571', '1467103571');
INSERT INTO `my_auth_item` VALUES ('/admin/auth/index', '2', '权限管理显示', null, '1467103726', '1467103726');
INSERT INTO `my_auth_item` VALUES ('/admin/auth/search', '2', '权限信息搜索', null, '1467103781', '1467103781');
INSERT INTO `my_auth_item` VALUES ('/admin/auth/update', '2', '权限信息编辑', null, '1467103757', '1467103757');
INSERT INTO `my_auth_item` VALUES ('/admin/menu/index', '2', '导航栏目显示', null, '1467372709', '1467715261');
INSERT INTO `my_auth_item` VALUES ('/admin/menu/search', '2', '导航栏目搜索', null, '1467082050', '1467082050');
INSERT INTO `my_auth_item` VALUES ('/admin/menu/update', '2', '导航栏目编辑', null, '1467082073', '1467082073');
INSERT INTO `my_auth_item` VALUES ('/admin/module/create', '2', '模块生成编辑', null, '1467103886', '1467103886');
INSERT INTO `my_auth_item` VALUES ('/admin/module/index', '2', '模块生成显示', null, '1467103861', '1467103861');
INSERT INTO `my_auth_item` VALUES ('/admin/role/allocation', '2', '角色权限分配', null, '1467279058', '1467279058');
INSERT INTO `my_auth_item` VALUES ('/admin/role/create', '2', '角色分配权限操作', null, '1467280201', '1467347971');
INSERT INTO `my_auth_item` VALUES ('/admin/role/index', '2', '角色管理显示', null, '1467103645', '1467103645');
INSERT INTO `my_auth_item` VALUES ('/admin/role/search', '2', '角色信息搜索', null, '1467103694', '1467103694');
INSERT INTO `my_auth_item` VALUES ('/admin/role/update', '2', '角色管理编辑', null, '1467103674', '1467351824');
INSERT INTO `my_auth_item` VALUES ('/admin/role/view', '2', '角色信息详情', null, '1467351856', '1467351856');
INSERT INTO `my_auth_item` VALUES ('admin', '1', '超级管理员', null, '1467081917', '1467081917');
INSERT INTO `my_auth_item` VALUES ('deleteAuth', '2', '删除权限的权限', null, '1467274356', '1467274356');
INSERT INTO `my_auth_item` VALUES ('deleteRole', '2', '删除角色信息权限', null, '1467274307', '1467274307');
INSERT INTO `my_auth_item` VALUES ('deleteUser', '2', '删除管理员权限', null, '1467274151', '1467274151');
INSERT INTO `my_auth_item` VALUES ('updateAuth', '2', '权限信息操作', null, '1467351871', '1467351871');
INSERT INTO `my_auth_item` VALUES ('user', '1', '普通管理员', null, '1467081958', '1467715048');

-- ----------------------------
-- 角色对应权限表
-- ----------------------------
DROP TABLE IF EXISTS `my_auth_child`;
CREATE TABLE `my_auth_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `jc_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `my_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `jc_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `my_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- 角色对应权限信息数据
-- ----------------------------
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/admin/index');
INSERT INTO `my_auth_child` VALUES ('user', '/admin/admin/index');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/admin/login');
INSERT INTO `my_auth_child` VALUES ('user', '/admin/admin/login');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/admin/search');
INSERT INTO `my_auth_child` VALUES ('user', '/admin/admin/search');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/admin/update');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/admin/upload');
INSERT INTO `my_auth_child` VALUES ('user', '/admin/admin/upload');
INSERT INTO `my_auth_child` VALUES ('user', '/admin/admin/update');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/auth/index');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/auth/search');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/auth/update');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/menu/index');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/menu/search');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/menu/update');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/module/create');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/module/index');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/role/allocation');
INSERT INTO `my_auth_child` VALUES ('user', '/admin/role/allocation');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/role/create');
INSERT INTO `my_auth_child` VALUES ('user', '/admin/role/create');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/role/index');
INSERT INTO `my_auth_child` VALUES ('user', '/admin/role/index');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/role/search');
INSERT INTO `my_auth_child` VALUES ('user', '/admin/role/search');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/role/update');
INSERT INTO `my_auth_child` VALUES ('user', '/admin/role/update');
INSERT INTO `my_auth_child` VALUES ('admin', '/admin/role/view');
INSERT INTO `my_auth_child` VALUES ('user', '/admin/role/view');
INSERT INTO `my_auth_child` VALUES ('admin', 'deleteAuth');
INSERT INTO `my_auth_child` VALUES ('admin', 'deleteRole');
INSERT INTO `my_auth_child` VALUES ('admin', 'deleteUser');
INSERT INTO `my_auth_child` VALUES ('admin', 'updateAuth');

-- ----------------------------
-- Table 导航栏目表
-- ----------------------------
DROP TABLE IF EXISTS `my_menu`;
CREATE TABLE `my_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父类ID(只支持两级分类)',
  `menu_name` varchar(50) NOT NULL COMMENT '栏目名称',
  `icons` varchar(50) NOT NULL DEFAULT 'icon-desktop' COMMENT '使用的icons',
  `url` varchar(50) DEFAULT NULL COMMENT '访问地址',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态（1启用 0 停用）',
  `sort` int(4) NOT NULL DEFAULT '100' COMMENT '排序字段',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `create_id` int(11) NOT NULL DEFAULT '0' COMMENT '创建用户',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `update_id` int(11) NOT NULL DEFAULT '0' COMMENT '修改用户',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='使用SimpliQ的样式的导航栏样式';

-- ----------------------------
-- Records of my_menu
-- ----------------------------
INSERT INTO `my_menu` VALUES ('1', '0', '后台管理', ' icon-cog', '#', '1', '1', '1467007846', '2', '1467007846', '2');
INSERT INTO `my_menu` VALUES ('2', '1', '导航栏目', ' icon-align-justify', '/admin/menu/index', '1', '100', '1467008425', '2', '1467008594', '2');
INSERT INTO `my_menu` VALUES ('3', '1', '管理员信息', ' icon-user', '/admin/admin/index', '1', '2', '1467009023', '2', '1467009023', '2');
INSERT INTO `my_menu` VALUES ('4', '1', '权限管理', 'icon-fire', '/admin/auth/index', '1', '3', '1467009344', '2', '1467104026', '2');
INSERT INTO `my_menu` VALUES ('5', '1', '角色管理', 'icon-flag', '/admin/role/index', '1', '2', '1467009415', '2', '1467009415', '2');
INSERT INTO `my_menu` VALUES ('6', '1', '模块生成', ' icon-magic', '/admin/module/index', '1', '101', '1467010590', '2', '1467010590', '2');
