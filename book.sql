/*
Navicat MySQL Data Transfer

Source Server         : localhost_phpwamp
Source Server Version : 50554
Source Host           : 127.0.0.1:3306
Source Database       : book

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2017-10-13 15:26:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for author
-- ----------------------------
DROP TABLE IF EXISTS `author`;
CREATE TABLE `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '笔名',
  `realname` varchar(255) DEFAULT NULL COMMENT ' 真实姓名',
  `phone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='作者';

-- ----------------------------
-- Records of author
-- ----------------------------

-- ----------------------------
-- Table structure for book
-- ----------------------------
DROP TABLE IF EXISTS `book`;
CREATE TABLE `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) DEFAULT NULL COMMENT '分类 ID',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态，0连载，1完结',
  `type` tinyint(1) DEFAULT NULL COMMENT '类型，0男，1女',
  `picture` text COMMENT '封面',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `author_id` int(11) DEFAULT NULL COMMENT '作者 ID',
  `summary` varchar(255) DEFAULT NULL COMMENT '摘要',
  `click_num` int(11) DEFAULT '0' COMMENT '点击量',
  `submit_num` int(11) DEFAULT '0' COMMENT '保存量',
  `buy_num` int(11) DEFAULT '0' COMMENT '购买量',
  `amount` int(11) DEFAULT NULL COMMENT '整书价格',
  `free_status` tinyint(1) DEFAULT '0' COMMENT '免费状态，0不免费，1限时免费，2完全免费',
  `free_start` int(11) DEFAULT NULL COMMENT '免费开始时间',
  `free_end` int(11) DEFAULT NULL COMMENT '免费结束时间',
  `is_hot` tinyint(1) DEFAULT '0' COMMENT '热门，0否，1是',
  `is_recommend` tinyint(1) DEFAULT '1' COMMENT '推荐，0否，1是',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '修改时间',
  `edited_at` int(11) DEFAULT NULL COMMENT '文章更新时间',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小说文章 主表';

-- ----------------------------
-- Records of book
-- ----------------------------

-- ----------------------------
-- Table structure for book_cate
-- ----------------------------
DROP TABLE IF EXISTS `book_cate`;
CREATE TABLE `book_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '修改时间',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小说分类';

-- ----------------------------
-- Records of book_cate
-- ----------------------------

-- ----------------------------
-- Table structure for book_content
-- ----------------------------
DROP TABLE IF EXISTS `book_content`;
CREATE TABLE `book_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL COMMENT '小说主表 ID',
  `order_num` int(11) DEFAULT NULL COMMENT ' 序号',
  `name` varchar(255) DEFAULT NULL COMMENT ' 标题',
  `content` text COMMENT '内容',
  `price` int(11) DEFAULT NULL COMMENT ' 章节价格',
  `click_num` int(11) DEFAULT NULL COMMENT '点击量',
  `buy_num` int(11) DEFAULT NULL COMMENT '购买量',
  `created_at` int(11) DEFAULT NULL COMMENT ' ',
  `edited_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小说文章 内容表';

-- ----------------------------
-- Records of book_content
-- ----------------------------

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL COMMENT '上级 ID',
  `name` varchar(255) DEFAULT NULL COMMENT ' 名称',
  `level` int(11) DEFAULT NULL COMMENT ' 层级',
  `icon` text COMMENT ' 图标',
  `sort` int(11) DEFAULT NULL COMMENT ' 排序',
  `url` text COMMENT ' 路由地址',
  `infos` text COMMENT ' 描述',
  `display` tinyint(1) DEFAULT '0' COMMENT ' 显示状态，0隐藏，1显示',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态，0禁用，1启用',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='功能与菜单';

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '0', '系统设置', '1', '<img src=\"/static/system/img/setting_tools.png\"/>', '0', '/system/setting#', '', '1', '1', '1507862534', '1507876492', null);
INSERT INTO `menu` VALUES ('2', '1', '功能与菜单', '2', '<img src=\"/static/system/img/monitor_window_3d.png\"/>', '0', '/system/menu', '', '1', '1', '1507865210', '1507877960', null);
INSERT INTO `menu` VALUES ('3', '1', '权限与角色', '2', '<img src=\"/static/system/img/role.png\"/>', '0', '/system/role', '', '1', '1', '1507865414', '1507865414', null);
INSERT INTO `menu` VALUES ('4', '1', '系统用户', '2', '<img src=\"/static/system/img/folder_user.png\"/>', '0', '/system/user', '', '1', '1', '1507866165', '1507866165', null);
INSERT INTO `menu` VALUES ('5', '2', '添加菜单', '3', '<img src=\"/static/system/img/add.png\"/>', '0', '/system/menu/add', '', '0', '1', '1507872000', '1507877976', null);

-- ----------------------------
-- Table structure for reader
-- ----------------------------
DROP TABLE IF EXISTS `reader`;
CREATE TABLE `reader` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL COMMENT '邀请人ID',
  `openid` varchar(255) DEFAULT NULL COMMENT '微信openid',
  `book_money` int(11) DEFAULT NULL COMMENT '书币',
  `vip_end` int(11) DEFAULT NULL COMMENT '会员结束时间',
  `created_at` varchar(255) DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  `login_at` int(11) DEFAULT NULL COMMENT ' 最近登录时间',
  `login_ip` varchar(255) DEFAULT NULL COMMENT ' 最近登录IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100000 DEFAULT CHARSET=utf8 COMMENT='读者 基本信息';

-- ----------------------------
-- Records of reader
-- ----------------------------

-- ----------------------------
-- Table structure for reader_bookmark
-- ----------------------------
DROP TABLE IF EXISTS `reader_bookmark`;
CREATE TABLE `reader_bookmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reader_id` int(11) DEFAULT NULL COMMENT '读者ID',
  `book_id` int(11) DEFAULT NULL COMMENT ' 小说ID',
  `content_id` int(11) DEFAULT NULL COMMENT ' 内容 ID',
  `created_at` int(11) DEFAULT NULL COMMENT ' 创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT ' 更新时间',
  `read_at` int(11) DEFAULT NULL COMMENT ' 最近阅读时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='读者 书签';

-- ----------------------------
-- Records of reader_bookmark
-- ----------------------------

-- ----------------------------
-- Table structure for reader_bookshelf
-- ----------------------------
DROP TABLE IF EXISTS `reader_bookshelf`;
CREATE TABLE `reader_bookshelf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reader_id` int(11) DEFAULT NULL COMMENT '读者ID',
  `book_id` int(11) DEFAULT NULL COMMENT '小说 ID',
  `content_id` int(11) DEFAULT NULL COMMENT '最近阅读内容ID',
  `created_at` int(11) DEFAULT NULL COMMENT ' 创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
  `read_at` int(11) DEFAULT NULL COMMENT '最近阅读时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='读者 书架';

-- ----------------------------
-- Records of reader_bookshelf
-- ----------------------------

-- ----------------------------
-- Table structure for reader_readlast
-- ----------------------------
DROP TABLE IF EXISTS `reader_readlast`;
CREATE TABLE `reader_readlast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reader_id` int(11) DEFAULT NULL COMMENT '读者ID',
  `book_id` int(11) DEFAULT NULL COMMENT ' 小说ID',
  `content_id` int(11) DEFAULT NULL COMMENT ' 内容 ID',
  `created_at` int(11) DEFAULT NULL COMMENT ' 创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT ' 更新时间',
  `read_at` int(11) DEFAULT NULL COMMENT ' 最近阅读时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='读者 最近阅读';

-- ----------------------------
-- Records of reader_readlast
-- ----------------------------

-- ----------------------------
-- Table structure for recharge_orders
-- ----------------------------
DROP TABLE IF EXISTS `recharge_orders`;
CREATE TABLE `recharge_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0' COMMENT '充值类型，0书币，1会员',
  `orders_no` varchar(255) DEFAULT NULL COMMENT '订单号',
  `trade_no` varchar(255) DEFAULT NULL COMMENT '微信交易号',
  `reader_id` int(11) DEFAULT NULL COMMENT '读者ID',
  `price` int(11) DEFAULT NULL COMMENT ' 订单金额（￥）',
  `number` int(11) DEFAULT NULL COMMENT '购买书币 或会员时长',
  `gift_num` int(11) DEFAULT NULL COMMENT ' 充值赠送书币  或会员时长',
  `pay_num` float DEFAULT NULL COMMENT ' 支付金额',
  `result_code` varchar(255) DEFAULT NULL COMMENT ' 支付返回码',
  `created_at` int(11) DEFAULT NULL COMMENT ' 创建时间',
  `finished_at` int(11) DEFAULT NULL COMMENT ' 完成时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值与会员 订单';

-- ----------------------------
-- Records of recharge_orders
-- ----------------------------

-- ----------------------------
-- Table structure for recharge_price
-- ----------------------------
DROP TABLE IF EXISTS `recharge_price`;
CREATE TABLE `recharge_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0' COMMENT '充值类型，0书币，1会员',
  `price` int(11) DEFAULT NULL COMMENT '价格（￥）',
  `number` int(11) DEFAULT NULL COMMENT '书币 或会员时长',
  `gift_num` int(11) DEFAULT NULL COMMENT '充值 赠送书币 或会员时长',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值与会员 价格';

-- ----------------------------
-- Records of recharge_price
-- ----------------------------

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL COMMENT '上级 ID',
  `name` varchar(255) DEFAULT NULL COMMENT ' 名称',
  `level` int(11) DEFAULT NULL COMMENT ' 层级',
  `infos` text COMMENT ' 描述',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态，0禁用，1启用',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限与角色';

-- ----------------------------
-- Records of role
-- ----------------------------

-- ----------------------------
-- Table structure for system
-- ----------------------------
DROP TABLE IF EXISTS `system`;
CREATE TABLE `system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `qr_code` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统信息';

-- ----------------------------
-- Records of system
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL COMMENT '角色ID',
  `name` varchar(255) DEFAULT '' COMMENT ' 名称',
  `phone` varchar(255) DEFAULT NULL COMMENT '电话',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `infos` text COMMENT ' 描述',
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `password` varchar(255) DEFAULT NULL COMMENT ' 密码',
  `secret_key` varchar(255) DEFAULT NULL COMMENT ' 密钥',
  `status` tinyint(255) DEFAULT '1' COMMENT '状态，0禁用，1启用',
  `created_at` int(255) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统用户';

-- ----------------------------
-- Records of user
-- ----------------------------
