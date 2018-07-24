-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 24, 2018 at 03:20 PM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `telus`
--
CREATE DATABASE IF NOT EXISTS `telus` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `telus`;

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `selector` varchar(254) NOT NULL,
  `hashedValidator` varchar(254) NOT NULL,
  `expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deviceTable`
--

CREATE TABLE `deviceTable` (
  `id` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `macadd` varchar(17) NOT NULL DEFAULT '00:00:00:00:00:00',
  `name` varchar(100) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `token` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `port` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) DEFAULT NULL,
  `provisioned` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loginInfo`
--

CREATE TABLE `loginInfo` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `username` varchar(254) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `hash` varchar(128) NOT NULL,
  `conf_str` varchar(32) NOT NULL,
  `confirmed` int(1) NOT NULL DEFAULT '0',
  `profile_img` varchar(254) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `passwordrecovery`
--

CREATE TABLE `passwordrecovery` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `token` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `portTable`
--

CREATE TABLE `portTable` (
  `id` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `secid` varchar(36) NOT NULL,
  `ipaddr` varchar(15) NOT NULL,
  `ospid` varchar(36) NOT NULL,
  `osdid` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `routerTable`
--

CREATE TABLE `routerTable` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `ipaddr` varchar(15) NOT NULL,
  `osrid` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subnetTable`
--

CREATE TABLE `subnetTable` (
  `id` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `subname` varchar(100) NOT NULL,
  `osnetid` varchar(36) NOT NULL,
  `cidr` varchar(18) NOT NULL,
  `ossubid` varchar(36) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `icon` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`userid`);

--
-- Indexes for table `deviceTable`
--
ALTER TABLE `deviceTable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sid` (`sid`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `loginInfo`
--
ALTER TABLE `loginInfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passwordrecovery`
--
ALTER TABLE `passwordrecovery`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid_2` (`uid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `portTable`
--
ALTER TABLE `portTable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rid` (`rid`),
  ADD KEY `sid` (`sid`);

--
-- Indexes for table `routerTable`
--
ALTER TABLE `routerTable`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`uid`);

--
-- Indexes for table `subnetTable`
--
ALTER TABLE `subnetTable`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ossubid` (`ossubid`),
  ADD KEY `rid` (`rid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `deviceTable`
--
ALTER TABLE `deviceTable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `loginInfo`
--
ALTER TABLE `loginInfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `passwordrecovery`
--
ALTER TABLE `passwordrecovery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `portTable`
--
ALTER TABLE `portTable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `routerTable`
--
ALTER TABLE `routerTable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `subnetTable`
--
ALTER TABLE `subnetTable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `auth_tokens_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `loginInfo` (`id`);

--
-- Constraints for table `deviceTable`
--
ALTER TABLE `deviceTable`
  ADD CONSTRAINT `deviceTable_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `subnetTable` (`id`);

--
-- Constraints for table `passwordrecovery`
--
ALTER TABLE `passwordrecovery`
  ADD CONSTRAINT `passwordRecovery_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `loginInfo` (`id`);

--
-- Constraints for table `portTable`
--
ALTER TABLE `portTable`
  ADD CONSTRAINT `portTable_ibfk_1` FOREIGN KEY (`rid`) REFERENCES `routerTable` (`id`),
  ADD CONSTRAINT `portTable_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `subnetTable` (`id`);

--
-- Constraints for table `routerTable`
--
ALTER TABLE `routerTable`
  ADD CONSTRAINT `routerTable_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `loginInfo` (`id`);

--
-- Constraints for table `subnetTable`
--
ALTER TABLE `subnetTable`
  ADD CONSTRAINT `subnetTable_ibfk_1` FOREIGN KEY (`rid`) REFERENCES `routerTable` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
