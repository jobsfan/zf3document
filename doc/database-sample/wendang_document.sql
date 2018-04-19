-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-04-19 11:19:04
-- 服务器版本： 5.7.19-0ubuntu0.16.04.1
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wendang_document`
--

-- --------------------------------------------------------

--
-- 表的结构 `achievement`
--

CREATE TABLE `achievement` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户，唯一',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '可以造榜'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='个人成就，主要是积分';

-- --------------------------------------------------------

--
-- 表的结构 `chapter`
--

CREATE TABLE `chapter` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '假装有很多管理员',
  `documentid` int(11) NOT NULL COMMENT '对应document中的id',
  `parentid` int(11) DEFAULT NULL COMMENT '父id',
  `foreigner` varchar(255) NOT NULL COMMENT '外文名',
  `chinese` varchar(255) DEFAULT NULL COMMENT '中文',
  `valid` int(11) NOT NULL DEFAULT '1' COMMENT '是否有效，尽量不删',
  `addtime` int(11) NOT NULL COMMENT '添加时间戳',
  `uptime` int(11) NOT NULL COMMENT '用于排序的时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文档的章节，组织结构';

-- --------------------------------------------------------

--
-- 表的结构 `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '假装有很多管理员',
  `title` varchar(255) NOT NULL COMMENT '文档标题，比如zend framework 3',
  `cover` varchar(255) DEFAULT NULL COMMENT '封面图',
  `description` text COMMENT '文档的描述',
  `language` int(11) NOT NULL DEFAULT '1' COMMENT '语言，1英文',
  `valid` int(11) NOT NULL DEFAULT '1' COMMENT '是否有效，尽量不删',
  `addtime` int(11) NOT NULL COMMENT '添加时间戳',
  `uptime` int(11) DEFAULT NULL COMMENT '修改时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文档表，比如zend framework，yii2，scrapy， django等';

-- --------------------------------------------------------

--
-- 表的结构 `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户的uid',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '假设有积分意外的东西，1表示积分',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '带正负号的数值',
  `mem` varchar(255) DEFAULT NULL COMMENT '积分变化的说明',
  `addtime` int(11) NOT NULL COMMENT '积分变化的时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分日志，加减，以及其他';

-- --------------------------------------------------------

--
-- 表的结构 `paragraph`
--

CREATE TABLE `paragraph` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '假装有很多管理员',
  `documentid` int(11) NOT NULL COMMENT '冗余设计，对应document中的id',
  `chapterid` int(11) NOT NULL COMMENT '章节id，一个章节，可以对应很多个小段落，为了更加利于翻译',
  `content` text NOT NULL COMMENT '段落内容，就是要翻译的内容',
  `valid` int(11) NOT NULL DEFAULT '1' COMMENT '是否有效，尽量不删',
  `addtime` int(11) NOT NULL COMMENT '添加时间戳',
  `uptime` int(11) NOT NULL COMMENT '排序时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文档章节内容，把一个章节分成很多小段';

-- --------------------------------------------------------

--
-- 表的结构 `translate`
--

CREATE TABLE `translate` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '假设有人来翻译',
  `documentid` int(11) NOT NULL COMMENT '冗余设计，对应document中的id',
  `chapterid` int(11) NOT NULL COMMENT '冗余设计，对应chapter中的id',
  `paragraphid` int(11) NOT NULL COMMENT '对应paragraph的id',
  `content` text NOT NULL COMMENT '翻译的中文内容',
  `goods` int(11) NOT NULL DEFAULT '0' COMMENT '赞',
  `bads` int(11) NOT NULL DEFAULT '0' COMMENT '踩',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态，0待审，1无害，2有毒，3采纳',
  `addtime` int(11) NOT NULL COMMENT '添加时间戳',
  `uptime` int(11) DEFAULT NULL COMMENT '修改时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='翻译的内容';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievement`
--
ALTER TABLE `achievement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD KEY `score` (`score`);

--
-- Indexes for table `chapter`
--
ALTER TABLE `chapter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreigner` (`foreigner`),
  ADD KEY `chinese` (`chinese`),
  ADD KEY `parentid` (`parentid`),
  ADD KEY `uptime` (`uptime`),
  ADD KEY `uid` (`uid`),
  ADD KEY `documentid` (`documentid`),
  ADD KEY `valid` (`valid`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `addtime` (`addtime`),
  ADD KEY `language` (`language`),
  ADD KEY `uid` (`uid`),
  ADD KEY `valid` (`valid`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `score` (`score`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `paragraph`
--
ALTER TABLE `paragraph`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `document` (`documentid`),
  ADD KEY `chapterid` (`chapterid`),
  ADD KEY `uptime` (`uptime`),
  ADD KEY `valid` (`valid`);

--
-- Indexes for table `translate`
--
ALTER TABLE `translate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `documentid` (`documentid`),
  ADD KEY `chapterid` (`chapterid`),
  ADD KEY `paragraphid` (`paragraphid`),
  ADD KEY `status` (`status`),
  ADD KEY `addtime` (`addtime`),
  ADD KEY `goods` (`goods`),
  ADD KEY `bads` (`bads`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `achievement`
--
ALTER TABLE `achievement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `chapter`
--
ALTER TABLE `chapter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `paragraph`
--
ALTER TABLE `paragraph`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `translate`
--
ALTER TABLE `translate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
