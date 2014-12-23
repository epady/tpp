ALTER TABLE `administrator` ADD `amount` DECIMAL(10,2) NOT NULL COMMENT '额度' AFTER `order_status`;

--
-- 表的结构 `service`
--

CREATE TABLE IF NOT EXISTS `service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='设备单营运人表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `service`
--

INSERT INTO `service` (`id`, `name`) VALUES
(2, 'A先生'),
(3, 'B小姐');


ALTER TABLE  `order` ADD  `receipt` TEXT NOT NULL COMMENT  '回执' AFTER  `updated`;


ALTER TABLE `order` ADD `service` VARCHAR(50) NOT NULL AFTER `receipt`;


-- 2014-10-27

ALTER TABLE `order` ADD `reimage` VARCHAR(255) NOT NULL COMMENT '回执图片' AFTER `image`;



-- 设备ID
ALTER TABLE `member` ADD `appid` VARCHAR(50) NOT NULL AFTER `order_status`, ADD `channel_id` VARCHAR(100) NOT NULL AFTER `appid`, ADD `user_id` VARCHAR(100) NOT NULL AFTER `channel_id`;




-- 毛重
ALTER TABLE `order` ADD `gw` VARCHAR(20) NOT NULL COMMENT '毛重' AFTER `service`;
