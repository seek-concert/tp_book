/*
Navicat MySQL Data Transfer

Source Server         : locahost
Source Server Version : 50554
Source Host           : localhost:3306
Source Database       : book

Target Server Type    : MYSQL
Target Server Version : 50554
File Encoding         : 65001

Date: 2017-10-20 15:50:11
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='作者';

-- ----------------------------
-- Records of author
-- ----------------------------
INSERT INTO `author` VALUES ('1', '觅树知音', '张', '15912345678', '1508467893', '1508467893', null);
INSERT INTO `author` VALUES ('2', '土豆', '李四', '13012341234', '1508467913', '1508467913', null);

-- ----------------------------
-- Table structure for banner
-- ----------------------------
DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) DEFAULT NULL COMMENT ' 书ID',
  `picture` text COMMENT ' 广告图',
  `sort` int(11) DEFAULT NULL COMMENT ' 排序',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='首页轮播图';

-- ----------------------------
-- Records of banner
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
  `online` tinyint(1) DEFAULT '1' COMMENT '在架状态，0下架，1在架',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '修改时间',
  `edited_at` int(11) DEFAULT NULL COMMENT '文章更新时间',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='小说文章 主表';

-- ----------------------------
-- Records of book
-- ----------------------------
INSERT INTO `book` VALUES ('1', '6', '1', '0', '/uploads/20171020\\4f540f84a22baf966f86a9519bc1708a.jpg', '巴黎圣母院', '1', '巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院巴黎圣母院', '0', '0', '0', '0', '1', '2017', '2017', '0', '1', '0', '1', '1508468051', '1508481282', null, null);
INSERT INTO `book` VALUES ('2', '6', '1', '0', '/uploads/20171020\\72c76d5b839179f4fd9f4e354096066f.jpg', '战争与和平', '1', '战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平战争与和平', '0', '0', '0', '100', '0', '0', '0', '1', '1', '0', '1', '1508468095', '1508481271', null, null);
INSERT INTO `book` VALUES ('3', '6', '1', '0', '/uploads/20171020\\3627e4eaff26529e306926ecf4f6613d.jpg', '童年', '2', '童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年童年', '0', '0', '0', '0', '1', '1508342400', '1508515199', '1', '1', '0', '1', '1508468124', '1508482125', null, null);
INSERT INTO `book` VALUES ('4', '1', '1', '0', '/uploads/20171020\\b03728ca92d8b6c25ab39772d8e7e27c.jpg', '第一本书', '1', '第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书第一本书', '0', '0', '0', '0', '1', '2017', '2017', '1', '1', '0', '1', '1508468165', '1508479501', null, null);
INSERT INTO `book` VALUES ('5', '3', '1', '0', '/uploads/20171020\\ae275c880eef845dcbf509c1ae8573fd.jpg', '鬼吹灯', '1', '小说中作者首创历史上四大盗墓门派——摸金、卸岭、发丘、搬山，其中摸金是技术含量最高，规矩最多的门派。“人点烛，鬼吹灯”是传说中摸金派的不传之秘，意为进入古墓之中先在东南角点燃一支蜡烛才能开棺，如果蜡烛熄灭，须速速退出，不可取一物。相传这是祖师爷所定的一条活人与死人的契约，千年传承，不得破。', '0', '0', '0', '0', '1', '2017', '2017', '0', '1', '0', '1', '1508481019', '1508481019', null, null);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='小说分类';

-- ----------------------------
-- Records of book_cate
-- ----------------------------
INSERT INTO `book_cate` VALUES ('1', '都市', '0', '1508467927', '1508467927', null);
INSERT INTO `book_cate` VALUES ('2', '玄幻', '0', '1508467937', '1508467937', null);
INSERT INTO `book_cate` VALUES ('3', '惊悚', '0', '1508467945', '1508467945', null);
INSERT INTO `book_cate` VALUES ('4', '历史', '0', '1508467970', '1508467970', null);
INSERT INTO `book_cate` VALUES ('5', '科幻', '0', '1508467975', '1508467975', null);
INSERT INTO `book_cate` VALUES ('6', '名著', '0', '1508467981', '1508467981', null);

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
-- Table structure for data_setting
-- ----------------------------
DROP TABLE IF EXISTS `data_setting`;
CREATE TABLE `data_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `banner` int(11) DEFAULT NULL COMMENT '轮播显示条数',
  `recommend` int(11) DEFAULT NULL COMMENT '主编推荐显示条数',
  `hot` int(11) DEFAULT NULL COMMENT ' 热门显示条数',
  `new_recommend` int(11) DEFAULT NULL COMMENT '新书推荐显示条数',
  `free` int(11) DEFAULT NULL COMMENT '限时免费显示条数',
  `guess` int(11) DEFAULT NULL COMMENT ' 猜你喜欢显示条数',
  `buy` int(11) DEFAULT NULL COMMENT ' 畅销显示条数',
  `pagenum` int(11) DEFAULT NULL COMMENT ' 列表每页条数',
  `ranking_top` int(11) DEFAULT NULL COMMENT ' 排行置顶条数',
  `ranking_total` int(11) DEFAULT NULL COMMENT ' 排行显示条数',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='数据设置';

-- ----------------------------
-- Records of data_setting
-- ----------------------------
INSERT INTO `data_setting` VALUES ('1', '3', '7', '3', '10', '3', '3', '3', '20', '3', '3', '1508481061');

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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='功能与菜单';

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '0', '系统设置', '1', '<img src=\"/static/system/img/setting_tools.png\"/>', '0', '/system/setting#', '', '1', '1', '1507862534', '1508407873', null);
INSERT INTO `menu` VALUES ('2', '1', '功能与菜单', '2', '<img src=\"/static/system/img/monitor_window_3d.png\"/>', '0', '/system/menu/index', '', '1', '1', '1507865210', '1507898297', null);
INSERT INTO `menu` VALUES ('3', '1', '权限与角色', '2', '<img src=\"/static/system/img/role.png\"/>', '0', '/system/role/index', '', '1', '1', '1507865414', '1507888531', null);
INSERT INTO `menu` VALUES ('4', '1', '系统用户', '2', '<img src=\"/static/system/img/folder_user.png\"/>', '0', '/system/user/index', '', '1', '1', '1507866165', '1507889575', null);
INSERT INTO `menu` VALUES ('5', '2', '添加菜单', '3', '<img src=\"/static/system/img/add.png\"/>', '0', '/system/menu/add', '', '0', '1', '1507872000', '1507897493', null);
INSERT INTO `menu` VALUES ('6', '2', '菜单详情', '3', '<img src=\"/static/system/img/page_white_paste.png\"/>', '0', '/system/menu/detail', '', '0', '1', '1507880446', '1507880446', null);
INSERT INTO `menu` VALUES ('7', '2', '菜单修改', '3', '<img src=\"/static/system/img/richtext_editor.png\"/>', '0', '/system/menu/edit', '', '0', '1', '1507880485', '1507896298', null);
INSERT INTO `menu` VALUES ('8', '0', '内容管理', '1', '<img src=\"/static/system/img/bricks.png\"/>', '0', '/system/content#', '', '1', '1', '1507880673', '1508144387', null);
INSERT INTO `menu` VALUES ('9', '8', '作者管理', '2', '<img src=\"/static/system/img/outlook_new_meeting.png\"/>', '0', '/system/author/index', '', '1', '1', '1508145488', '1508145488', null);
INSERT INTO `menu` VALUES ('10', '8', '小说分类', '2', '<img src=\"/static/system/img/server_database.png\"/>', '0', '/system/bookcate/index', '', '1', '1', '1508145595', '1508145595', null);
INSERT INTO `menu` VALUES ('11', '1', '个人中心', '2', '<img src=\"/static/system/img/report_user.png\"/>', '0', '/system/user/info', '', '0', '1', '1508145659', '1508145659', null);
INSERT INTO `menu` VALUES ('12', '8', '小说管理', '2', '<img src=\"/static/system/img/books.png\"/>', '0', '/system/book/index', '', '1', '1', '1508145720', '1508145720', null);
INSERT INTO `menu` VALUES ('13', '8', '充值设置', '2', '<img src=\"/static/system/img/add_on.png\"/>', '0', '/system/rechargeprice/index', '', '1', '1', '1508146326', '1508146326', null);
INSERT INTO `menu` VALUES ('14', '0', '业务数据', '1', '<img src=\"/static/system/img/sharepoint.png\"/>', '0', '/system/datas#', '', '1', '1', '1508146527', '1508146527', null);
INSERT INTO `menu` VALUES ('15', '14', '读者管理', '2', '<img src=\"/static/system/img/outlook_new_meeting.png\"/>', '0', '/system/reader/index', '', '1', '1', '1508146593', '1508146593', null);
INSERT INTO `menu` VALUES ('16', '14', '支付订单', '2', '<img src=\"/static/system/img/small_business.png\"/>', '0', '/system/rechargeorders/index', '', '1', '1', '1508146679', '1508146679', null);
INSERT INTO `menu` VALUES ('17', '2', '菜单排序', '3', '<img src=\"/static/system/img/text_list_numbers.png\"/>', '0', '/system/menu/sort', '', '0', '1', '1508146897', '1508146897', null);
INSERT INTO `menu` VALUES ('18', '2', '菜单显示状态', '3', '<img src=\"/static/system/img/monitor_window_3d.png\"/>', '0', '/system/menu/show', '', '0', '1', '1508146986', '1508146986', null);
INSERT INTO `menu` VALUES ('19', '2', '菜单使用状态', '3', '<img src=\"/static/system/img/checked.png\"/>', '0', '/system/menu/status', '', '0', '1', '1508147023', '1508147023', null);
INSERT INTO `menu` VALUES ('20', '2', '删除菜单', '3', '<img src=\"/static/system/img/broom.png\"/>', '0', '/system/menu/delete', '', '0', '1', '1508147061', '1508147061', null);
INSERT INTO `menu` VALUES ('21', '2', '菜单恢复', '3', '<img src=\"/static/system/img/recycle.png\"/>', '0', '/system/menu/restore', '', '0', '1', '1508147092', '1508147092', null);
INSERT INTO `menu` VALUES ('22', '2', '菜单销毁', '3', '<img src=\"/static/system/img/destroy.png\"/>', '0', '/system/menu/destroy', '', '0', '1', '1508147134', '1508147140', null);
INSERT INTO `menu` VALUES ('23', '2', '所有菜单', '3', '<img src=\"/static/system/img/navigation.png\"/>', '0', '/system/menu/all', '', '0', '1', '1508147228', '1508147228', null);
INSERT INTO `menu` VALUES ('24', '8', '应用设置', '2', '<img src=\"/static/system/img/cog.png\"/>', '0', '/system/setting/index', '', '1', '1', '1508147838', '1508227822', null);

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
  `created_at` int(255) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
  `login_at` int(11) DEFAULT NULL COMMENT ' 最近登录时间',
  `login_ip` varchar(255) DEFAULT NULL COMMENT ' 最近登录IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='读者 基本信息';

-- ----------------------------
-- Records of reader
-- ----------------------------
INSERT INTO `reader` VALUES ('1', null, 'gdfgdfgdfgd4546454', '100', null, '1508470762', '1508470762', '1508470762', '192.168.0.1');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='读者 书架';

-- ----------------------------
-- Records of reader_bookshelf
-- ----------------------------
INSERT INTO `reader_bookshelf` VALUES ('1', '1', '5', null, null, null, null);

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
  `is_admin` tinyint(1) DEFAULT '0' COMMENT '角色类型，0受约束角色，1超级管理员',
  `level` int(11) DEFAULT NULL COMMENT ' 层级',
  `infos` text COMMENT ' 描述',
  `menu_ids` text COMMENT '授权菜单ID集合',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态，0禁用，1启用',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='权限与角色';

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '0', '内置超级管理员', '1', '1', '', '[]', '1', '1507947384', '1508117290', null);
INSERT INTO `role` VALUES ('2', '1', '内置管理员', '0', '2', '', '[\"1\",\"2\",\"5\",\"6\",\"7\",\"4\",\"8\"]', '1', '1507947460', '1508147309', null);

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '应用名称',
  `qr_code` text COMMENT '公众号二维码',
  `appid` varchar(255) DEFAULT NULL COMMENT '微信appid',
  `appsecret` varchar(255) DEFAULT NULL COMMENT '微信secret',
  `mchid` varchar(255) DEFAULT NULL COMMENT '商户号',
  `mchkey` varchar(255) DEFAULT NULL COMMENT '商户密钥',
  `logo` text,
  `title` varchar(255) DEFAULT NULL COMMENT '网站标题',
  `keyword` varchar(255) DEFAULT NULL COMMENT ' 关键词',
  `description` text COMMENT ' 描述',
  `favicon` text COMMENT 'favicon',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='应用配置';

-- ----------------------------
-- Records of setting
-- ----------------------------
INSERT INTO `setting` VALUES ('1', '小说', 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQGh8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyM2daSHN1LTlmZDMxMDAwMDAwN18AAgQB2_VZAwQAAAAA', 'wx54f10549c50ce050', 'dd860df8562da122b03cbfcb75f73714', 'cc', 'dd', null, '小说', '', '', null, '1508231690', '1508236834');

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
  `login_at` int(11) DEFAULT NULL COMMENT '最近登录时间',
  `login_ip` varchar(255) DEFAULT NULL COMMENT '最近登录IP',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态，0禁用，1启用',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='系统用户';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', '1', '', '', '', '', 'demo', 'e10adc3949ba59abbe56e057f20f883e', 'e94e5817cbf45ba73ae22527672dafdd', '1508407857', '127.0.0.1', '1', '1507970329', '1508144712', null);
INSERT INTO `user` VALUES ('2', '2', '', '', '', '', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '9df991ab8755df426239cef0afa87077', '1508147953', '127.0.0.1', '1', '1507971362', '1508141160', null);
