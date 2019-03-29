DROP TABLE IF EXISTS `articles`;


CREATE TABLE articles (
    `id` int(11) NOT NULL,
    `article_id` varchar(8) NOT NULL,
    `title` text NOT NULL,
    `page_url` varchar(256) NOT NULL,
    `pic_url` varchar(256) DEFAULT NULL,
    `intro` text,
    `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
