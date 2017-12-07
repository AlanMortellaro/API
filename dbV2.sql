CREATE DATABASE apidallas;

USE apidallas;


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `content` (
  `id` int(30) NOT NULL,
  `quantitiy` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_article` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `content` (`id`, `quantitiy`, `id_order`, `id_article`, `date`) VALUES
(1, 2, 1, 1, '2017-12-06 10:21:29'),
(2, 2, 2, 2, '2017-12-06 10:24:10');

CREATE TABLE `tbl_article` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tbl_article` (`id`, `name`, `price`) VALUES
(1, 'Kinder', 2),
(2, 'Bueno', 1);

CREATE TABLE `tbl_key` (
  `id` int(11) NOT NULL,
  `UID` varchar(50) NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tbl_key` (`id`, `UID`, `id_user`) VALUES
(1, '356743746378', 1),
(2, '4545452343423', NULL),
(3, '23424321123443', 1);

CREATE TABLE `tbl_order` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tbl_order` (`id`, `id_user`) VALUES
(1, 1),
(2, 2);

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  `token` varchar(50) DEFAULT NULL,
  `credit` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tbl_user` (`id`, `firstname`, `lastname`, `email`, `admin`, `token`, `credit`) VALUES
(1, 'Admin', 'Admin', 'Admin@rpn.ch', 1, '7ed17ac1ece95f1951a0c250f4ee9c55', 10000),
(2, 'John', 'Doe', NULL, 0, NULL, 2);

ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_content_id` (`id_order`),
  ADD KEY `FK_content_id_tbl_article` (`id_article`);

ALTER TABLE `tbl_article`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tbl_key`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tbl_key_id_tbl_user` (`id_user`);

ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `content`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `tbl_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `tbl_key`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `tbl_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `content`
  ADD CONSTRAINT `FK_content_id` FOREIGN KEY (`id_order`) REFERENCES `tbl_order` (`id`),
  ADD CONSTRAINT `FK_content_id_tbl_article` FOREIGN KEY (`id_article`) REFERENCES `tbl_article` (`id`);

ALTER TABLE `tbl_key`
  ADD CONSTRAINT `FK_tbl_key_id_tbl_user` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id`);

ALTER TABLE `tbl_order`
  ADD CONSTRAINT `FK_tbl_order_id_tbl_user` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id`);
